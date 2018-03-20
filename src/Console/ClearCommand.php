<?php

namespace Ty666\CdnPusher\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ty666\CdnPusher\Cdn;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdn:clear {--rule=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear all assets from CDN';


    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $rule = $this->option('rule');
        /**
         * @var Cdn $cdn
         */
        $cdn = app()->makeWith(Cdn::class, ['rule' => $rule]);
        $cdn->clearCdn($this->output);
    }
}
