<?php

namespace App\Controller;

use App\Entity\Auteurs;
use App\Form\Auteurs1Type;
use App\Repository\AuteursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auteurs")
 */
class AuteursController extends AbstractController
{
    /**
     * @Route("/", name="auteurs_index", methods={"GET"})
     */
    public function index(AuteursRepository $auteursRepository): Response
    {
        return $this->render('auteurs/index.html.twig', [
            'auteurs' => $auteursRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="auteurs_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $auteur = new Auteurs();
        $form = $this->createForm(Auteurs1Type::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($auteur);
            $entityManager->flush();

            return $this->redirectToRoute('auteurs_index');
        }

        return $this->render('auteurs/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="auteurs_show", methods={"GET"})
     */
    public function show(Auteurs $auteur): Response
    {
        return $this->render('auteurs/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="auteurs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Auteurs $auteur): Response
    {
        $form = $this->createForm(Auteurs1Type::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('auteurs_index');
        }

        return $this->render('auteurs/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="auteurs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Auteurs $auteur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($auteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('auteurs_index');
    }
}
