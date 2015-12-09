<?php

use Modules\Core\Composers\CurrentUserViewComposer;

view()->composer('translation::admin.translations.index', CurrentUserViewComposer::class);
