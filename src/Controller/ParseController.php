<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ParseNews\RbcParse;

class ParseController extends AbstractController
{
    /**
     * @Route("/parse", name="parse")
     * @param RbcParse $parse
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(RbcParse $parse)
    {
        $parse->updateNews();
        exit();
    }

}
