<?php

namespace App\Controller;

use App\Entity\Questionnaire;
use App\Entity\Question;
use App\Entity\ReponseUtilisateur;
use App\Entity\Reponse;
use App\Entity\Utilisateur;
use App\Entity\Anonyme;
use App\Form\QuestionnaireType;
use App\Form\QuestionType;
use App\Form\ReponseUtilisateurType;
use App\Repository\QuestionnaireRepository;
use App\Repository\ReponseRepository;
use App\Repository\ReponsesUtilisateurRepository;
use App\Repository\QuestionRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\AnonymeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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

//********************************************************************** */
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

        //FONCTION: Accueil----------------------------------------------
    /**
     * @Route("/reponses/", name="questionnaire_reponses" , methods={"GET"})
     * @param Questionnaire $questionnaire
     * @return Response
     */
    public function questionnaireReponses(QuestionnaireRepository $questionnaireRepository, QuestionRepository $questionRepository): Response
    {
        return $this->render('questionnaire/questionnaireReponses.html.twig', [
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
    public function quiz(Questionnaire $questionnaire,QuestionnaireRepository $questionnaireRepository, QuestionRepository $questionRepository, Reponse $reponse,  ReponseRepository $reponseRepository, UtilisateurRepository $utilisateurRepository,AnonymeRepository $anonymeRepository, Request $request): Response
    {
        // ------------------------Form ReponseUtilisateur (affichage avec listes déroulantes!)---------------------------------------------------
        // $reponseUtilisateur = new ReponseUtilisateur();
        // $form = $this->createForm(ReponseUtilisateurType::class, $reponseUtilisateur);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($reponseUtilisateur);
        //     $entityManager->flush();

        //     return $this->redirectToRoute('home');
        // }

        // ------------------------Form ReponseUtilisateur ---------------------------------------------------
        $questions =$questionnaire->getQuestions();
        $affiche_question = [];
        foreach ( $questions as $clef => $question)
        {
            // dump($question);
            $reponses = $reponseRepository->findBy(['question' => $question->getId()]);
            // dump($reponses);
           $affiche_question =$question->getLibelleQuestion();
           $affiche_type_de_question =$question->getTypeDeQuestion()->getId();
            // dump($affiche_question);
            // dump($affiche_type_de_question);
            
            $question_array = [];
            foreach ($reponses as $reponse)
            {
               $question_array[$reponse->getLibelleReponse()]= $reponse->getId();
               
            }
            // dump($question_array);

            // Récupère dans un tableau les questions et les réponses 
            $tab_reponse[$clef]=$question_array;
            // dump($tab_reponse);
             $tab_question[$clef]=$affiche_question;
            // dump($tab_question);
            $tab_type_de_question[$clef]=$affiche_type_de_question;
            dump($tab_type_de_question);
        }
        // récupérer les données de $affiche_question et $question_array pour les utiliser dans le form
 

        $formBuilder = $this->createFormBuilder();
        // ------------------------Ajouter un utilisateur---------------------------------------------------
        // Si, il est connecté, passé HiddenType est récupérer l'id 
        if( $this->getUser() ){
            $formBuilder->add('utilisateur', HiddenType::class, [
                    'data'=>$this -> getUser()->getId(),
                ]);
            }
        //ou Creer un utilisateur avec un nouvelle id 
        else{
            $formBuilder->add('anonyme', EmailType::class, [
                'label'=>"Email:"
            ]);
        }
        // ------------------------Ajouter la date courante---------------------------------------------------
        $formBuilder->add('date',HiddenType::class, [
            
        ]);
    
        // ------------------------Ajouter une réponse et une question---------------------------------------------------

        $key=1;
        $i=0;
        foreach ( $questions as $question)
        {
            // Récupère l'id questionnaire :
            $formBuilder->add('question',HiddenType::class,[
            'data'=>$questionnaire->getId(),
            ]);
            // dump($tab_question[$i]);

            // Ajouter une condition pour le type de question
            // Trouver une façon de récupèrer le type de question depuis la question
            
            // if($tab_type_de_question[$i] ==4 ){        
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
            // else{
            //     $formBuilder->add('reponse'.$i,  ChoiceType::class,[
            //         'choices'  => [
            //             // $question_array,
            //             $tab_reponse[$i],
            //         ],
            //         'expanded'=> true,
            //         //Si multiple = false (radio bouton), = true (checkbox)
            //         'multiple'=>false,
            //         'label'=>  $key.": ".$tab_question[$i],
                    
            //     ]);
            // }
                 
            $key++ ;
            $i++;
           // dump($formBuilder);
        }
        dump($i);
// ---------------------------------------------------------------------------
        $form2=$formBuilder->getForm();

        $form2->handleRequest($request);


        if ($form2->isSubmitted() && $form2->isValid()) {
            //les données sont un tableau "utilisateur" et "reponse"
            $data = $form2->getData();
            // dump($data["utilisateur"]);
            if($this->getUser())
            $objetUtilisateur=$utilisateurRepository->find($data["utilisateur"]);
           
        //    $objetUtilisateur= $this -> getUser()->getId();
            // $i=0;
        // ---------------------------------------
        // Pour les utilisateurs qui ne sont pas inscrit:
            // Créer une entité Anonyme 
            // ------------------------------------------
            // Au moment de remplir le quiz, créer new Anonyme(), avec son Email;
            else {
            $objetEmail=$data["anonyme"];
            $a = new Anonyme();
            $a -> setEmail($objetEmail);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($a);
            $entityManager->flush();
            dump($a);
            // -------------Récupérer l'id de l'anonyme----------------------------
            $objetAnonyme=$a;
            // dump($objetAnonyme);
            }
            // $i = nombre de question
            for($j=0;$j<$i;$j++){
                foreach ($data["reponse".$j] as $reponse)
                {
                    dump($data["reponse".$j]);
                    $Objet = $reponseRepository->find($reponse);
                    dump($Objet->getQuestion());
                    // $dateReponse->setDate(new \DateTime());

                    // pour chaque question
                    // Créer une entité reponse_utilisateur vide
                    $ru = new reponseUtilisateur();
                    // peupler l'entité en question avec les données du form 
                    $ru->setReponse($Objet);
                    // Si, il est connecté "utilisateur" sinon "anonyme":
                    if($this->getUser())
                        $ru->setUtilisateur($objetUtilisateur);
                    else
                        $ru->setAnonyme($objetAnonyme);

                    $ru->setQuestion($Objet->getQuestion());
                    $ru->setDate(new \DateTime());
                    // enregistrer l'entité dans la BDD
                    dump($ru);

                    $entityManager = $this->getDoctrine()->getManager();
                    //Cette méthode signale à Doctrine que l'objet doit être enregistré
                    $entityManager->persist($ru);
                }
                //Met à jour la base à partir des objets signalés à Doctrine.
                $entityManager->flush();
            }
            if($this->getUser())
                return $this->redirectToRoute('reponse_utilisateur_resultat');
            else
                return $this->redirectToRoute('reponse_utilisateur_anonyme');
        
    }

       return $this->render('question/quiz.html.twig',[
           'questionnaire' => $questionnaire,
        //    'reponse_utilisateur' => $reponseUtilisateur,
            // 'form' => $form->createView(),
            'form2' => $form2->createView(),
        ]);
    }

//********************************************************************** */
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
//********************************************************************** */
    /**
     * @Route("/{id}/show", name="questionnaire_show", methods={"GET"})
     */
    public function show(Questionnaire $questionnaire): Response
    {
        return $this->render('questionnaire/show.html.twig', [
            'questionnaire' => $questionnaire,
        ]);
    }
//********************************************************************** */
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
//********************************************************************** */
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
