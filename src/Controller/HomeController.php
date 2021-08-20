<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(TrickRepository $repo): Response
    // public function home(): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Trick::class);
        $tricks = $repo->findAll();

        return $this->render('home/index.html.twig', [
            'title' => 'bienvenue sur Snowtricks',
            'tricks' => $tricks
        ]);
    }
}
