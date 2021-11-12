<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(TrickRepository $trickRepository, Request $request): Response
    {
        // ---------------for the pagination------------
        // number of tricks per page 
        // $numberTrickPage = 5;

        // page number for tricks (for the pagination)
        // $pageTrick = (int) $request->query->get("pageTrick", 1);

        // the tricks of a page 
        // $tricks = $trickRepository->paginatedTrick($pageTrick, $numberTrickPage);
        // $numberTrickTotal = $trickRepository->countTrick();
        // ---------------end for the pagination------------

        // FOR THE LOADER
        $tricks = $trickRepository->findby([], ['id' => 'ASC'], 3, 0);

        return $this->render('home/index.html.twig', [
            'title' => 'Snowtricks du snow et des tricks',
            'tricks' =>  $tricks,
            // ---------------for the pagination------------
            // 'numberTrickTotal' =>  $numberTrickTotal,
            // 'numberTrickPage' => $numberTrickPage,
            // 'pageTrick' => $pageTrick
        ]);
    }

    /**
     * @Route("/loadTricks/{offset}", name="loadTricks", methods={"GET"})
     */
    public function loadTricks($offset = 3, TrickRepository $trickRepository, SerializerInterface $serializer)
    {
        $tricks = $trickRepository->findby([], ['id' => 'ASC'], 3, $offset);

        return $this->render('home/cardTrick.html.twig', [
            'tricks' =>  $tricks,
        ]);
    }
}
