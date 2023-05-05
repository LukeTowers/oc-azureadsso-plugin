<?php
if (! function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth()
    {
        return \Backend\Classes\AuthManager::instance();
    }
}
