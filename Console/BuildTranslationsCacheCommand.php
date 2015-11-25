<?php namespace Modules\Translation\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Translation\Jobs\BuildTranslationsCache;

class BuildTranslationsCacheCommand extends Command
{
    use DispatchesJobs;

    protected $name = 'asgard:build:translations';
    protected $description = 'Build the translations cache based on file and database';

    public function fire()
    {
        $this->dispatch(new BuildTranslationsCache());
        $this->info('All translations were cached.');
    }
}
