<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WebAndMobileController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('webAndMobile/webAndMobile.html.twig');
    }
}
