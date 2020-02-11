<?php
namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminEventsController extends AbstractController
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
     * @Route("/admin", name="admin.events.index")
     * @return Response
     */
    public function index()
    {
        $events = $this->repository->findAll();

        return $this->render("admin/events/index.html.twig", [
            'events' => $events,
            'current_menu' => 'admin'
        ]);
    }


    /**
     * @Route("/admin/create", name="admin.events.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($event);
            $this->em->flush();
            $this->addFlash('success', 'Crée avec succès');

            return $this->redirectToRoute('admin.events.index');
        }

        return $this->render("admin/events/new.html.twig", [
            'events' => $event,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/{id}", name="admin.events.edit", methods="GET|POST")
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function edit(Event $event, Request $request)
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Modifié avec succès');

            return $this->redirectToRoute('admin.events.index');
        }

        return $this->render("admin/events/edit.html.twig", [
            'events' => $event,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/{id}", name="admin.events.delete", methods="DELETE")
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function delete(Event $event, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->get('_token'))) {
            $this->em->remove($event);
            $this->em->flush();
            $this->addFlash('success', 'Supprimé avec succès');

        }
        return $this->redirectToRoute('admin.events.index');

    }


}