<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Entity\Question;
use App\Entity\ReponseUtilisateur;
use App\Entity\Utilisateur;
use App\Form\QuestionnaireType;
use App\Form\QuestionType;
use App\Form\ReponseUtilisateurType;
use App\Repository\QuestionnaireRepository;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/questionnaire")
 */
class QuestionnaireController extends AbstractController
{

    /**
     * @Route("/", name="questionnaire_index", methods={"GET"})
     * @param Questionnaire $questionnaire
     * @return Response
     */
    public function index(QuestionnaireRepository $questionnaireRepository, QuestionRepository $questionRepository): Response
    {
        return $this->render('questionnaire/index.html.twig', [
            'questionnaires' => $questionnaireRepository->findAll(),
            'questions' => $questionRepository->findAll(),
        ]);
    }
    // /**
    //  * @Route("/{id}/listeQuestions", name="question_index", methods={"GET"})
    //  * @param Questionnaire $questionnaire
    //  * @return Response
    //  */
    // public function indexQuestion(Questionnaire $questionnaire): Response
    // {
    //     return $this->render('question/index.html.twig',[
    //         'questionnaire' => $questionnaire,
    //     ]);
    // }
        /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     */
    public function editQuestion(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }
    //FONCTION: Accueil----------------------------------------------
    /**
     * @Route("/home/", name="home" , methods={"GET"})
     * @param Questionnaire $questionnaire
     * @return Response
     */
    public function home(QuestionnaireRepository $questionnaireRepository, QuestionRepository $questionRepository): Response
    {
        return $this->render('questionnaire/home.html.twig', [
            'questionnaires' => $questionnaireRepository->findAll(),
            'questions' => $questionRepository->findAll(),
        ]);
    }
    /**
     * @Route("/{id}/quiz", name="question_quiz", methods={"GET","POST"})
     * @param Questionnaire $questionnaire
     * @return Response
     */
    public function quiz(Questionnaire $questionnaire, Request $request): Response
    {
        $reponseUtilisateur = new ReponseUtilisateur();
        $form = $this->createForm(ReponseUtilisateurType::class, $reponseUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponseUtilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }
       return $this->render('question/quiz.html.twig',[
           'questionnaire' => $questionnaire,
           'reponse_utilisateur' => $reponseUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    //     /**
    //  * @Route("/{id}/quiz", name="question_quiz", methods={"GET","POST"})
    //  * @param Questionnaire $questionnaire
    //  * @return Response
    //  */
    // public function quiz($id, Questionnaire $questionnaire, Request $request, QuestionRepository $questionRepository): Response
    // {   
    //     $question = $questionRepository->findOneBy(['Questionnaire'=>$id]);

    //     // $utilisateur = $this->getUtilisateur();

    //     $reponses =[
    //         // $question->getReponses() =>'reponse 1',
    //         // $question->getReponses() =>'reponse 2',
    //         // $question->getReponses() =>'reponse 3',
    //         // $question->getReponses() =>'reponse 4'
    //     ];
    //     // dump($reponses);

    //     $form = $this->createFormBuilder()
    //     ->add('reponse', ChoiceType::class,[
    //         'label'=>$question->getLibelleQuestion(),
    //         'choices'=>$reponses,
    //         'expanded'=>true,
    //         'multiple'=>false,
    //     ])
    //     ->getForm();

    //     $form->handleRequest($request);
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $data = $form->getData();
            
    //         dump($data);
    //     }

    //    return $this->render('question/quiz.html.twig',[
    //        'questionnaire' => $questionnaire,
    //     //    'reponse_utilisateur' => $reponseUtilisateur,
    //         'form' => $form->createView(),
    //     ]);
    // }

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
     * @Route("/{id}/show", name="questionnaire_show", methods={"GET"})
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
