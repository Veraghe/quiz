<?php

namespace App\Controller;
use App\Entity\ReponseUtilisateur;
use App\Repository\ReponseUtilisateurRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReponseUtilisateurController extends AbstractController
{
    /**
     * @Route("/reponseUtilisateur", name="reponse_utilisateur", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function show(ReponseUtilisateurRepository $reponseUtilisateurRepository, UtilisateurRepository $utilisateurRepository, ReponseRepository $reponseRepository): Response
    {
        return $this->render('reponse_utilisateur/reponse.html.twig', [
            'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
            'utilisateurs' => $utilisateurRepository->findAll(),
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

}
