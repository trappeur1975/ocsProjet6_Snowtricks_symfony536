<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(TrickRepository $repo): Response
    // public function home(): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repo->findAll();

        return $this->render('front/index.html.twig', [
            'title' => 'bienvenue sur Snowtricks',
            'tricks' => $tricks
        ]);
    }

    /**
     * @Route("/trick/{id}", name="showTrick")
     */
    public function showTrick(Trick $trick): Response
    // public function showTrick($id): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Trick::class);
        // $trick = $repo->find($id);

        return $this->render('front/showTrick.html.twig', [
            'title' => 'bienvenue sur le trick',
            'trick' => $trick
            // 'pictures' => $trick->getPictures()
        ]);
    }
}
