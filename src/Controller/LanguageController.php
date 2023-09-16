<?php

namespace App\Controller;


use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;


class LanguageController extends FrontendController
{

    public function changeLanguage(Request $request, String $lang): Response
    {
        $this->document->setProperty("language", "language", $lang);
        $this->document->save();

        $onepagerRoute = $this->generateUrl('onepager');
        return $this->redirect($onepagerRoute);
    }
}
