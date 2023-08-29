<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Pimcore\Model\Document;

class VideoSliderBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'VideoSliderBrick';
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
        $linkFolder = Document::getByPath("/Links/AV2-Links");

        foreach ($linkFolder->getChildren() as $document) {
            $hrefArray[] = $document;
            $fileNames[] = $document->getKey();
        }
       
        $info->setParam('fileNames', $fileNames);
        $info->setParam('links', $hrefArray);

        return null;
    }
}