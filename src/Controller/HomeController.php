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
    {
        // $session = $request->getSession();
        // dd($session);

        // number of tricks per page 
        $numberTrickPage = 5;

        // page number for tricks (for the pagination)
        $pageTrick = (int) $request->query->get("pageTrick", 1);

        // the tricks of a page 
        $tricks = $trickRepository->paginatedTrick($pageTrick, $numberTrickPage);
        $numberTrickTotal = $trickRepository->countTrick();

        return $this->render('home/index.html.twig', [
            'title' => 'Snowtricks du snow et des tricks',
            'tricks' =>  $tricks,
            'numberTrickTotal' =>  $numberTrickTotal,
            'numberTrickPage' => $numberTrickPage,
            'pageTrick' => $pageTrick
        ]);
    }
}
