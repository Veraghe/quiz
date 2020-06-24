<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Form\Livres4Type;
use App\Repository\LivresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/livres")
 */
class LivresController extends AbstractController
{
    /**
     * @Route("/", name="livres_index", methods={"GET"})
     */
    public function index(LivresRepository $livresRepository): Response
    {
        return $this->render('livres/index.html.twig', [
            'livres' => $livresRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="livres_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $livre = new Livres();
        $form = $this->createForm(Livres4Type::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($livre);
            $entityManager->flush();

            return $this->redirectToRoute('livres_index');
        }

        return $this->render('livres/new.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livres_show", methods={"GET"})
     */
    public function show(Livres $livre): Response
    {
        return $this->render('livres/show.html.twig', [
            'livre' => $livre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="livres_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Livres $livre): Response
    {
        $form = $this->createForm(Livres4Type::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livres_index');
        }

        return $this->render('livres/edit.html.twig', [
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="livres_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Livres $livre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($livre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('livres_index');
    }
}
