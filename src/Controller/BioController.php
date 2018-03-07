<?php

namespace App\Controller;
use App\Entity\Bio;
use App\Form\BioType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ImageUploader;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BioController extends Controller
{


    public function defaultAction(ImageUploader $imageUploader, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bio = $em->getRepository('App:Bio')->getBio();
        $bioPreview = clone $bio;
        $bio->setImage(
            new File($this->getParameter('images_directory').'/'.$bio->getImage())
        );
        $form = $this->createForm(BioType::class, $bio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $bio->getImage();
            $fileName = $imageUploader->upload($file);
            $bio->setImage($fileName);

            $em->flush();

            return $this->redirectToRoute('cm_back_bio');
        }

        return $this->render('Bio/default.html.twig', array('bio' => $bioPreview, 'form' => $form->createView()));

    }


    public function smallAction(ImageUploader $imageUploader, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bio = $em->getRepository('App:Bio')->getBio();
        return $this->render('Bio/small.html.twig', array('bio' => $bio));

    }
}
