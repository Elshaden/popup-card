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

    /*
    |--------------------------------------------------------------------------
    | Appearance Settings
    |--------------------------------------------------------------------------
    */

    // Default title for popup cards (used if title is not provided)
    'default_title' => 'Notification',

    // Default body for popup cards (used if body is not provided)
    'default_body' => 'This is a notification from the system.',

    // Text for the close button
    'close_button_text' => 'Close',

    // Text for the "do not show again" button
    'do_not_show_again_text' => 'Do Not Show Again',
];
