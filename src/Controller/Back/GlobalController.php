<?php

namespace App\Controller\Back;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;


class GlobalController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche le header du back-office
     */
    public function headAction(Request $request)
    {
        return $this->render('Back/Global/header.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche le dashboard.
     */
    public function indexAction()
    {
        return $this->render('Back/Dashboard/index.html.twig');
    }



}
