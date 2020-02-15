<?php
namespace App\Controller;

use App\Entity\Event;
use App\Entity\Team;
use App\Entity\User;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $repository;

    public function __construct(EventRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/evenements", name="events.index")
     * @return Response
     */
    public function index(): Response
    {
        $properties = $this->repository->find(1);
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
    /**
     * @Route("/evenements/{slug}-{id}/join", name="events.join", requirements={"slug": "[a-z0-9\-]*"})
     * @param Event $event
     * @param string $slug
     * @return Response
     */
    public function join(Event $event, string $slug): Response
    {
        $event = $this->getDoctrine()
            ->getRepository(Event::class)
            ->find($event);

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser());

        if ($event->getSlug() === $slug) {
            $event->addUser($user);
            $user->addEvent($event);

            $this->getDoctrine()->getManager()->flush();


        } else {
            return $this->redirectToRoute('events.show', [
                'id' => $event->getId(),
            ], 301);
        }
        return $this->render('events/show.html.twig', [
            'event' => $event,
            'current_menu' => 'event'
        ]);
    }
}