<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use \Pimcore\Model\DataObject;
use App\Document\Areabrick\TimelineBrick;
use Pimcore\Model\DataObject\Timeline;

class WebAndMobileController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */

    public function defaultAction(Request $request): Response
    {
        $timeline = new DataObject\Timeline\Listing();
      
        $timelineOptions = $timeline->getClass()->getFieldDefinition("major")->getOptions();
        return $this->render('webAndMobile/webAndMobile.html.twig', [
            'timelineOptions' => $timelineOptions,
        ]);
    }

}
