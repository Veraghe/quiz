<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Entity\Question;
use App\Entity\ReponseUtilisateur;
use App\Entity\Reponse;
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

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
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
    public function quiz(Questionnaire $questionnaire,QuestionnaireRepository $questionnaireRepository, Request $request, Reponse $reponse, QuestionRepository $questionRepository, ReponseRepository $reponseRepository): Response
    {
        // $reponseUtilisateur = new ReponseUtilisateur();
        // $form = $this->createForm(ReponseUtilisateurType::class, $reponseUtilisateur);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($reponseUtilisateur);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('home');
        // }

        $questions = $questionnaire->getQuestions();
        foreach ( $questions as $question)
        {
            dump($question);
            $reponses = $reponseRepository->findBy(['question' => $question->getId()]);
            dump($reponses);
            $question_array = [];
            foreach ($reponses as $reponse)
            {
                $question_array[$reponse->getLibelleReponse()] = $reponse->getId();

            }
            dump($question_array);
        }



        
        // foreach ( $questions as $question){
            //Data Class: Créer un formulaire sans classe pour afficher une seule question et ses 4 réponses
            $defaultData = ['question' => 'Tapez votre question ici'];
            $form2 = $this->createFormBuilder($defaultData)
            ->add('utilisateur', TextType::class, [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 3]),
                    ],
                ])

            //Si l'utilisateur le sélectionne "reponse 2", le formulaire retournera false pour ce champ.
            ->add('reponse', ChoiceType::class,[
                'choices'  => [
                    $question_array,
        
                ],
                'expanded'=> true,
                //Si multiple = false (radio bouton), = true (checkbox)
                'multiple'=>true
            ])

            

        ->add('valider', SubmitType::class)
        ->getForm();
    // }
        $form2->handleRequest($request);

        // dump($form2);
        
        if ($form2->isSubmitted() && $form2->isValid()) {
            //les données sont un tableau "utilisateur" et "reponse"
            $data = $form2->getData();
        }


       return $this->render('question/quiz.html.twig',[
           'questionnaire' => $questionnaire,
        //    'reponse_utilisateur' => $reponseUtilisateur,
            // 'form' => $form->createView(),
            'form2' => $form2->createView(),
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
