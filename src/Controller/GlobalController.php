<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class GlobalController extends Controller
{


    public function menuAction(Request $request)
    {
        return $this->render('Global/header.html.twig');
    }

    public function indexAction()
    {
        return $this->render('Dashboard/index.html.twig');
    }



}
