<?php
namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $reposiptory;

    public function __construct(EventRepository $reposiptory)
    {
        $this->reposiptory = $reposiptory;
    }

    /**
     * @Route("/evenements", name="events.index")
     * @return Response
     */
    public function index(): Response
    {
        $properties = $this->reposiptory->find(1);
        return $this->render("events/index.html.twig", [
            "current_menu" => "events"
        ]);
    }

    /**
     * @Route("/evenements/{slug}-{id}", name="events.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Event $event
     * @param string $slug
     * @return Response
     */
    public function show(Event $event, string $slug): Response
    {
        if ($event->getSlug() !== $slug) {
            return $this->redirectToRoute('events.show', [
                'id' => $event->getId(),
                'slug' => $event->getSlug()
            ], 301);
        }
        return $this->render('events/show.html.twig', [
            'event' => $event,
            'current_menu' => 'event'
        ]);
    }
}