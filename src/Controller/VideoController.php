<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use App\Service\MediaManageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/video")
 */
class VideoController extends AbstractController
{
    /**
     * @Route("/", name="video_index", methods={"GET"})
     */
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render('video/index.html.twig', [
            'videos' => $videoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="video_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('video_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('video/new.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="video_show", methods={"GET"})
     */
    public function show(Video $video): Response
    {
        return $this->render('video/show.html.twig', [
            'video' => $video,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="video_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Video $video, MediaManageService $media): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($form->get('addVideo')->getData() !== null) {
                // We recover the transmitted video
                $addVideo = $form->get('addVideo')->getData();
                // we recover the identity of the youtube video (end of the string (youtube url))
                $videoFileName = $media->sourceVideo($addVideo);

                // We update the video in the database
                $video->setVideoFileName($videoFileName);

                $entityManager->persist($video);
                $entityManager->flush();

                // flash message for creating, editing or deleting a picture
                $this->addFlash('successVideoManagement', 'Félicitation votre video a bien été MODIFIE.');
            }

            return $this->redirectToRoute('trick_show', ['slug' => $video->getTrick()->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('video/edit.html.twig', [
            'video' => $video,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="video_delete", methods={"POST"})
     */
    public function delete(Request $request, Video $video): Response
    {
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();

            //deletion of the video (database) 
            $entityManager->remove($video);
            $entityManager->flush();
        }

        // flash message for creating, editing or deleting a video
        $this->addFlash('successVideoManagement', 'Félicitation votre video "' . $video->getVideoFileName() . '" a bien été SUPPRIME.');

        return $this->redirectToRoute('trick_show', ['slug' => $video->getTrick()->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
