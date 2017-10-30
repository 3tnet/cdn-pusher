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
    protected $signature = 'cdn:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear all assets from CDN';

    protected $cdn;

    /**
     * Create a new command instance.
     * @param Cdn $cdn
     * @return void
     */
    public function __construct(Cdn $cdn)
    {
        $this->cdn = $cdn;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->clearCdn($this->output);
    }
}
