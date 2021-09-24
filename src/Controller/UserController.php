<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Picture;
use App\Repository\UserRepository;
use App\Service\MediaManageService;
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
    public function edit(Request $request, User $user, MediaManageService $media): Response
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
                // addition (physically) of an uploader picture on the server
                $pictureFileName = $media->addImageOnServer($picture);

                // we get the name of the picture we want to replace
                $oldPicture = $user->getPicture();
                $pictureFileNameOldPicture = $oldPicture->getPictureFileName();

                //if the picture of the user is not the default picture (personna.png) we delete physically the picture of the user 
                if ($pictureFileNameOldPicture !== "persona.png") {
                    unlink($this->getParameter('pictures_directory') . '/' . $pictureFileNameOldPicture);
                }

                //otherwise we modify the picture (its $pictureFileName) in database
                $oldPicture->setPictureFileName($pictureFileName);

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
