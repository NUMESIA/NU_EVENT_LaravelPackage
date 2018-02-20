<?php

if (!function_exists('nuEvent')) {
    /**
     * Send an event to components
     *
     * @param      <string>  $eventName   The event name
     * @param      <string>  $eventData   The event data
     * @param      <mixed>   $components  The components
     */
    function nuEvent($eventName, $eventData, $components = null, $callback = null)
    {
        $response = \NuEvent::dispatch($eventName, $eventData, $components, $callback);

        if ($callback) {
            $callback($response);
        }
    }
}
