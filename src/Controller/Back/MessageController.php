<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Message;

/**
 * Class MessageController
 * @package App\Controller\Back
 *
 * Contrôleur de la partie "Messages" du back-office
 */
class MessageController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche la liste des messages, divisée en deux catégories ( taités / non traités)
     */
    public function listAction(Request $request, $id){

        // Récupération des messages selon la catégorie
        $em = $this->getDoctrine()->getManager();
        $processed = $em->getRepository('App:Message')->findAllByProcessed(true);
        $notProcessed = $em->getRepository('App:Message')->findAllByProcessed(false);

        // Définition et récupération au besoin du message à lire
        $message = ($id) ? $em->getRepository('App:Message')->find($id) : null;

        // Affichage de la vue
        return $this->render('Back/Message/list.html.twig',
            array(
                'notProcessed' => $notProcessed,
                'processed' => $processed,
                'message' => $message
            ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affichage des cinq derniers des messages dans le dashboard
     */
    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('App:Message')->findLastUnprocessed(5);

        return $this->render('Back/Message/short-list.html.twig', array('messages' => $messages));
    }

    /**
     * @param Request $request
     * @param Message $message
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Supprime l'entité $message entrée en paramètre
     */
    public function deleteAction(Request $request, Message $message)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('cm_back_message_list');
    }

    /**
     * @param Message $message
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Change le statut de l'entité $message entrée en paramètre ( traité / non-traité)
     */
    public function toggleProcessedAction(message $message){
        $em = $this->getDoctrine()->getManager();
        $newStatus = $message->getProcessed() ? false : true;
        $message->setProcessed($newStatus);
        $em->flush();

        return $this->redirectToRoute('cm_back_message_list');

    }

}
