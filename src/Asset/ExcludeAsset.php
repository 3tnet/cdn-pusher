<?php
namespace Ty666\CdnPusher\Asset;
use Ty666\CdnPusher\Contracts\ExcludeAsset as ExcludeAssetContract;

class ExcludeAsset extends Asset implements  ExcludeAssetContract{

    private $files;
    private $hidden;
    public function init($configurations)
    {
        parent::init($configurations);
        $this->files = $configurations['files'];
        $this->hidden = $configurations['hidden'];
    }

    public function getFiles()
    {
        return $this->files;
    }

    public function needHidden()
    {
        return $this->hidden;
    }
}
