<?php

namespace App\Controller;

use App\Entity\Picture;
use App\Form\PictureType;
use App\Service\MediaManageService;
use App\Repository\PictureRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/picture")
 */
class PictureController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="picture_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Picture $picture, MediaManageService $media): Response
    {
        $form = $this->createForm(PictureType::class, $picture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //we recover the initial FileName picture of the bite that we want to replace 
            $oldpictureFileName = $picture->getPictureFileName();

            if (!empty($form->get('addPicture')->getData())) {
                $addPicture = $form->get('addPicture')->getData();


                // addition (physically) of an uploader image on the server
                $newPicture = $media->addImageOnServer($addPicture);

                // we make the following changes
                //we modify in database the old picture with the data of the new picture
                $picture->setPictureFileName($newPicture->getPictureFileName());
                $picture->setAlt($newPicture->getAlt());

                //we physically delete the old picture file on the server
                unlink($this->getParameter('pictures_directory_contributions') . '/' . $oldpictureFileName);
            }
            $em->flush();

            // flash message for creating, editing or deleting a picture
            $this->addFlash('successPictureManagement', 'Félicitation votre image a bien été MODIFIE.');

            return $this->redirectToRoute('trick_show', ['slug' => $picture->getTrick()->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('picture/edit.html.twig', [
            'picture' => $picture,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="picture_delete", methods={"POST"})
     */
    public function delete(Request $request, Picture $picture, MediaManageService $media): Response
    {
        if ($this->isCsrfTokenValid('delete' . $picture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            //deletion of the picture (database, server) 
            $media->deleteImageOnServer($picture, $entityManager);
        }

        // flash message for creating, editing or deleting a picture
        $this->addFlash('successPictureManagement', 'Félicitation votre image a bien été SUPPRIME.');

        return $this->redirectToRoute('trick_show', ['slug' => $picture->getTrick()->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
