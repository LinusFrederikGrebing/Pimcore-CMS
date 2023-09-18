<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Pimcore\Model\DataObject;

class OnePagerController extends FrontendController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function defaultAction(Request $request): Response
    {
        $specializations = new DataObject\User\Listing();
        $specializationsOptions = $specializations->getClass()->getFieldDefinition("specialization")->getOptions();
        
        return $this->render('onepager/onepager.html.twig', [
            'specializationsOptions' => $specializationsOptions,
        ]);
    }
}
