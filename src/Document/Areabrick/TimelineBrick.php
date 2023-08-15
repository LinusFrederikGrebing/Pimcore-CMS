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
        $param = $info->getEditable()->getConfig()['params'];
       
        $timeline = new DataObject\Timeline\Listing();
  
        $info->setParam('timelines', $timeline);

        return null;
    }
}