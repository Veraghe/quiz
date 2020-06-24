<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Form\QuestionnaireType;
use App\Repository\QuestionnaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/questionnaire")
 */
class QuestionnaireController extends AbstractController
{

    /**
     * @Route("/", name="questionnaire_index", methods={"GET"})
     */
    public function index(QuestionnaireRepository $questionnaireRepository): Response
    {
        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaireRepository->findAll(),
        ]);
    }
    //FONCTION: Accueil----------------------------------------------
    /**
     * @Route("/home", name="home" , methods={"GET"})
     */
    public function home(QuestionnaireRepository $questionnaireRepository): Response
    {
        return $this->render('questionnaire/home.html.twig', [
            'questionnaires' => $questionnaireRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="questionnaire_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $questionnaire = new Questionnaire();
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($questionnaire);
            $entityManager->flush();

            return $this->redirectToRoute('questionnaire_index');
        }

        return $this->render('questionnaire/new.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questionnaire_show", methods={"GET"})
     */
    public function show(Questionnaire $questionnaire): Response
    {
        return $this->render('questionnaire/show.html.twig', [
            'questionnaire' => $questionnaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="questionnaire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Questionnaire $questionnaire): Response
    {
        $form = $this->createForm(QuestionnaireType::class, $questionnaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('questionnaire_index');
        }

        return $this->render('questionnaire/edit.html.twig', [
            'questionnaire' => $questionnaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="questionnaire_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Questionnaire $questionnaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$questionnaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($questionnaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('questionnaire_index');
    }
}
