<?php

namespace App\Controller;

use App\Controller\generatorController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Teams;

class generatorController extends AbstractController
{
	/**
	 * @Route("/generator")
	 */
	public function index()
	{
		$repository = $this->getDoctrine()
		->getRepository(Teams::class);
        $teams = $repository->findAll();
		return $this->render('board/generator.html.twig', ["winner" => "", "teams" => $teams, "newTeam" => ""]);
	}
}