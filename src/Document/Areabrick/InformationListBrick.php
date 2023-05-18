<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use \Pimcore\Model\DataObject;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class InformationListBrick extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'InformationListBrick';
    }

    public function getDescription()
    {
        return 'Embed contents from other URL (websites) via iframe';
    }

    public function getTemplateLocation()
    {
        return static::TEMPLATE_LOCATION_GLOBAL;
    }
    
    public function needsReload(): bool
    {
        // optional
        // here you can decide whether adding this bricks should trigger a reload
        // in the editing interface, this could be necessary in some cases. default=false
        return false;
    }
    public function action(Info $info)
    {
        $informations = new DataObject\Informations\Listing();
        $info->setParam('informations', $informations);
        return null;
    }

}