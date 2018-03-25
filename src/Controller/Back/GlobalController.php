<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class GlobalController
 * @package App\Controller\Back
 *
 * Contrôleur de la partie globale du back-office
 */
class GlobalController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche le header
     */
    public function headAction(){
        $unprocessedMessages = $this->getDoctrine()->getRepository('App:Message')->countUnprocessed();
        return $this->render('Back/Global/header.html.twig', array("unprocessedMessages" => $unprocessedMessages));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche le dashboard
     */
    public function indexAction(){
        return $this->render('Back/Dashboard/index.html.twig');
    }



}
