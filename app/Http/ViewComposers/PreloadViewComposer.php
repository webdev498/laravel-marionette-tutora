<?php

namespace App\Http\ViewComposers;

use Route;
use Illuminate\Contracts\View\View;

class PreloadViewComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $route = Route::currentRouteName();

        return $view->with([
            'route' => $route,
            '__preload' => [
                'environment' => env('APP_ENV'),
                'route'       => $route,
            ],
        ]);
    }
}
