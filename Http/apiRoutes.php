<?php

use Illuminate\Routing\Router;

/** @var $router Router */
$router->any('translation/update', [
    'uses' => 'TranslationController@update',
    'as' => 'api.translation.translations.update'
]);
