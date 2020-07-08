<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Entity\Question;
use App\Entity\ReponseUtilisateur;
use App\Entity\ReponsesUtilisateur;
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
    //********************************************************************** */
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

        $questions =$questionnaire->getQuestions();
        $affiche_question = [];
        foreach ( $questions as $clef => $question)
        {
            // dump($question);
            $reponses = $reponseRepository->findBy(['question' => $question->getId()]);
            // dump($reponses);
           $affiche_question =$question->getLibelleQuestion();
            dump($affiche_question);
            
            $question_array = [];
            foreach ($reponses as $reponse)
            {
               $question_array[$reponse->getLibelleReponse()]= $reponse->getId();
               
            }
            // dump($question_array);

            // Récupère dans un tableau les questions et les réponses 
            $tab_reponse[$clef]=$question_array;
            dump($tab_reponse);
             $tab_question[$clef]=$affiche_question;
            dump($tab_question);
        }
        // récupérer les données de $affiche_question et $question_array pour les utiliser dans le form
        // pour le moment je récupère que les dernières données du foreach!!!


            //Data Class: Créer un formulaire sans classe pour afficher une seule question et ses 4 réponses
            //$defaultData = ['question' => 'Ici la question'];
        $quiz=new ReponsesUtilisateur();
        $formBuilder = $this->createFormBuilder($quiz);
        // $formBuilder = $this->createFormBuilder();
        $formBuilder->add('utilisateur', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3]),
                ],
                'label'=>"Email :"
            ]);
        // ---------------------------------------------------------------------------

            $key=1;
            $i=0;
            foreach ( $questions as $question)
            {
            // $formBuilder->add('question'.$i, ChoiceType::class,[
            //     'choices'  => [
            //         $key.": ".$tab_question[$i] => $tab_question[$i],
            //         // $key.": ".$affiche_question => $affiche_question,
            //     ],
            //     'expanded'=> true,
            //     'attr' =>[
            //         'class'=>'titreQuiz'
            //     ],
            //     'label'=> ' '
            // ]);
                // foreach ($reponses as $reponse)
                // {
                
                $formBuilder->add('reponse'.$i,  ChoiceType::class,[
                    'choices'  => [
                        // $question_array,
                        $tab_reponse[$i],
                    ],
                    'expanded'=> true,
                    //Si multiple = false (radio bouton), = true (checkbox)
                    'multiple'=>true,
                    'label'=>  $key.": ".$tab_question[$i],
                    
                ]);
                // } 
            $key++ ;
            $i++;
            dump($affiche_question);
           // dump($formBuilder);
        }
        
// ---------------------------------------------------------------------------
        // $formBuilder->add('valider', SubmitType::class,[
        //     'attr' =>[
        //         'class'=>'bleu'
        //             ],
        // ]);
        $form2=$formBuilder->getForm();

        $form2->handleRequest($request);
        


        if ($form2->isSubmitted() && $form2->isValid()) {
            //les données sont un tableau "utilisateur" et "reponse"
            $data = $form2->getData();

            return $this->redirectToRoute('reponse_utilisateur_resultat');
        }


       return $this->render('question/quiz.html.twig',[
           'questionnaire' => $questionnaire,
        //    'reponse_utilisateur' => $reponseUtilisateur,
            // 'form' => $form->createView(),
            'form2' => $form2->createView(),
        ]);
    }
//********************************************************************** */
    // /**
    //  * @Route("/quiz/résultat", name="reponse_utilisateur_resultat", methods={"GET"})
    //  */
    // public function resultat()
    // {
    //     return $this->render('question/resultat.html.twig');
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
     * @Route("/{id}/edit/questionnaire", name="questionnaire_edit", methods={"GET","POST"})
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
