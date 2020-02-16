<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{

    /**
     * @var TeamRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(TeamRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/team", name="team")
     * @return Response
     */
    public function index()
    {
        $properties = $this->repository->findAll();
        return $this->render('teams/index.html.twig', [
            'controller_name' => 'TeamController',
            'properties' => $properties,
            'current_menu' => 'team'
        ]);
    }

    /**
     * @Route("/teams/{slug}-{id}", name="teams.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Team $team
     * @param string $slug
     * @return Response
     */
    public function show(Team $team, string $slug): Response
    {
        $properties = $this->repository->findAll();

        if ($team->getSlug() !== $slug) {
            return $this->redirectToRoute('teams.show', [
                'id' => $team->getId(),
            ], 301);
        }
        return $this->render('teams/show.html.twig', [
            'team' => $team,
            'current_menu' => 'team',
            'properties' => $properties,

        ]);
    }

    /**
     * @Route("/teams/{slug}-{id}/join", name="teams.join", requirements={"slug": "[a-z0-9\-]*"})
     * @param Team $team
     * @param string $slug
     * @return Response
     */
    public function join(Team $team, string $slug): Response
    {
        $team = $this->getDoctrine()
            ->getRepository(Team::class)
            ->find($team);

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($this->getUser());

        if ($team->getSlug() === $slug) {
            $team->addUsersMember($user);
            $user->addTeamsMember($team);

            $this->getDoctrine()->getManager()->flush();

        } else {
            return $this->redirectToRoute('teams.show', [
                'id' => $team->getId(),
            ], 301);
        }
        return $this->render('teams/show.html.twig', [
            'team' => $team,
            'current_menu' => 'team'
        ]);
        $this->addFlash('success', 'Rejoin avec succès');

    }
}
