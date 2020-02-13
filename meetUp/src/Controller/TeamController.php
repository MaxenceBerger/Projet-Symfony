<?php

namespace App\Controller;

use App\Entity\Team;
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
        if ($team->getSlug() !== $slug) {
            return $this->redirectToRoute('teams.show', [
                'id' => $team->getId(),
            ], 301);
        }
        return $this->render('teams/show.html.twig', [
            'team' => $team,
            'current_menu' => 'team'
        ]);
    }
}
