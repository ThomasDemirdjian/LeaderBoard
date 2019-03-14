<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use App\Entity\Teams;
use App\Entity\Matches;

class TeamsController extends Controller
{
    /**
     * @Route("teams", name="teams_show")
     */
    public function index()
    {
        $teamMatches = [];
        $repository = $this->getDoctrine()
            ->getRepository(Teams::class);
        $teams = $repository->findBy(array(), array("skill_mu" => "DESC"));
        foreach ($teams as $team)
        {
            $won = $this->getDoctrine()
            ->getRepository(Matches::class)
            ->countByAllMatchesWon($team->getId());
            $equal = $this->getDoctrine()
                ->getRepository(Matches::class)
                ->countByAllMatchesEqual($team->getId());
            $lost = $this->getDoctrine()
                ->getRepository(Matches::class)
                ->countByAllMatchesLost($team->getId());
            $teamMatches += [ $team->getId() => array(
                                                        "won" => $won["0"]["1"],
                                                        "equal" => $equal["0"]["1"],
                                                        "lost" => $lost["0"]["1"],
                                                        )];
        }
        return $this->render('board/teams.html.twig', [
			'teams' => $teams, 'teamMatches' => $teamMatches,
        ]);
    }

    /**
     * @Route("teams/{id}", name="teams_show_id")
     */
    public function show($id)
    {  
        $matches = [];
        $team = $this->getDoctrine()
            ->getRepository(Teams::class)
            ->find($id);

        if (!$team) {
            throw $this->createNotFoundException(
                'No teams found for id '.$id
            );
        }
        $won = $this->getDoctrine()
            ->getRepository(Matches::class)
            ->countByAllMatchesWon($team->getId());
        $equal = $this->getDoctrine()
            ->getRepository(Matches::class)
            ->countByAllMatchesEqual($team->getId());
        $lost = $this->getDoctrine()
            ->getRepository(Matches::class)
            ->countByAllMatchesLost($team->getId());
        $matchesResult = $this->getDoctrine()
            ->getRepository(Matches::class)
            ->findAllMatches($team->getId());
        foreach ($matchesResult as $match)
        {
            $isWon = -1;
            $duration = ["start" => $match->getStart(), "end" => $match->getEnd()];
            if (($match->getIdTeam1() == $id && $match->getWinner() == 1) || ($match->getIdTeam2() == $id && $match->getWinner() == 2)) {
                    $isWon = 0;
            }
            else if (($match->getIdTeam1() == $id && $match->getWinner() == 2) || ($match->getIdTeam2() == $id && $match->getWinner() == 1)) {
                    $isWon = 1;
            }
            $team1 = $this->getDoctrine()
                ->getRepository(Teams::class)
                ->find($match->getIdTeam1());
            $team2 = $this->getDoctrine()
                ->getRepository(Teams::class)
                ->find($match->getIdTeam2());
            $matches += [ $match->getId() => array(
                "id_team1" => $match->getIdTeam1(),
                "name_team1" => $team1,
                "id_team2" => $match->getIdTeam2(),
                "name_team2" => $team2,
                "isWon" => $isWon,
                "duration" => $duration
                )];
        }
        return $this->render('board/team.html.twig', [
			'team' => $team, 'won' => $won[0][1], 'equal' => $equal[0][1], 'lost' => $lost[0][1], 'matches' => $matches
        ]);
    }
    /**
     * @Route("/new/teams", name="teams_new")
    */
    public function new(Request $request)
    {
        if ($request->isMethod('GET'))
            return $this->render('board/new_team.html.twig');
        else if ($request->isMethod('POST')) {
            $entityManager = $this->getDoctrine()->getManager();
            $team = new Teams();
            $team->setName($request->request->get('name'));
            $team->setSkillMu($request->request->get('skill_mu'));
            $team->setSkillSigma($request->request->get('skill_sigma'));
            $entityManager->persist($team);
            $entityManager->flush();
            $repository = $this->getDoctrine()
            ->getRepository(Teams::class);
            $teams = $repository->findAll();
            return $this->redirect('/generator');
        }
    }
}