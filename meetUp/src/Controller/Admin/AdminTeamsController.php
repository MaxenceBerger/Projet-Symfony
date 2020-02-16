<?php
namespace App\Controller\Admin;

use App\Entity\Team;
use App\Entity\User;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdminTeamsController extends AbstractController
{

    /**
     * @var TeamRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TeamRepository $repository, EntityManagerInterface $em )
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/admin/team", name="admin.teams.index")
     * @return Response
     */
    public function index()
    {
        $teams = $this->repository->findAll();

        return $this->render("admin/teams/index.html.twig", [
            'teams' => $teams,
            'current_menu' => 'admin.team'
        ]);
    }


    /**
     * @Route("/admin/team/create", name="admin.teams.new")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request)
    {
        $team = new Team();
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($team);
            $this->em->flush();
            $this->addFlash('success', 'Crée avec succès');

            return $this->redirectToRoute('admin.teams.index');
        }

        return $this->render("admin/teams/new.html.twig", [
            'teams' => $team,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin/team/{id}", name="admin.teams.edit", methods="GET|POST")
     * @param Team $team
     * @param Request $request
     * @return Response
     */
    public function edit(Team $team, Request $request)
    {
        $form = $this->createForm(TeamType::class, $team);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($team);
            $this->em->flush();
            $this->addFlash('success', 'Modifié avec succès');

            return $this->redirectToRoute('admin.teams.index');
        }

        return $this->render("admin/teams/edit.html.twig", [
            'teams' => $team,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/team/{id}", name="admin.teams.delete", methods="DELETE")
     * @param Team $team
     * @param Request $request
     * @return Response
     */
    public function delete(Team $team, Request $request)
    {
        if ($this->isCsrfTokenValid('delete'.$team->getId(), $request->get('_token'))) {
            $this->em->remove($team);
            $this->em->flush();
            $this->addFlash('success', 'Supprimé avec succès');

        }
        return $this->redirectToRoute('admin.teams.index');

    }


}