<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Forms;
use App\Entity\Matches;
use App\Entity\Teams;

class MatchesController extends Controller
{

	/**
	 * @Route("/new/matches", name="match_new")
     */
	public function index(Request $request)
	{
		$repository = $this->getDoctrine()
		->getRepository(Teams::class);
        $teams = $repository->findAll();
		if ($request->isMethod('GET'))
			return $this->render('board/new_match.html.twig', [
				'teams' => $teams, 'winner' => ""
				]);
		else if ($request->isMethod('POST')) {
			$matchTeams = [
					"1" => $teams[$request->request->get('id_team1') - 1],
					"2" => $teams[$request->request->get('id_team2') - 1]];
			$entityManager = $this->getDoctrine()->getManager();
			$match = new Matches();
			$match->setIdTeam1($request->request->get('id_team1'));
			$match->setIdTeam2($request->request->get('id_team2'));
			$match->setStart(new \DateTime($request->request->get('start')));
			$match->setEnd(new \DateTime($request->request->get('end')));
			$winner = self::whoWinns($matchTeams["1"], $matchTeams["2"]);
			$match->setWinner($winner);
			$entityManager->persist($match);
			$entityManager->flush();
			$winner = $matchTeams[$winner];
			return $this->render('board/new_match.html.twig', [
				'teams' => $teams, 'winner' => $winner
				]);
			}
		}

	public function whoWinns($team1, $team2)
	{
		$team1Power = $team1->getSkillMu() + random_int($team1->getSkillSigma() * -1, $team1->getSkillMu());
		$team2Power = $team2->getSkillMu() + random_int($team2->getSkillSigma() * -1, $team2->getSkillMu());
		$entityManager = $this->getDoctrine()->getManager();
		$team1 = $entityManager->getRepository(Teams::class)->find($team1->getId());
		$team2 = $entityManager->getRepository(Teams::class)->find($team2->getId());

		if ($team1Power > $team2Power) {
			$team1->setSkillMu($team1->getSkillMu() + 1);
			if ($team1->getSkillSigma() > 1)
				$team1->setSkillSigma($team1->getSkillSigma() - 1);
			$entityManager->persist($team1);
			if ($team2->getSkillMu() > 1)
				$team2->setSkillMu($team2->getSkillMu() - 1);
			if ($team2->getSkillSigma() > 1)
				$team2->setSkillSigma($team2->getSkillSigma() - 1);
			$entityManager->persist($team2);
			$entityManager->flush();
			return ("1");
		}
		else if ($team1Power < $team2Power) {
			$team2->setSkillMu($team2->getSkillMu() + 1);
			if ($team2->getSkillSigma() > 1)
				$team2->setSkillSigma($team2->getSkillSigma() - 1);
			$entityManager->persist($team2);
			if ($team1->getSkillMu() > 1)
				$team1->setSkillMu($team1->getSkillMu() - 1);
			if ($team1->getSkillSigma() > 1)
				$team1->setSkillSigma($team1->getSkillSigma() - 1);
			$entityManager->persist($team1);
			$entityManager->flush();
			return ("2");
		}
		else {
			if ($team1->getSkillSigma() > 1)
				$team1->setSkillSigma($team1->getSkillSigma() - 1);
			$entityManager->persist($team1);
			if ($team2->getSkillSigma() > 1)
				$team2->setSkillSigma($team2->getSkillSigma() - 1);
			$entityManager->persist($team2);
			$entityManager->flush();
			return ("0");
		}
	}
}