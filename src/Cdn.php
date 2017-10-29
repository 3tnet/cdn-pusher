<?php

namespace Ty666\CdnPusher;

use Symfony\Component\Finder\Finder;
use Ty666\CdnPusher\Contracts\ExcludeAsset;
use Ty666\CdnPusher\Contracts\IncludeAsset;

class Cdn
{
    protected $finder;

    public function __construct(Finder $finder, IncludeAsset $includeAsset, ExcludeAsset $excludeAsset)
    {
        $this->finder = $finder;
    }

    protected function resolveInclude(IncludeAsset $includeAsset){
        $this->finder->files()->in($includeAsset->getDirectories());

        foreach ($includeAsset->getExtensions() as $extension) {
            $this->finder->name("*.$extension");
        }
        foreach ($includeAsset->getPatterns() as $pattern) {
            $this->finder->name($pattern);
        }
    }

    protected function resolveExclude(ExcludeAsset $excludeAsset){
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



    public function getAssets()
    {
        return $this->finder;
    }
}
