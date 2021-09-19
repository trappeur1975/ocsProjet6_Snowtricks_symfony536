<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Picture;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $this->getDoctrine()->getManager()->flush();
            $em = $this->getDoctrine()->getManager();

            // -------------Picture management--------------- 

            // We recover the transmitted pictures
            $picture = ($form->get('newPictures')->getData());
            if (!empty($picture)) {
                //We generate a new picture file name
                $pictureFileName = uniqid() . '.' . $picture->guessExtension();

                // We copy the file to the picture folder
                $picture->move(
                    $this->getParameter('pictures_directory'),
                    $pictureFileName
                );

                // we physically delete the old picture of the user
                $oldPicture = $user->getPicture();
                $pictureFileNameOldPicture = $oldPicture->getPictureFileName();

                // dd($pictureFileNameOldPicture);
                // dd($pictureFileNameOldPicture !== "persona.png");

                if ($pictureFileNameOldPicture !== "persona.png") {
                    unlink($this->getParameter('pictures_directory') . '/' . $pictureFileNameOldPicture);
                    // dd("image supprimer");
                }

                // We create the new picture in the database
                $picture = new Picture();
                $picture->setPictureFileName($pictureFileName);
                $user->setPicture($picture);

                // we delete the old picture from the database
                $em->remove($oldPicture);
                // -------------we record in the database ---------------
                $em->flush();
            }
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
