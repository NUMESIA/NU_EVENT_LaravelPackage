# NU_EVENTS_LaravelPackage
Laravel package that helps to send events across components

This laravel event package is a private package so we can't just require it using composer, that's why we have to add a vcs repository to tell
composer from which url the package must be loaded.

    "repositories": [
        {
            "type": "vcs",
            "url":  "git@github.com:NUMESIA/NU_EVENT_LaravelPackage.git"
        }
    ],
    "require": {
        "NUMESIA/laravel-events": "0.0.*"
    },


Once this has finished, you will need to add the service provider to the providers array in your app.php config as follows:

    Numesia\NuEvent\Providers\NuEventServiceProvider::class,

Next, also in the app.php config file, under the aliases array, you may want to add NuEvent facades.

    'NuEvent' => Numesia\NuEvent\Facades\NuEvent::class,

Then publish configs

    php artisan vendor:publish --provider="Numesia\NuEvent\Providers\NuEventServiceProvider" --tag=config

Finally, you will want to change your `NUEVENT_TOKEN` key from `.env` file:

    NUEVENT_TOKEN=YourEventSecretKey

## How to use ?
NuEvent comes with a global function nuEvent() which help you te send easily events to other components

```
    /**
     * Send an event to components
     *
     * @param      <string>  $eventName   The event name
     * @param      <string>  $eventData   The event data
     * @param      <mixed>   $components  The components
     */
    function nuEvent($eventName, $eventData, $components = null)
```
> $components can be a string (bpm), an array ['bpm', 'cms'] or nothing to broadcast to all components

You can Also use the Facade NuEvent if you want to perform the same task

```
/**
 * Emit event to component name
 *
 * @param      <string>  $componentName  The component name
 * @param      <string>  $eventName      The event name
 * @param      <string>  $eventData      The event data
 */
NuEvent::emit($componentName, $eventName, $eventData)

/**
 * Broadcast event to all components
 *
 * @param      <string>  $eventName   The event name
 * @param      <string>  $eventData   The event data
 */
NuEvent::broadcast($eventName, $eventData)

/**
 * Dispatch event to selected components
 *
 * @param      <string>  $eventName   The event name
 * @param      <string>  $eventData   The event data
 * @param      <mixed>   $components  The components
 */
NuEvent::dispatch($eventName, $eventData, $components = null)
```

> /!\ When you send an event, NuEvent add automatically a suffix '**nuEvents:**' to your event name

### Example

In this example we will try to send an event from book to bpm

#### BOOK Component
All we have to do is adding this command somewhere

```
nuEvent('book.ping', "Hello World", "bpm");
```

#### BPM Component
We should create a listener (don't forget to add it into your EventServiceProvider)

* EventServiceProvider
```
//EventServiceProvider
protected $subscribe = [
    'App\Listeners\NuEventSubscriber',
];
```

* NuEventSubscriber

```
// App\Listeners\NuEventSubscriber
<?php

namespace App\Listeners;

use App\Jobs\SendNotification;

class NuEventSubscriber
{
    /**
     * Listening ActivityWasAborted event
     */
    public function onBookPing($event)
    {
        \Log::info($event);
    }

    /**
     * Register listeners for the subscriber
     *
     * @param Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'nuEvents:book.ping',
            'App\Listeners\NuEventSubscriber@onBookPing'
        );
    }
}

