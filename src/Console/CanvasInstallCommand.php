<?php

namespace Seongbae\Canvas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Artisan;

class CanvasInstallCommand extends Command
{
    protected $signature = 'canvas:install';
    protected $description = 'Installs Canvas.';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $this->info('Publishing files...');

        Artisan::call('vendor:publish', [
            '--tag' => 'canvas-install', '--force' => true
        ]);

        $this->info('Publish files complete.');

        $this->info('Running migrations...');

        Artisan::call('migrate');

        $this->info('Migrations complete.');

        $this->info('Seeding database...');

        Artisan::call('db:seed', ['--class'=>'CanvasSeeder']);

        $this->info('Seeding complete.');
    }

}
