<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Irl;
use App\Form\IrlType;
use App\Form\IrlEditType;
use App\Service\ImageManager;
use Symfony\Component\HttpFoundation\File\File;

class IrlController extends Controller
{

    public function listAction(){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->findAll();

        return $this->render('Back/Irl/list.html.twig', array('irl' => $irl));
    }

    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->findAll();

        return $this->render('Back/Irl/short-list.html.twig', array('irl' => $irl));
    }

    public function addAction(Request $request, ImageManager $imageManager)
    {
        $em = $this->getDoctrine()->getManager();
        $irl = new Irl();
        $form = $this->createForm(IrlType::class, $irl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $irl->setImage(
                $imageManager->uploadImage($irl->getImage())
            );
            $em->persist($irl);
            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "L'élément IRL a été ajouté" );
            return $this->redirectToRoute('cm_back_irl_list');
        }

        return $this->render('Back/Irl/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function singleAction(Request $request, ImageManager $imageManager, Irl $irl){
        $em = $this->getDoctrine()->getManager();
        $irlPreview = clone $irl;

        $originalImage = $irl->getImage();
        $irl->setImage(new File($this->getParameter('images_directory').'/'.$originalImage));

        $form = $this->createForm(IrlEditType::class, $irl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $irl->setImage(
                $imageManager->manageImageUpdate($irl->getImage(), $originalImage)
            );
            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "L'élément IRL a été modifié" );
            return $this->redirectToRoute('cm_back_irl_list');
        }

        return $this->render('Back/Irl/single.html.twig', array('irl' => $irlPreview, 'form' => $form->createView()));

    }

    public function deleteAction(Request $request, ImageManager $imageManager, Irl $irl)
    {
        $em = $this->getDoctrine()->getManager();

        $imageManager->delete($irl->getImage());
        $em->remove($irl);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "L'élément IRL a été supprimé" );

        return $this->redirectToRoute('cm_back_irl_list');
    }

    public function togglePublishedAction(Irl $irl){
        $em = $this->getDoctrine()->getManager();
        $newStatus = $irl->getPublished() ? false : true;
        $irl->setPublished($newStatus);
        $em->flush();
        $notice= $career->getPublished() ? "L'élément IRL a été publié" : "L'élément IRL a été dépublié";
        $this->get('session')->getFlashbag()->add('notice', $notice );

        return $this->redirectToRoute('cm_back_irl_list');

    }

}