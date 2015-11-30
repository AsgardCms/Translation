<?php

use Illuminate\Routing\Router;

/** @var $router Router */
post('translation/update', ['uses' => 'TranslationController@update', 'as' => 'api.translation.translations.update', ]);
post('translation/clearCache', ['uses' => 'TranslationController@clearCache', 'as' => 'api.translation.translations.clearCache']);
post('translation/revisions', ['uses' => 'TranslationController@revisions', 'as' => 'api.translation.translations.revisions']);
