# NU_EVENTS_LaravelPackage
Laravel package that helps to send events across components

This laravel auth package is a private package so we can't just require it using composer, that's why we have to add a vcs repository to tell
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
