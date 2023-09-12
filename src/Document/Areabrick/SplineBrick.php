<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use \Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\RedirectResponse;


class SplineBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'SplineBrick';
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
        $viewers = new DataObject\SplineViewer\Listing();
        $viewersArray = [];

        foreach ($viewers as $viewer) {
            // Assuming that $viewer is an instance of DataObject\SplineViewer
            $viewerName = $viewer->getName(); // Replace with the actual method to get the viewer's name
            $splinedata = $viewer->getSplinedata(); // Replace with the actual method to get the spline data
            
            // Create an array entry with the viewer name as the key and splineData as the value
            $viewersArray[] = [
                $splinedata,
                $viewerName
            ];
        }
        $info->setParam('viewer', $viewersArray);
        return null;
    }
}