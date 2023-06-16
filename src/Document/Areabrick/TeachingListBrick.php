<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use \Pimcore\Model\DataObject;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class TeachingListBrick extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'TeachingListBrick';
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

   /*public function action(Info $info)
    {
        $teachings = new DataObject\Teaching\Listing();
        $info->setParam('teachings', $teachings);
        return null;
    }
       */
    public function action(Info $info)
    {
        $teachings = new DataObject\Teaching\Listing();

        // Filtern der DataObjects nach Typ
        $teachingsAV = $teachings->find([
            'Select' => 'AV'
        ]);
        
        $teachingsWebMobile = $teachings->find([
            'Select' => 'Web'
        ]);
        
        $teachingsProduktion = $teachings->find([
            'Select' => 'Medien'
        ]);
        dd($teachingsAV);
        // Übergabe der separaten DataObjects an die View
        $info->setParam('teachingsAV', $teachingsAV);
        $info->setParam('teachingsWebMobile', $teachingsWebMobile);
        $info->setParam('teachingsProduktion', $teachingsProduktion);
        
        return null;
    }
}