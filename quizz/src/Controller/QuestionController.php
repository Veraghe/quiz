<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question_index", methods={"GET"})
     * @param Question $question
     * @return Response
     */
    public function index(QuestionRepository $questionRepository, ReponseRepository $reponseRepository, Request $request): Response
    {
        $question = $questionRepository->findBy(['Questionnaire' => $request->query->get('id')]);
         dump($question);
        return $this->render('question/index.html.twig',[
            'questions' => $question,
            'reponses' => $reponseRepository->findAll(),
        ]);
    }
// *************************************************************************************
        /**
     * @Route("/questionAdmin", name="question_admin", methods={"GET"})
     * @param Question $question
     * @return Response
     */
    public function questionAdmin(QuestionRepository $questionRepository, ReponseRepository $reponseRepository, Request $request): Response
    {
        return $this->render('question/index.html.twig',[
            'questions' => $questionRepository->findAll(),
            'reponses' => $reponseRepository->findAll(),
        ]);
    }


    /**
     * @Route("/new", name="question_new", methods={"GET","POST"})
     */
    public function new(QuestionRepository $questionRepository,Request $request): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_admin');
        }

        return $this->render('question/new.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            // 'id'=> 1
        ]);
    }

    /**
     * @Route("/{id}", name="question_show", methods={"GET"})
     */
    public function show(Question $question, ReponseRepository $reponseRepository,  QuestionRepository $questionRepository, Request $request): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
            'reponses' => $reponseRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_admin');
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_admin');
    }
}
