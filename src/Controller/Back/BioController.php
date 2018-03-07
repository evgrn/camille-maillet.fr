<?php

namespace App\Controller\Back;
use App\Entity\Bio;
use App\Form\BioType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ImageManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class BioController extends Controller
{

    public function defaultAction(ImageManager $imageManager, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bio = $em->getRepository('App:Bio')->getBio();

        $originalImage = $bio->getImage();
        $bioPreview = clone $bio;

        $bio->setImage(new File($this->getParameter('images_directory').'/'. $originalImage));
        $form = $this->createForm(BioType::class, $bio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bio->setImage(
                $imageManager->manageImageUpdate($bio->getImage(), $originalImage)
            );
            $em->flush();
            return $this->redirectToRoute('cm_back_bio');
        }

        return $this->render('Back/Bio/default.html.twig', array('bio' => $bioPreview, 'form' => $form->createView()));

    }

    public function smallAction(ImageManager $imageManager, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bio = $em->getRepository('App:Bio')->getBio();
        return $this->render('Back/Bio/small.html.twig', array('bio' => $bio));

    }

}
