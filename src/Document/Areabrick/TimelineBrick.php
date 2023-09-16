<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use \Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class TimelineBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'TimelineBrick';
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
        // Get the first parameter from the Area Brick configuration
        $param = $info->getEditable()->getConfig()['params'][0];
        
        // Initialize a listing of Timeline data objects
        $timeline = new DataObject\Timeline\Listing();
        
        // Initialize an array to store filtered Timeline items
        $filteredTimelines = [];

        // Loop through the Timeline items and filter based on the 'major' property
        foreach ($timeline as $item) {
            if ($item->getMajor() === $param) {
                $filteredTimelines[] = $item;
            }
        }

        // Set the 'timelines' parameter in the Info object for later use in the view
        $info->setParam('timelines', $filteredTimelines);

        return null; // Return null as this action does not perform redirection
    }
}