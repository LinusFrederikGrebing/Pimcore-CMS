<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Pimcore\Model\DataObject;
use App\Document\Areabrick\TimelineBrick;
use Pimcore\Model\DataObject\Timeline;

class MediaproductionController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function defaultAction(Request $request): Response
    {
        $timeline = new DataObject\Timeline\Listing();
      
        $timelineOptions = $timeline->getClass()->getFieldDefinition("major")->getOptions();
        
        return $this->render('mediaproduction/mediaproduction.html.twig', [
            'timelineOptions' => $timelineOptions,
        ]);
    }
}
