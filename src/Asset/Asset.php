<?php


namespace Ty666\CdnPusher\Asset;

use Ty666\CdnPusher\Contracts\Asset as AssetContract;

abstract class Asset implements AssetContract
{
    private $directories = [];
    private $extensions = [];
    private $patterns = [];

    public function __construct($configurations)
    {
        $this->init($configurations);
    }

    public function init($configurations)
    {
        $this->directories = $configurations['directories'];
        $this->extensions = $configurations['extensions'];
        $this->patterns = $configurations['patterns'];
    }

    public function getDirectories()
    {
        return $this->directories;
    }

    public function getExtensions()
    {
        return $this->extensions;
    }

    public function getPatterns()
    {
        return $this->patterns;
    }
}
