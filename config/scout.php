<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Search Driver
    |--------------------------------------------------------------------------
    |
    | This option controls which search driver will be used by Scout. By
    | default, Scout uses the "algolia" driver, but you may configure a
    | different driver if you wish to use another search service.
    |
    | Supported: "algolia", "database", "meilisearch"
    |
    */

    'driver' => env('SCOUT_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Indexable Model
    |--------------------------------------------------------------------------
    |
    | This configuration option allows you to specify a model that should
    | be indexed. This can be useful if you want to track a particular
    | type of model for searching.
    |
    */

    'index' => 'default',

    /*
    |--------------------------------------------------------------------------
    | Database Search Driver Configuration
    |--------------------------------------------------------------------------
    |
    | If you are using the "database" driver, you may configure the table
    | name and indexable columns. The table will be used for storing
    | the searchable records.
    |
    */

    'database' => [
        'connection' => null, // (optional) the database connection to use for the search table.
        'table' => 'searchable', // table name for searchable index
    ],

    /*
    |--------------------------------------------------------------------------
    | Algolia Search Configuration
    |--------------------------------------------------------------------------
    |
    | This option is used for setting up Algolia. You will need to provide
    | your own Algolia API credentials, which are available at
    | https://www.algolia.com.
    |
    */

    'algolia' => [
        'id' => env('ALGOLIA_APP_ID'),
        'secret' => env('ALGOLIA_SECRET'),
    ],

    /*
    |--------------------------------------------------------------------------
    | MeiliSearch Search Configuration
    |--------------------------------------------------------------------------
    |
    | MeiliSearch is an open-source search engine that can be used as an
    | alternative to Algolia. Here you may configure your MeiliSearch
    | credentials and connection.
    |
    */

    'meilisearch' => [
        'host' => env('MEILISEARCH_HOST', 'http://127.0.0.1:7700'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Searchable Models
    |--------------------------------------------------------------------------
    |
    | The following array will be used to define which models should be
    | indexed. These models will automatically be included when performing
    | searches with Laravel Scout.
    |
    */

    'searchable' => [
        App\Models\Comment::class,
    ],

];
