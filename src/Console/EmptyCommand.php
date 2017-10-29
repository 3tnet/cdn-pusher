<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Ty666\CdnPusher\Cdn;

class EmptyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cdn:empty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Empty all assets from CDN';

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
        $cdnFilesystem = Storage::cloud();
        $assets = $this->cdn->getAssets();
        $bar = $this->output->createProgressBar(count($assets));
        foreach ($assets as $assetFile) {
            if ($cdnFilesystem->exists($assetFile->getRelativePathname())) {
                $cdnFilesystem->delete($assetFile->getRelativePathname());
            }
            $bar->advance();
            $this->info("已删除：" . $assetFile->getRealPath());

        }
        $bar->finish();
    }
}
