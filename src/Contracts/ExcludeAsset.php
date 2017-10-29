<?php
namespace Ty666\CdnPusher\Contracts;

interface ExcludeAsset extends Asset {

    public function getFiles();

    public function needHidden();

}
