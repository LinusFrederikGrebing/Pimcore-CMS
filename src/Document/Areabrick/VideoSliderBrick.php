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
        // Get the folder containing links (adjust the path as needed)
        $linkFolder = Document::getByPath("/Links/AV2-Links");

        // Initialize arrays to store links and filenames
        $hrefArray = [];
        $fileNames = [];

        // Loop through child documents in the folder
        foreach ($linkFolder->getChildren() as $document) {
            $hrefArray[] = $document; // Store the document
            $fileNames[] = $document->getKey(); // Store the document's key as the filename
        }

        // Set the 'fileNames' and 'links' parameters in the Info object for later use in the view
        $info->setParam('fileNames', $fileNames);
        $info->setParam('links', $hrefArray);

        return null; // Return null as this action does not perform redirection
    }
}