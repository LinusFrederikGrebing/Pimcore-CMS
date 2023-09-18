<?php

namespace App\Controller;


use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class LanguageController extends FrontendController
{

    public function changeLanguage(Request $request, String $lang): Response
    {
        $this->document->setProperty("language", "language", $lang);
        $this->document->save();

        return $this->redirect("/$lang");
    }
}
