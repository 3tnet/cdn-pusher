<?php

namespace Ty666\CdnPusher;

use Illuminate\Console\OutputStyle;
use Symfony\Component\Finder\Finder;
use Ty666\CdnPusher\Contracts\ExcludeAsset;
use Ty666\CdnPusher\Contracts\IncludeAsset;
use Illuminate\Support\Facades\Storage;

class Cdn
{
    protected $finder;

    public function __construct(Finder $finder, IncludeAsset $includeAsset, ExcludeAsset $excludeAsset)
    {
        $this->finder = $finder;
        $this->resolveInclude($includeAsset);
        $this->resolveExclude($excludeAsset);
    }

    protected function resolveInclude(IncludeAsset $includeAsset)
    {
        $this->finder->files()->in($includeAsset->getDirectories());

        foreach ($includeAsset->getExtensions() as $extension) {
            $this->finder->name("*.$extension");
        }
        foreach ($includeAsset->getPatterns() as $pattern) {
            $this->finder->name($pattern);
        }
    }

    protected function resolveExclude(ExcludeAsset $excludeAsset)
    {
        $this->finder->exclude($excludeAsset->getDirectories());
        foreach ($excludeAsset->getFiles() as $file) {
            $this->finder->notName($file);
        }
        foreach ($excludeAsset->getExtensions() as $extension) {
            $this->finder->notName("*.$extension");
        }
        foreach ($excludeAsset->getPatterns() as $pattern) {
            $this->finder->notName($pattern);
        }
        $this->finder->ignoreDotFiles($excludeAsset->needHidden());
    }

    public function pushCdn(OutputStyle $output = null)
    {
        $cdnFilesystem = Storage::cloud();
        if (!is_null($output)) {
            $bar = $output->createProgressBar($this->finder->count());
            $bar->setFormat('%current% [%bar%] %message%');
        }

        foreach ($this->finder as $assetFile) {
            if ($assetFile->isLink()) {
                continue;
            }
            if ($cdnFilesystem->exists($assetFile->getRelativePathname())) {
                $cdnFilesystem->update($assetFile->getRelativePathname(), file_get_contents($assetFile->getRealPath()));
            } else {
                $cdnFilesystem->put($assetFile->getRelativePathname(), file_get_contents($assetFile->getRealPath()));
            }

            if (!is_null($output)) {
                $bar->setMessage('已上传：' . $assetFile->getRealPath());
                $bar->advance();
            }
        }
        if (!is_null($output))
            $bar->finish();
    }

    public function clearCdn(OutputStyle $output = null)
    {
        $cdnFilesystem = $this->getCloudFilesystem();
        if (!is_null($output)) {
            $bar = $output->createProgressBar($this->finder->count());
            $bar->setFormat('%current% [%bar%] %message%');
        }

        foreach ($this->finder as $assetFile) {
            if ($cdnFilesystem->exists($assetFile->getRelativePathname())) {
                $cdnFilesystem->delete($assetFile->getRelativePathname());
            }
            if (!is_null($output)) {
                $bar->setMessage('已删除：' . $assetFile->getRealPath());
                $bar->advance();
            }
        }
        if (!is_null($output))
            $bar->finish();
    }

    protected function getCloudFilesystem()
    {
        return Storage::cloud();
    }

    public function getAssets()
    {
        return $this->finder;
    }
}
