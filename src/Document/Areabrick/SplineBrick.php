<?php
// src/Document/Areabrick/SpacerBrick.php

namespace App\Document\Areabrick;

use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
    
}
