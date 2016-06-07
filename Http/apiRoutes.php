<?php

use Illuminate\Routing\Router;

/** @var $router Router */

$router->post('translation/update', ['uses' => 'TranslationController@update', 'as' => 'api.translation.translations.update', ]);
$router->post('translation/clearCache', ['uses' => 'TranslationController@clearCache', 'as' => 'api.translation.translations.clearCache']);
$router->post('translation/revisions', ['uses' => 'TranslationController@revisions', 'as' => 'api.translation.translations.revisions']);
