<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use \Pimcore\Model\DataObject;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class TeamListBrick extends AbstractTemplateAreabrick
{
    public function getName()
    {
        return 'TeamListBrick';
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
        $teamMembers = new DataObject\Team\Listing();
        $teamMembers->setOrderKey("lastname");
        $teamMembers->setOrder("asc");     
        
        $teamValues = $teamMembers->getClass()->getFieldDefinition("team")->getOptions();
        usort($teamValues, function ($a, $b) {
            return -strcmp($a['value'], $b['value']);
        });

        $info->setParam('teamValues', $teamValues);
        $info->setParam('teams', $teamMembers);
        return null;
    }
}