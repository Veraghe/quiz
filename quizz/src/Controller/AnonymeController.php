<?php

namespace App\Controller;

use App\Entity\Anonyme;
use App\Form\AnonymeType;
use App\Repository\AnonymeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/anonyme")
 */
class AnonymeController extends AbstractController
{
    /**
     * @Route("/", name="anonyme_index", methods={"GET"})
     */
    public function index(AnonymeRepository $anonymeRepository): Response
    {
        return $this->render('anonyme/index.html.twig', [
            'anonymes' => $anonymeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="anonyme_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $anonyme = new Anonyme();
        $form = $this->createForm(AnonymeType::class, $anonyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($anonyme);
            $entityManager->flush();

            return $this->redirectToRoute('anonyme_index');
        }

        return $this->render('anonyme/new.html.twig', [
            'anonyme' => $anonyme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="anonyme_show", methods={"GET"})
     */
    public function show(Anonyme $anonyme): Response
    {
        return $this->render('anonyme/show.html.twig', [
            'anonyme' => $anonyme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="anonyme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Anonyme $anonyme): Response
    {
        $form = $this->createForm(AnonymeType::class, $anonyme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('anonyme_index');
        }

        return $this->render('anonyme/edit.html.twig', [
            'anonyme' => $anonyme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="anonyme_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Anonyme $anonyme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$anonyme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($anonyme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('anonyme_index');
    }
}
