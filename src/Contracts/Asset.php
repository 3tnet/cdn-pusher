<?php
namespace Ty666\CdnPusher\Contracts;

interface Asset
{
    public function init($configurations);

    public function getDirectories();

    public function getExtensions();

    public function getPatterns();
}
