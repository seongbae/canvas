<?php

namespace Seongbae\Canvas\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

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
        echo "hello world\n";
    }

}
