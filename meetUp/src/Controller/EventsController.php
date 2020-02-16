<?php
namespace App\Controller;

use App\Entity\CommentEvent;
use App\Entity\Event;
use App\Entity\User;
use App\Form\CommentEventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventsController extends AbstractController
{
    /**
     * @var EventRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EventRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
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
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function show(Event $event, string $slug, Request $request): Response
    {
        $comment = new CommentEvent();
        $form = $this->createForm(CommentEventType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $user = $this->getDoctrine()
                ->getRepository(User::class)
                ->find($this->getUser());

            $comment->setCreatedAt(new \DateTime());
            $comment->setEvent($event);
            $comment->addUser($user);

            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();
        }

        if ($event->getSlug() !== $slug) {
            return $this->redirectToRoute('events.show', [
                'id' => $event->getId(),
                'slug' => $event->getSlug()
            ], 301);
        }
        return $this->render('events/show.html.twig', [
            'commentEventForm' => $form->createView(),
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