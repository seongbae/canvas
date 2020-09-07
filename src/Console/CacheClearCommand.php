<?php

namespace Seongbae\Canvas\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CacheClearCommand extends Command
{
    protected $signature = 'canvas:clear';
    protected $description = 'Flushes entire cache';
    
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Cache::flush();
        echo "Cache flushed.\n";
    }

}
