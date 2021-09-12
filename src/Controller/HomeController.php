<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(TrickRepository $trickRepository, Request $request): Response
    // public function home(): Response
    {
        // number of tricks per page 
        $numberTrickPage = 3;

        // page number for tricks 
        $pageTrick = (int) $request->query->get("pageTrick", 1);

        $tricks = $trickRepository->paginatedTrick($pageTrick, $numberTrickPage);
        $numberTrickTotal = $trickRepository->countTrick();

        return $this->render('home/index.html.twig', [
            'title' => 'bienvenue sur Snowtricks',
            'tricks' =>  $tricks,
            'numberTrickTotal' =>  $numberTrickTotal,
            'numberTrickPage' => $numberTrickPage,
            'pageTrick' => $pageTrick
        ]);
    }
}
