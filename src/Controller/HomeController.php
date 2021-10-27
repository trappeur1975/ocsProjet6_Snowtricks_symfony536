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
        // $session = $request->getSession();
        // dd($session);

        // number of tricks per page 
        // $numberTrickPage = 5;

        // page number for tricks (for the pagination)
        // $pageTrick = (int) $request->query->get("pageTrick", 1);

        // the tricks of a page 
        // $tricks = $trickRepository->paginatedTrick($pageTrick, $numberTrickPage);
        // $numberTrickTotal = $trickRepository->countTrick();

        // LIGNE INTEGRER AVEC LA CREATION DU LOADER
        $tricks = $trickRepository->findby([], ['id' => 'ASC'], 3, 0);

        return $this->render('home/index.html.twig', [
            'title' => 'Snowtricks du snow et des tricks',
            'tricks' =>  $tricks,
            // 'numberTrickTotal' =>  $numberTrickTotal,
            // 'numberTrickPage' => $numberTrickPage,
            // 'pageTrick' => $pageTrick
        ]);
    }

    /**
     * @Route("/loadTricks/{offset}", name="loadTricks", methods={"GET","POST"})
     */
    public function loadTricks($offset = 3, TrickRepository $trickRepository, SerializerInterface $serializer)
    {
        $tricks = $trickRepository->findby([], ['id' => 'ASC'], 3, $offset);
        // $tricks = $trickRepository->findby([], ['id' => 'ASC'], $limit, 3);
        $jsonContent = $serializer->serialize($tricks, 'json', ['groups' => 'group1']);

        // $trick = $trickRepository->find(11);
        // $jsonContent = $serializer->serialize($trick, 'json', ['groups' => 'group1']);


        // $encoders = [new JsonEncoder()];
        // $normalizers = [new ObjectNormalizer()];
        // $serializer = new Serializer($normalizers, $encoders);

        // $trick = $trickRepository->find(4);
        // $jsonContent = $serializer->serialize($trick, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['pictures', 'videos', 'messages', 'user']]);
        // $jsonContent = $serializer->serialize($trick, 'json', [
        //     'circular_reference_handler' => function ($object) {
        //         return $object->getId();
        //     }
        // ]);


        // $dataResponse = $jsonContent;
        // $dataResponse = $trickJson;
        // $dataResponse = $limit;
        // $dataResponse = 'salut nicolas t es le super meilleur';
        // return new JsonResponse(['dataResponse' => $dataResponse]);
        return new JsonResponse(['dataResponse' => $jsonContent]);
    }
}
