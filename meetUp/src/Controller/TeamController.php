<?php

namespace App\Controller;

use App\Entity\CommentTeam;
use App\Entity\Team;
use App\Entity\User;
use App\Form\CommentTeamType;
use App\Repository\CommentTeamRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @var CommentTeamRepository
     */
    private $commentTeamRepository;

    public function __construct(TeamRepository $repository, CommentTeamRepository $commentTeamRepository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->commentTeamRepository = $commentTeamRepository;
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
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function show(Team $team, string $slug, Request $request): Response
    {
        $comment = new CommentTeam();
        $form = $this->createForm(CommentTeamType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if ($this->getUser() !== null) {
                $comment->setCreatedAt(new \DateTime());
                $comment->setTeam($team);
                $comment->setUser($this->getUser());

                $this->getDoctrine()->getManager()->persist($comment);
                $this->getDoctrine()->getManager()->flush();
            } else {
                return $this->redirectToRoute('login');
            }
        }

        $comments = $this->commentTeamRepository->findBy(['team' => $team->getId()]);

        if ($team->getSlug() !== $slug) {
            return $this->redirectToRoute('teams.show', [
                'id' => $team->getId(),
                'slug' => $team->getSlug()
            ], 301);
        }

        return $this->render('teams/show.html.twig', [
            'commentTeamForm' => $form->createView(),
            'team' => $team,
            'comments' => $comments,
            'current_menu' => 'team'
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
        $comment = new CommentTeam();
        $form = $this->createForm(CommentTeamType::class, $comment);

        $team = $this->getDoctrine()
            ->getRepository(Team::class)
            ->find($team);

        $comments = $this->commentTeamRepository->findBy(['team' => $team->getId()]);

        if ($team->getSlug() === $slug) {
            $user = $this->getUser();
            $team->addUser($user);
            $user->addTeam($team);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Vous avez rejoint le groupe");

        }

        return $this->render('teams/show.html.twig', [
            'commentTeamForm' => $form->createView(),
            'team' => $team,
            'comments' => $comments,
            'current_menu' => 'team'
        ]);
    }
    /**
     * @Route("/teams/{slug}-{id}/quit", name="teams.quit", requirements={"slug": "[a-z0-9\-]*"})
     * @param Team $team
     * @param string $slug
     * @return Response
     */
    public function quit(Team $team, string $slug): Response
    {
        $comment = new CommentTeam();
        $form = $this->createForm(CommentTeamType::class, $comment);

        $team = $this->getDoctrine()
            ->getRepository(Team::class)
            ->find($team);

        $comments = $this->commentTeamRepository->findBy(['team' => $team->getId()]);

        if ($team->getSlug() === $slug) {
            $user = $this->getUser();
            $team->removeUser($user);
            $user->addTeam($team);

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', "Vous avez quitter le groupe");

        }

        return $this->render('teams/show.html.twig', [
            'commentTeamForm' => $form->createView(),
            'team' => $team,
            'comments' => $comments,
            'current_menu' => 'team'
        ]);
    }
}
