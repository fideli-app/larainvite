<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Invitation Expiration Default
    |--------------------------------------------------------------------------
    |
    | Default Expiry time in Hours from current time.
    | i.e now() + expires (hours)
    |
    */
    'expires' => 48,

    /*
    |--------------------------------------------------------------------------
    | Table name
    |--------------------------------------------------------------------------
    */
    'table_name' => 'invites',

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    */
    'user_model' => 'App\User',

    /*
    |--------------------------------------------------------------------------
    | Invite Model
    |--------------------------------------------------------------------------
    */
    'invite_model' => 'Junaidnasir\Larainvite\Models\Invite'
];
