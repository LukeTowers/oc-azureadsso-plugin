<?php return [
    'packages' => [
        'metrogistics/laravel-azure-ad-oauth' => [
            'providers' => [
                '\Metrogistics\AzureSocialite\ServiceProvider'
            ],

            'aliases' => [
                'AzureUser' => '\Metrogistics\AzureSocialite\AzureUserFacade'
            ],

            'config_namespace' => 'azure-oath',

            'config' => [
                'routes' => [
                    // The middleware to wrap the auth routes in.
                    // Must contain session handling otherwise login will fail.
                    'middleware' => 'web',

                    // The url that will redirect to the SSO URL.
                    'login' => 'luketowers/azureadsso/login/microsoft',

                    // The app route that SSO will redirect to
                    // Make sure you update credentials.redirect as well
                    'callback' => 'luketowers/azureadsso/login/microsoft/callback',
                ],
                'credentials' => [
                    'client_id'     => env('AZURE_AD_CLIENT_ID', ''),
                    'client_secret' => env('AZURE_AD_CLIENT_SECRET', ''),
                    'redirect'      => Request::root().'/luketowers/azureadsso/login/microsoft/callback'
                ],

                // The route to redirect the user to upon login.
                'redirect_on_login' => Backend::url(),

                // The User Eloquent class.
                'user_class' => '\Backend\Models\User',

                // How much time should be left before the access
                // token expires to attempt a refresh.
                'refresh_token_within' => 30,

                // The users table database column to store the user SSO ID.
                'user_id_field' => 'azure_id',

                // How to map azure user fields to Laravel user fields.
                // Do not include the id field above.
                // AzureUserField => LaravelUserField
                'user_map' => [
                    'givenName'         => 'first_name',
                    'surname'           => 'last_name',
                    'email'             => 'email',
                    'userPrincipalName' => 'alt_email',
                ]
            ]
        ]
    ]
];