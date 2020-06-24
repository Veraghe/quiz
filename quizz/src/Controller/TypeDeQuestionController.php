<?php

namespace App\Controller;

use App\Entity\TypeDeQuestion;
use App\Form\TypeDeQuestionType;
use App\Repository\TypeDeQuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/type/de/question")
 */
class TypeDeQuestionController extends AbstractController
{
    /**
     * @Route("/", name="type_de_question_index", methods={"GET"})
     */
    public function index(TypeDeQuestionRepository $typeDeQuestionRepository): Response
    {
        return $this->render('type_de_question/index.html.twig', [
            'type_de_questions' => $typeDeQuestionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_de_question_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $typeDeQuestion = new TypeDeQuestion();
        $form = $this->createForm(TypeDeQuestionType::class, $typeDeQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($typeDeQuestion);
            $entityManager->flush();

            return $this->redirectToRoute('type_de_question_index');
        }

        return $this->render('type_de_question/new.html.twig', [
            'type_de_question' => $typeDeQuestion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_de_question_show", methods={"GET"})
     */
    public function show(TypeDeQuestion $typeDeQuestion): Response
    {
        return $this->render('type_de_question/show.html.twig', [
            'type_de_question' => $typeDeQuestion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_de_question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TypeDeQuestion $typeDeQuestion): Response
    {
        $form = $this->createForm(TypeDeQuestionType::class, $typeDeQuestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('type_de_question_index');
        }

        return $this->render('type_de_question/edit.html.twig', [
            'type_de_question' => $typeDeQuestion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_de_question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TypeDeQuestion $typeDeQuestion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeDeQuestion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($typeDeQuestion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('type_de_question_index');
    }
}
