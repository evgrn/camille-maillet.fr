<?php

namespace App\Controller\Back;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class GlobalController extends Controller
{


    public function headAction(Request $request)
    {
        return $this->render('Back/Global/header.html.twig');
    }

    public function indexAction()
    {
        return $this->render('Back/Dashboard/index.html.twig');
    }



}
