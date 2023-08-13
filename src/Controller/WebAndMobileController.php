<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use \Pimcore\Model\DataObject;
use App\Document\Areabrick\TimelineBrick;
class WebAndMobileController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */

    public function defaultAction(Request $request): Response
    {
        return $this->render('webAndMobile/webAndMobile.html.twig');
    }

}
