<?php return [
    'packages' => [
        'socialiteproviders/microsoft-azure' => [
            'config_namespace' => 'services.azure',

            'config' => [
                'client_id' => env('AZURE_AD_CLIENT_ID', ''),
                'client_secret' => env('AZURE_AD_CLIENT_SECRET', ''),
                'redirect' => url('/luketowers/azureadsso/login/microsoft/callback'),
                'tenant' => env('AZURE_TENANT_ID', 'consumers'),
                'cms_role_code' => false,
            ]
        ]
    ]
];
