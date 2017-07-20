<?php

use ;

if (!function_exists('nuEvent')) {
    /**
     * Get Settings.
     *
     * @param string $key
     *
     * @return string/Setting
     */
    function nuEvent($eventName, $eventData, $components = null)
    {
        \NuEvent::dispache($eventName, $eventData, $components);
    }
}
