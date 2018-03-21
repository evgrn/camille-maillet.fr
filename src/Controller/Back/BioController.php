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

    /**
     * @param ImageManager $imageManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche l'interface d'édition de la biographie avec un aperçu et un formulaire d'édition.
     */
    public function defaultAction(ImageManager $imageManager, Request $request)
    {
        // Récupération des entités
        $em = $this->getDoctrine()->getManager();
        $bio = $em->getRepository('App:Bio')->getBio();

        // Afin de ne pas avoir de problème avec le champ image du formulaire, on clone l'objet $bio.
        // Son clone $bioPreview servira à hydrater la partie aperçue
        // L'objet $bio initial, qui servira à hydrater le formulaire, se voit remplacer sa propriété $image par un objet de type File attendu par le formulaire.
        $bioPreview = clone $bio;
        $originalImage = $bio->getImage();
        $bio->setImage(new File($this->getParameter('images_directory').'/'. $originalImage));

        // Gestion du formulaire
        $form = $this->createForm(BioType::class, $bio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Mise à jour de l'image
            $bio->setImage(
                $imageManager->manageImageUpdate($bio->getImage(), $originalImage)
            );
            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', 'La bio a été mise à jour');
            return $this->redirectToRoute('cm_back_bio');
        }

        // Génération de la vue
        return $this->render('Back/Bio/default.html.twig', array('bio' => $bioPreview, 'form' => $form->createView()));

    }

    /**
     * @param ImageManager $imageManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affiche l'aperçu de la bio dans le dashboard.
     */
    public function smallAction(ImageManager $imageManager, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $bio = $em->getRepository('App:Bio')->getBio();
        return $this->render('Back/Bio/small.html.twig', array('bio' => $bio));

    }

}
