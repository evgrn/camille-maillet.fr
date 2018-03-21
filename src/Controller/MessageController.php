<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Message;
use App\Form\MessageType;

class MessageController extends Controller
{
    public function listAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $processed = $em->getRepository('App:Message')->findAllByProcessed(true);
        $notProcessed = $em->getRepository('App:Message')->findAllByProcessed(false);


        $message = ($id) ? $em->getRepository('App:Message')->find($id) : null;



        return $this->render('Back/Message/list.html.twig',
            array(
                'notProcessed' => $notProcessed,
                'processed' => $processed,
                'message' => $message
            ));
    }

    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('App:Message')->findLastUnprocessed(5);

        return $this->render('Back/Message/short-list.html.twig', array('messages' => $messages));
    }


    public function deleteAction(Request $request, Message $message)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute('cm_back_message_list');
    }

    public function toggleProcessedAction(message $message){
        $em = $this->getDoctrine()->getManager();
        $newStatus = $message->getProcessed() ? false : true;
        $message->setProcessed($newStatus);
        $em->flush();

        return $this->redirectToRoute('cm_back_message_list');

    }

}
