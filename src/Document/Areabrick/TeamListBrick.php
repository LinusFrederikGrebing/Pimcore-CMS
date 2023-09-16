<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use \Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class TeamListBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'TeamListBrick';
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
        // Initialize a listing of Team data objects
        $teamMembers = new DataObject\Team\Listing();
        
        // Set the order for sorting by "lastname" in ascending order
        $teamMembers->setOrderKey("lastname");
        $teamMembers->setOrder("asc");     

        // Get the options for the "team" field and sort them alphabetically
        $teamValues = $teamMembers->getClass()->getFieldDefinition("team")->getOptions();
        usort($teamValues, function ($a, $b) {
            return -strcmp($a['value'], $b['value']);
        });

        // Set the 'teamValues' and 'teams' parameters in the Info object for later use in the view
        $info->setParam('teamValues', $teamValues);
        $info->setParam('teams', $teamMembers);
        
        return null; // Return null as this action does not perform redirection
    }
}