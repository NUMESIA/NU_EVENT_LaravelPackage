<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    |   Liste of components and their urls
    */

    'components' => [
        "bpm" => env("INTELLIGENSIA_BPM_URL"),
        "agenda" => env("INTELLIGENSIA_AGENDA_URL"),
        "book" => env("INTELLIGENSIA_BOOK_URL"),
        "cms" => env("INTELLIGENSIA_CMS_URL"),
        "tracker" => env("INTELLIGENSIA_TRACKER_URL"),
        "auth" => env("INTELLIGENSIA_AUTH_URL"),
    ],

];
