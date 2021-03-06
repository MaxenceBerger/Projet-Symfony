<?php
namespace App\Controller;

use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param EventRepository $repository
     * @return Response
     */

    public function home(EventRepository $repository): Response
    {
        $properties = $repository->findLatest();
        return $this->render('pages/home.html.twig', [
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/evenements", name="events.index")
     * @param EventRepository $repository
     * @return Response
     */

    public function index(EventRepository $repository): Response
    {
        $properties = $repository->findAll();
        return $this->render('events/index.html.twig', [
            'properties' => $properties
        ]);
    }
}