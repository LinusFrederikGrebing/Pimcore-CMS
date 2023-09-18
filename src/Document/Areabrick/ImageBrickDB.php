<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ImageBrickDB extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'ImageBrickDB';
    }
    public function getDescription(): string
    {
        return 'Embed contents from other URL (websites) via iframe';
    }

    public function getTemplateLocation(): string
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
    public function action(Info $info): ?RedirectResponse
    {   
        // Get the root folder containing image assets
        $imageAssetsFolder  = \Pimcore\Model\Asset::getByPath("/Datenbanken");
        
        // Loop through child assets in the folder
        foreach ($imageAssetsFolder->getChildren() as $document) {
            $images[] = $document; 
        }
        // Set parameters in the Info object for later use in the view
        $info->setParam('images', $images);
    
        
        return null;
    }
}