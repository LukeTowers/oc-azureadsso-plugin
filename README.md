# About

Adds support for logging into the backend with Azure Active Directory Single Sign On (SSO) OAuth.

# Installation

To install from the [Marketplace](https://octobercms.com/plugin/luketowers-azureadsso), click on the "Add to Project" button and then select the project you wish to add it to before updating the project to pull in the plugin.

To install from the backend, go to **Settings -> Updates & Plugins -> Install Plugins** and then search for `LukeTowers.AzureADSSO`.

To install from [the repository](https://github.com/luketowers/oc-azureadsso-plugin), clone it into **plugins/luketowers/azureadsso** and then run `composer update` from your project root in order to pull in the dependencies.

To install it with Composer, run `composer require luketowers/oc-azureadsso-plugin` from your project root.

# Setup

1. Go to [`Azure Active Directory` -> `App registrations`](https://aad.portal.azure.com/#blade/Microsoft_AAD_IAM/ActiveDirectoryMenuBlade/RegisteredAppsPreview)
2. Create a new application (registration)
3. Choose a name (Example: "My OctoberCMS Application Sign-in Helper")
4. If asked, select the "Web app / API" Application Type
5. Provide the Redirect URI (by default will be `https://example.com/luketowers/azureadsso/login/microsoft/callback`, replace `https://example.com` with the URL to your OctoberCMS instance)
6. Click Register
7. Select your newly created application
8. Copy the "Application (client) ID" value and put it into your `.env` file for the `AZURE_AD_CLIENT_ID` env variable
9. Select the permissions required for your app in the "API Permissions" tab (recommended at least Microsft Graph -> `User.Read`, `email`, & `profile`)
10. Go to the Certificates & Secrets tab and create a new Client Secret (recommended to set it to "Never" expire). Copy this value down and use it for the `AZURE_AD_CLIENT_SECRET` env variable in your `.env` file.