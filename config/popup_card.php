<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Default Popup Card Settings
    |--------------------------------------------------------------------------
    |
    | These options configure the popup card behavior.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | General Settings
    |--------------------------------------------------------------------------
    */

    // Whether the popup modal is enabled.
    'enabled' => true,

    // Modal size (1/4, 1/3, 1/2, 2/3, 3/4, full)
    'card_width' => '1/2',


    // Enable or disable showing the users in the PopupCards Resource
    'show_seen_by_users'=>false,

    // Enable or disable showing the user count in the PopupCards Resource
    'show_users_count'=>true,


    /*
    |--------------------------------------------------------------------------
    | Database Table Settings
    |--------------------------------------------------------------------------
    */

    // The name of the main table for popup cards
    'table_name' => 'popup_cards',

    /*
    |--------------------------------------------------------------------------
    | User Model and Relationship Settings
    |--------------------------------------------------------------------------
    */



    // The model class to use for users
    'user_model' => 'App\Models\User',

    // The name of the pivot table between users and popup cards
    'pivot_table' => 'cards_users',

    // The foreign key for the user in the pivot table
    'user_foreign_key' => 'user_id',

    // The foreign key for the popup card in the pivot table
    'popup_card_foreign_key' => 'popup_card_id',


    'user_nova_resource'=> 'App\Nova\User',


];
