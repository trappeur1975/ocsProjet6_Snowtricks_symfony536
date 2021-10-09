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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
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
            $em = $this->getDoctrine()->getManager();

            // -------------Picture management--------------- 

            // We recover the transmitted pictures
            $picture = ($form->get('newPictures')->getData());

            if (!empty($picture)) {

                // -------------removing the old picture---------------
                // we get the name of the picture we want to replace
                $oldPicture = $user->getPicture();
                $pictureFileNameOldPicture = $oldPicture->getPictureFileName();

                //if the picture of the user is not the default picture (personna.png) we delete physically the picture of the user 
                if ($pictureFileNameOldPicture !== "persona.png") {
                    unlink($this->getParameter('pictures_directory_contributions') . '/' . $pictureFileNameOldPicture);
                }

                // we delete the file from the database 
                $em->remove($oldPicture);

                // -------------add new picture---------------
                // addition (physically) of an uploader picture on the server
                $newPicture = $media->addImageOnServer($picture);

                // we attribute to the user the picture upload 
                $user->setPicture($newPicture);

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

            // ---------------rajout tchenio nicolas-------------------------
            //physical deletion of pictures files of users' tricks on the server 
            $triks = $user->getTricks();

            foreach ($triks as $trick) {
                $pictures = $trick->getPictures();

                foreach ($pictures as $picture) {
                    // we delete the file physically
                    $pictureFileName = $picture->getPictureFileName();
                    unlink($this->getParameter('pictures_directory_contributions') . '/' . $pictureFileName);
                }
            }

            // physical deletion of the user's picture (his portrait) on the server if it is not the default image 
            $pictureUser = $user->getPicture();
            if ($pictureUser->getPictureFileName() !== $this->getParameter('pictureDefault')) {
                $pictureFileName = $pictureUser->getPictureFileName();
                unlink($this->getParameter('pictures_directory_contributions') . '/' . $pictureFileName);
            }

            // ---------------fin rajout tchenio nicolas-------------------------
            // $triks = $user->getTricks();

            // foreach ($triks as $trick) {
            //     $user->removeTrick($trick);
            // }

            $entityManager->remove($user);
            $entityManager->flush();

            // ---------------PROBLEME-------------------------
            // to manage the case where the user connects to the site deletes his user account => the user session will have to be "destroyed" to manage the redirection otherwise there will be an error 
            if ($user === $this->getUser()) {
                $session = new Session();
                $session->invalidate();
                // $session = $request->getSession();
                // $session->invalidate();
                return $this->redirectToRoute('home');
            }
            // ---------------FIN PROBLEME-------------------------
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        // return $this->redirectToRoute('app_logout');
        // return $this->redirectToRoute('home');
    }
}
