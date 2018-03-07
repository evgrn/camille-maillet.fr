<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Irl;
use App\Form\IrlType;
use App\Form\IrlEditType;
use App\Service\ImageUploader;
use Symfony\Component\HttpFoundation\File\File;

class IrlController extends Controller
{
    /**
     * @Route("/irl/add", name="cm_back_irl_add")
     */
    public function addAction(Request $request, ImageUploader $imageUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $irl = new Irl();
        $form = $this->createForm(IrlType::class, $irl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $irl->getImage();
            $fileName = $imageUploader->upload($file);
            $irl->setImage($fileName);

            $em->persist($irl);
            $em->flush();

            return $this->redirectToRoute('cm_back_irl_single', array('id' => $irl->getId()));
        }

        return $this->render('Irl/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listAction(){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->findAll();

        return $this->render('Irl/list.html.twig', array('irl' => $irl));
    }

    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->findAll();

        return $this->render('Irl/short-list.html.twig', array('irl' => $irl));
    }

    public function singleAction(Request $request,  ImageUploader $imageUploader, $id){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->find($id);
        $irlPreview = clone $irl;
        $irl->setImage(
            new File($this->getParameter('images_directory').'/'.$irl->getImage())
        );
        $form = $this->createForm(IrlEditType::class, $irl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $irl->getImage();
            $fileName = $imageUploader->upload($file);
            $irl->setImage($fileName);

            $em->flush();

            return $this->redirectToRoute('cm_back_irl_single', array('id' => $irl->getId()));
        }

        return $this->render('Irl/single.html.twig', array('irl' => $irlPreview, 'form' => $form->createView()));

    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $irl = $em->getRepository('App:Irl')->find($id);
        $em->remove($irl);
        $em->flush();


        return $this->redirectToRoute('cm_back_irl_list');
    }

    public function togglePublishedAction($id){
        $em = $this->getDoctrine()->getManager();
        $irl = $em->getRepository('App:Irl')->find($id);
        $newStatus = $irl->getPublished() ? false : true;
        $irl->setPublished($newStatus);
        $em->flush();

        return $this->redirectToRoute('cm_back_irl_list');

    }

}