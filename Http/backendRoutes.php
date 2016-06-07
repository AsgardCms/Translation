<?php

use Illuminate\Routing\Router;

/** @var Router $router */

$router->group(['prefix' =>'/translation'], function (Router $router) {
    $router->bind('translations', function ($id) {
        return \Modules\Translation\Entities\TranslationTranslation::find($id);
    });
    $router->get('translations', ['uses' => 'TranslationController@index', 'as' => 'admin.translation.translation.index', ]);
    $router->get('translations/update/{translations}', ['uses' => 'TranslationController@update', 'as' => 'admin.translation.translation.update', ]);
    $router->get('translations/export', ['uses' => 'TranslationController@export', 'as' => 'admin.translation.translation.export', ]);
    $router->post('translations/import', ['uses' => 'TranslationController@import', 'as' => 'admin.translation.translation.import', ]);
});
