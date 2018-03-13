<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Career;
use App\Form\CareerType;

class CareerController extends Controller
{

    public function listAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $corporate = $em->getRepository('App:Career')->findByCategory('Entreprise');
        $formations = $em->getRepository('App:Career')->findByCategory('Formation');

        $career = ($id) ? $em->getRepository('App:Career')->find($id) : new Career();
        $form = $this->createForm(CareerType::class, $career);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($career);
            $em->flush();

            $notice= ($id) ? "L'élément du parcours a été modifié" : "L'élément a été ajouté au parcours";
            $this->get('session')->getFlashbag()->add('notice', $notice );


            return $this->redirectToRoute('cm_back_career_list');
        }

        return $this->render('Back/Career/list.html.twig',
            array(
                'corporate' => $corporate,
                'formations' => $formations,
                'career' => $career,
                'form' => $form->createView()
            ));
    }

    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $corporate = $em->getRepository('App:Career')->findByCategory('Entreprise');
        $formation = $em->getRepository('App:Career')->findByCategory('Formation');

        return $this->render('Back/Career/short-list.html.twig',
            array(
                'corporate' => $corporate,
                'formation' => $formation
            ));
    }


    public function deleteAction(Request $request, Career $career)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($career);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "L'élement a été retiré du parcours");
        return $this->redirectToRoute('cm_back_career_list');
    }

    public function togglePublishedAction(Career $career){
        $em = $this->getDoctrine()->getManager();
        $newStatus = $career->getPublished() ? false : true;
        $career->setPublished($newStatus);
        $em->flush();

        $notice= $career->getPublished() ? "L'élément du parcours a été publié" : "L'élément du parcours a été dépublié";
        $this->get('session')->getFlashbag()->add('notice', $notice );
        return $this->redirectToRoute('cm_back_career_list');

    }

}