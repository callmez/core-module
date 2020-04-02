<?php

if (! function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (in_admin()) {
            if (auth()->check()) {
                return 'admin.dashboard';
            }

            return 'admin.auth.login';
        }

        if (auth()->check()) {
            return 'frontend.user.dashboard';
        }

        return 'frontend.index';
    }
}

if (! function_exists('in_admin')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function in_admin()
    {
        return auth()->getDefaultDriver() == 'admin';
    }
}

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}
