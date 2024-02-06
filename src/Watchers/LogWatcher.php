<?php

namespace Namaa\NamaaLogger\Watchers;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Arr;
use Namaa\NamaaLogger\IncomingEntry;
use Namaa\NamaaLogger\NamaaLogger;
use Psr\Log\LogLevel;
use Throwable;

class LogWatcher extends Watcher
{
    /**
     * The available log level priorities.
     */
    private const PRIORITIES = [
        LogLevel::DEBUG => 100,
        LogLevel::INFO => 200,
        LogLevel::NOTICE => 250,
        LogLevel::WARNING => 300,
        LogLevel::ERROR => 400,
        LogLevel::CRITICAL => 500,
        LogLevel::ALERT => 550,
        LogLevel::EMERGENCY => 600,
    ];

    /**
     * Register the watcher.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function register($app)
    {
        $app['events']->listen(MessageLogged::class, [$this, 'recordLog']);
    }

    /**
     * Record a message was logged.
     *
     * @param  \Illuminate\Log\Events\MessageLogged  $event
     * @return void
     */
    public function recordLog(MessageLogged $event)
    {
        if (
            $this->shouldIgnore($event)
        ) {
            return;
        }

        NamaaLogger::recordLog(
            IncomingEntry::make([
                'uri' => str_replace($event->request->root(), '', $event->request->fullUrl()) ?: '/',
                'method' => $event->request->method(),
                'controller_action' => optional($event->request->route())->getActionName(),
                'response_status' => $event->response->getStatusCode(),
                'level' => $event->level,
                'message' => (string) $event->message,
                'context' => Arr::except($event->context, ['telescope']),
            ])->tags($this->tags($event))
        );
    }

    /**
     * Extract tags from the given event.
     *
     * @param  \Illuminate\Log\Events\MessageLogged  $event
     * @return array
     */
    private function tags($event)
    {
        return $event->context['telescope'] ?? [];
    }

    /**
     * Determine if the event should be ignored.
     *
     * @param  mixed  $event
     * @return bool
     */
    private function shouldIgnore($event)
    {
        if (isset($event->context['exception']) && $event->context['exception'] instanceof Throwable) {
            return true;
        }

        $minimumTelescopeLogLevel = static::PRIORITIES[$this->options['level'] ?? 'debug']
            ?? static::PRIORITIES[LogLevel::DEBUG];

        $eventLogLevel = static::PRIORITIES[$event->level]
            ?? static::PRIORITIES[LogLevel::DEBUG];

        return $eventLogLevel < $minimumTelescopeLogLevel;
    }
}
