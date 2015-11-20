<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' =>'/translation'], function (Router $router) {
        $router->bind('translations', function ($id) {
            return app('Modules\Translation\Repositories\TranslationRepository')->find($id);
        });
        $router->resource('translations', 'TranslationController', ['except' => ['show'], 'names' => [
            'index' => 'admin.translation.translation.index',
            'create' => 'admin.translation.translation.create',
            'store' => 'admin.translation.translation.store',
            'edit' => 'admin.translation.translation.edit',
            'update' => 'admin.translation.translation.update',
            'destroy' => 'admin.translation.translation.destroy',
        ]]);
// append

});
