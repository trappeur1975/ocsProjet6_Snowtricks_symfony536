<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\Picture;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function new(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // if ($form->get('pictures')->getData() !== null) {
            // We recover the transmitted picture 
            $newPictures = $form->get('newPictures')->getData();

            // On boucle sur les images
            foreach ($newPictures as $picture) {

                //We generate a new picture file name
                $pictureFileName = uniqid() . '.' . $picture->guessExtension();

                // We copy the file to the images folder
                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $pictureFileName
                );

                // We create the image in the database
                $picture = new Picture();
                $picture->setPictureFileName($pictureFileName);
                $trick->addPicture($picture);
            }
            // }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/new.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="trick_show", methods={"GET"})
     */
    public function show(Trick $trick): Response
    {
        return $this->render('trick/show.html.twig', [
            // 'title' => 'bienvenue sur le trick',
            'trick' => $trick,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="trick_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Trick $trick): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        // $form = $this->createForm(TrickType::class, $trick, ['trickId' => 'delete']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newPictures = $form->get('newPictures')->getData();

            // On boucle sur les images
            foreach ($newPictures as $picture) {

                //We generate a new picture file name
                $pictureFileName = uniqid() . '.' . $picture->guessExtension();

                // We copy the file to the images folder
                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $pictureFileName
                );

                // We create the image in the database
                $picture = new Picture();
                $picture->setPictureFileName($pictureFileName);
                $trick->addPicture($picture);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"POST"})
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }

        return $this->redirectToRoute('trick_index', [], Response::HTTP_SEE_OTHER);
    }

    // ----------------------- FUNCTION PERSO TCHENIO NICOLAS ------------------------
    /**
     * @Route("/delete/picture/{id}", name="trick_delete_picture", methods={"DELETE"})
     */
    public function deleteImage(Picture $picture, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //check if the token is valid
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $data['_token'])) {
            // we delete the file physically 
            $pictureFileName = $picture->getPictureFileName();
            unlink($this->getParameter('pictures_directory') . '/' . $pictureFileName);

            // we delete the file from the database 
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($picture);
            $entityManager->flush();

            // we answer in json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token invalid'], 400);
        }
    }
}
