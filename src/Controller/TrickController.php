<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Trick;
use App\Entity\Video;
use App\Entity\Message;
use App\Entity\Picture;
use App\Form\TrickType;
use App\Form\MessageType;
use App\Repository\TrickRepository;
use App\Repository\PictureRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\MediaManageService;

/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/", name="trick_index", methods={"GET"})
     */
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('trick/index.html.twig', [
            'tricks' => $trickRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     */
    public function new(Request $request, MediaManageService $media): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // -------------Picture management--------------- 
            // we define the author of the trick by the user to connect (because we do not define it in a field via the form) (see _form.html.twig) 
            $trick->setUser($this->getUser());

            // We recover the transmitted pictures 
            $newPictures = $form->get('newPictures')->getData();

            // We loop on the pictures 
            foreach ($newPictures as $picture) {

                // addition (physically) of an uploader image on the server
                $newPicture = $media->addImageOnServer($picture);

                // We create the picture in the database
                $trick->addPicture($newPicture);
            }

            // -------------Video management--------------- 
            if ($form->get('newVideo')->getData() !== null) {   //A PRIORI A SUPPRIMER SI ON FERA UNE BOUCLE FOR (COMME AVEC LES PICTURES)
                // We recover the transmitted video
                $newVideo = $form->get('newVideo')->getData();
                // we recover the identity of the youtube video (end of the string (youtube url))  
                $videoFileName = $media->sourceVideo($newVideo);

                // We create the video in the database
                $video = new Video();
                $video->setVideoFileName($videoFileName);
                $trick->addVideo($video);
            }

            // -------------we record in the database --------------- 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            // flash message for creating, editing or deleting a trick
            $this->addFlash('successTrickManagement', 'F??licitation votre trick "' . $trick->getName() . '" a bien ??t?? CREE.');

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form
        ]);
    }

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET","POST"})
     */
    public function show(Trick $trick, Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        // page number for messages (for the pagination)
        $pageMessage = (int) $request->query->get("pageMessage", 1);

        // message form
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setTrick($trick)
                ->setUser($this->getUser());

            $entityManager->persist($message);

            $entityManager->flush();

            return $this->redirectToRoute('trick_show', ['slug' => $trick->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/show.html.twig', [
            'trick' => $trick,
            'pageMessage' => $pageMessage,
            'form' => $form
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick, MediaManageService $media): Response
    {
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $trick->setUpdateAt(new \Datetime());

            // -------------Picture management--------------- 
            // We recover the transmitted pictures 
            $newPictures = $form->get('newPictures')->getData();

            // We loop on the pictures
            foreach ($newPictures as $picture) {
                // addition (physically) of an uploader image on the server
                $newPicture = $media->addImageOnServer($picture);

                // We create the image in the database
                $trick->addPicture($newPicture);
            }

            //deleting selected pictures via checkbox
            $oldPictures = $form->get('pictures')->getData();

            foreach ($oldPictures as $oldPicture) {
                // we delete the file physically 
                $pictureFileName = $oldPicture->getPictureFileName();
                unlink($this->getParameter('pictures_directory_contributions') . '/' . $pictureFileName);

                // we delete the file from the database 
                $em->remove($oldPicture);
            }

            // -------------Video management--------------- 
            if ($form->get('newVideo')->getData() !== null) {   //A PRIORI A SUPPRIMER SI ON FERA UNE BOUCLE FOR (COMME AVEC LES PICTURES)
                // We recover the transmitted video
                $newVideo = $form->get('newVideo')->getData();

                // we recover the identity of the youtube video (end of the string (youtube url))
                $videoFileName = $media->sourceVideo($newVideo);

                // We create the video in the database
                $video = new Video();
                $video->setVideoFileName($videoFileName);
                $trick->addVideo($video);
            }

            //deleting selected videos via checkbox
            $oldVideos = $form->get('videos')->getData();

            // foreach ($form->get('pictures')->getData() as $oldpicture) {
            foreach ($oldVideos as $oldVideo) {
                // we delete the file from the database 
                $em->remove($oldVideo);
            }

            // -------------we record in the database --------------- 
            $em->flush();

            // flash message for creating, editing or deleting a trick
            $this->addFlash('successTrickManagement', 'F??licitation votre trick "' . $trick->getName() . '" a bien ??t?? MODIFIE.');

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="trick_delete", methods={"POST"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            //deleting pictures
            $pictures = $trick->getPictures();

            foreach ($pictures as $picture) {
                // we delete the file physically 
                $pictureFileName = $picture->getPictureFileName();
                unlink($this->getParameter('pictures_directory_contributions') . '/' . $pictureFileName);
            }

            $entityManager->remove($trick);
            $entityManager->flush();

            // flash message for creating, editing or deleting a trick
            $this->addFlash('successTrickManagement', 'F??licitation votre trick "' . $trick->getName() . '" a bien ??t?? SUPPRIME.');
        }

        return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
    }
}
