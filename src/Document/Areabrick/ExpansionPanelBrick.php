<?php
// src/Document/Areabrick/Iframe.php

namespace App\Document\Areabrick;
use Pimcore\Model\Document\Editable\Area\Info;
use \Pimcore\Model\DataObject;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Pimcore\Extension\Document\Areabrick\AbstractTemplateAreabrick;

class ExpansionPanelBrick extends AbstractTemplateAreabrick
{
    public function getName(): string
    {
        return 'ExpansionPanelBrick';
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
        $pdfassets = \Pimcore\Model\Asset::getByPath("/Mediengestaltung");
        foreach ($pdfassets->getChildren() as $document) {
            $hrefArray[] = $document; 
            $filename = $document->getFilename();
            $filenameWithoutExtension = str_replace('.pdf', '', $filename);
        
            $fileNames[] = $filenameWithoutExtension;
        }
        $info->setParam('filenames', $fileNames);
        $info->setParam('pdfassets', $hrefArray);
        return null;
    }
}