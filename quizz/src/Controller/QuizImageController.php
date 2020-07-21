<?php

namespace App\Controller;

use App\Entity\QuizImage;
use App\Form\QuizImageType;
use App\Repository\QuizImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quiz/image")
 */
class QuizImageController extends AbstractController
{
    /**
     * @Route("/", name="quiz_image_index", methods={"GET"})
     */
    public function index(QuizImageRepository $quizImageRepository): Response
    {
        return $this->render('quiz_image/index.html.twig', [
            'quiz_images' => $quizImageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/quizImage", name="quiz_image_quizImage", methods={"GET"})
     */
    public function quizImage(QuizImageRepository $quizImageRepository): Response
    {
        // $objetQuizImage = $quizImageRepository->findAll();
        // dump($objetQuizImage);
        // $rand = rand(0, count($objetQuizImage) -1);
        // $objetQuizImageRand = shuffle($objetQuizImage[$rand]);
        // dump($objetQuizImageRand);
        return $this->render('quiz_image/quizImage.html.twig', [
            'quiz_images' => $quizImageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="quiz_image_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $quizImage = new QuizImage();
        $form = $this->createForm(QuizImageType::class, $quizImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quizImage);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_image_index');
        }

        return $this->render('quiz_image/new.html.twig', [
            'quiz_image' => $quizImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_image_show", methods={"GET"})
     */
    public function show(QuizImage $quizImage): Response
    {
        return $this->render('quiz_image/show.html.twig', [
            'quiz_image' => $quizImage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quiz_image_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, QuizImage $quizImage): Response
    {
        $form = $this->createForm(QuizImageType::class, $quizImage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quiz_image_index');
        }

        return $this->render('quiz_image/edit.html.twig', [
            'quiz_image' => $quizImage,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quiz_image_delete", methods={"DELETE"})
     */
    public function delete(Request $request, QuizImage $quizImage): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizImage->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quizImage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quiz_image_index');
    }
}
