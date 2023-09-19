<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pimcore\Model\DataObject; 
use Pimcore\Model\DataObject\Links\imageBrickLinks\Listing;

class ImageBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'ImageBrick';
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
        $links = DataObject::getByPath("/Links/ImageBrickLinks");
        $linksArray = [];
        foreach ($links->getChildren() as $document) {
            $linksArray[] = [
                $document->getLink(),
                $document->getKey()
            ];
        }
       
        $info->setParam('linksSelect', $linksArray);
        $info->setParam('links', $links);
        
        return null;
    }
    
}