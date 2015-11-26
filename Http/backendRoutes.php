<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' =>'/translation'], function (Router $router) {
    $router->bind('translations', function ($id) {
        return app(\Modules\Translation\Repositories\TranslationRepository::class)->find($id);
    });
    get('translations', [
        'uses' => 'TranslationController@index',
        'as' => 'admin.translation.translation.index',
    ]);
});
