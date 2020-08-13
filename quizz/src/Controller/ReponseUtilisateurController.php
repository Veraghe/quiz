<?php

namespace App\Controller;

use App\Entity\ReponseUtilisateur;
use App\Form\ReponseUtilisateurType;
use App\Repository\ReponseUtilisateurRepository;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizImageRepository;
use App\Repository\UtilisateurRepository;
use App\Repository\AnonymeRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface; // Nous appelons le bundle KNP Paginator

/**
 * @Route("/reponse_utilisateur")
 */
class ReponseUtilisateurController extends AbstractController
{
    /**
     * @Route("/quiz", name="reponse_utilisateur_index", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function index(QuestionRepository $questionRepository,ReponseUtilisateurRepository $reponseUtilisateurRepository,ReponseRepository $reponseRepository, PaginatorInterface $paginator, Request $request): Response
    {
        // ----------------On doit récupérer que les réponses qui sont relier aux questionnaires.---------------
        // On commence part récupèrer les questions qui correspond aux questionnaire 
        $questions = $questionRepository->findBy(['Questionnaire' => $request->query->get('id')]);
        if(!empty($questions)){
            // récupère l'id des questions
            foreach($questions as $clef =>$question){
                $idQuestion= $question->getId();   
                $tab_question[$clef]=$idQuestion;
            }
            // afficher les réponses utilisateur part rapport à l'idQuestion
            $reponses = $reponseUtilisateurRepository->findBy(['Question'=>$tab_question]);
//----------// ajouter les types textarea !!
        }
        else {
            $reponses="";
        }
        // Plus d'explication en dessous ;)
        if (!empty($reponses)){
            for($i=0;$i<count($reponses);$i++){ 
                $questionReponse=$reponses[$i]->getReponse();
                $reponseTextarea=$reponses[$i]->getReponseTextarea();
                if( $questionReponse != null){
                    $idReponse = $reponses[$i]->getReponse()->getId();
                    $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
                    $valeur[$i] = $laReponse[0]->getValeurReponse();
                }
                else{
                    $valeur[$i]=2;
                }

            }
        }
        // Si, il n'y a pas de réponses, il n'y a pas de valeur:
        else {
            $valeur="";
        }

        // afficher les réponses du quiz image
        return $this->render('reponse_utilisateur/index.html.twig', [
            'reponse_utilisateurs' => $reponses,
            'valeurReponse'=>$valeur,
            
        ]);
    }
    /*************************************************************** */
     /**
     * @Route("/resultat", name="reponse_utilisateur_resultat", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function resultat( ReponseUtilisateurRepository $reponseUtilisateurRepository,ReponseRepository $reponseRepository, Request $request): Response
    {
        // récupère l'id de la personne connecté :
        if($this->getUser()){
            $utilisateur = $this -> getUser()->getId();
            // afficher que les réponses de l'id Utilisateur connecté:
            $reponses = $reponseUtilisateurRepository->findBy(['Utilisateur' => $utilisateur]);

         }
        else {
            // récupérer l'id Anonyme de la personne qui vient de répondre
            $anonyme = 1 ;
            $reponses = $reponseUtilisateurRepository->findBy(['Anonyme'=> $anonyme]);
        }
        // -----------Savoir si c'est la bonne réponse (pour l'affichage)-----------------
        $valeurVrai=0;
        $totalResultat=0;
        for($i=0;$i<count($reponses);$i++){ 
            $questionReponse=$reponses[$i]->getReponse();
            $reponseTextarea=$reponses[$i]->getReponseTextarea();
            // *******Question/Reponse***************************
            if( $questionReponse != null){
                $idReponse = $reponses[$i]->getReponse()->getId();
                // on veut la valeurReponse, des réponses répondues!
                // chercher l'id de la réponse pour afficher sa valeur
                $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
                // Je dois avoir comme valeur 0 ou 1
                $valeur[$i] = $laReponse[0]->getValeurReponse();
                if($valeur[$i]==1)
                $valeurVrai++;

                $valeurok[$i]="";
                $totalResultat++;
            }
            // *******Question/ReponseTextarea***************************
            elseif($reponseTextarea != null){
                $valeur[$i]=2;
                $quizImage="";
                $valeurok[$i]="";
                $idReponseTextarea = $reponses[$i]->getReponseTextarea();

            }
            else {
                $valeur[$i]="";
            }  
        }
        $resultat=$valeurVrai.'/'.$totalResultat;
   
        return $this->render('reponse_utilisateur/resultat.html.twig', [
            'reponse_utilisateurs' => $reponses,
            'valeurReponse'=>$valeur,
            'resultat'=>$resultat,
            'valeurok'=>$valeurok,
        ]);
    }
    /*************************************************************** */
    /**
     * @Route("/indexQuizImage", name="index_quiz_image", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function indexQuizImage(ReponseUtilisateurRepository $reponseUtilisateurRepository, ReponseRepository $reponseRepository, UtilisateurRepository $utilisateurRepository, AnonymeRepository $anonymeRepository,QuizImageRepository $quizImageRepository, Request $request): Response
    {
        // récupère l'id de la personne connecté :
        if($this->getUser()){
            $utilisateur = $utilisateurRepository->findAll();
            $anonyme = $anonymeRepository->findAll();

            // afficher que les réponses de l'id Utilisateur connecté:
            $reponses = $reponseUtilisateurRepository->findBy(['Utilisateur' => $utilisateur]); 
            $reponsesAnonyme = $reponseUtilisateurRepository->findBy(['Anonyme' => $anonyme]);
        }

        // afficher les images et les réponseImage
        for($i=0;$i<count($reponses);$i++){
           $image[$i]=$reponses[$i]->getImage();
           $reponse[$i]=$reponses[$i]->getReponseImage();
        }

        for($i=0;$i<count($reponsesAnonyme);$i++){
            $imageAnonyme[$i]=$reponsesAnonyme[$i]->getImage();
            $reponseAnonyme[$i]=$reponsesAnonyme[$i]->getReponseImage();
        }
 
        return $this->render('reponse_utilisateur/indexQuizImage.html.twig', [
            // 'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
            'reponse_anonymes' =>$reponsesAnonyme ,
            'reponse_utilisateurs' => $reponses,
            'image'=>$image,
            'reponseQuizImage'=>$reponse,
            'imageAnonyme'=>$imageAnonyme,
            'reponseQuizImageAnonyme'=>$reponseAnonyme,
            'quizImages'=>$quizImageRepository->findAll(),
        ]);
    }
    /*************************************************************** */
    /**
     * @Route("/resultatQuizImage", name="reponse_utilisateur_resultat_quiz_image", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function resultatQuizImage(ReponseUtilisateurRepository $reponseUtilisateurRepository,ReponseRepository $reponseRepository, Request $request): Response
    {
        // récupère l'id de la personne connecté :
        if($this->getUser()){
            $utilisateur = $this -> getUser()->getId();
            // afficher que les réponses de l'id Utilisateur connecté:
            $reponses = $reponseUtilisateurRepository->findBy(['Utilisateur' => $utilisateur]);   
        }
        $valeurVrai=0;
        $total=0;
        // afficher les images et les réponseImage
        for($i=0;$i<count($reponses);$i++){
            $image[$i]=$reponses[$i]->getImage();
            $reponse[$i]=$reponses[$i]->getReponseImage();
            if($reponse[$i]!=null){
                $total++;
                if($image[$i]==$reponse[$i] ){
                        $valeurVrai++;
                }
            }
        }
        $resultat=$valeurVrai.'/'.$total;
        return $this->render('reponse_utilisateur/resultatQuizImage.html.twig', [
            'reponse_utilisateurs' => $reponses,
            'image'=>$image,
            'reponseQuizImage'=>$reponse,
            'resultat'=>$resultat,
        ]);
    }

    /*************************************************************** */
    /**
     * @Route("/compte", name="compte", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function compte(ReponseUtilisateurRepository $reponseUtilisateurRepository, ReponseRepository $reponseRepository,QuizImageRepository $quizImageRepository, Request $request): Response
    {
        // récupère l'id de la personne connecté :
        $utilisateur = $this -> getUser()->getId();

        // afficher que les réponses de l'id Utilisateur connecté:
        $reponses = $reponseUtilisateurRepository->findBy(['Utilisateur' => $utilisateur]);
        
    // -----------Savoir si c'est la bonne réponse (pour l'affichage)-----------------
    $valeurVrai=0;
    $totalResultat=0;
    if (!empty($reponses)){

        for($i=0;$i<count($reponses);$i++){ 
            // si c'est une question reponse
            $questionReponse=$reponses[$i]->getReponse();
            $reponseTextarea=$reponses[$i]->getReponseTextarea();
            // *******Question/Reponse***************************
            if( $questionReponse != null){
                $idReponse = $reponses[$i]->getReponse()->getId();
                // on veut la valeurReponse, des réponses répondues!
                // chercher l'id de la réponse pour afficher sa valeur
                $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
                // Je dois avoir comme valeur 0 ou 1
                $valeur[$i] = $laReponse[0]->getValeurReponse();
                if($valeur[$i]==1)
                $valeurVrai++;

                $quizImage="";
                $valeurok[$i]="";
                $totalResultat++;
            }
            // *******Question/ReponseTextarea***************************
            elseif($reponseTextarea != null){
                $valeur[$i]=2;
                $quizImage="";
                $valeurok[$i]="";
                $idReponseTextarea = $reponses[$i]->getReponseTextarea();

            }
            // *******QuizImage***************************
            else{
                $valeur[$i]="";
                $image[$i]=$reponses[$i]->getImage();
                $imageQuiz=$quizImageRepository->findAll();

                $reponse[$i]=$reponses[$i]->getReponseImage();
                // Voir pour afficher le nom de la réponses et l'image
                    // pour que ça soit plus parlant pour l'utilisateur, à la place des id !!
                $quizImage=$quizImageRepository->find($reponse[$i]);
                
                $totalResultat++;
                if($reponse[$i]!=null){
                    // Si c'est la bonne réponse :
                    if($image[$i]==$reponse[$i] ){
                            $valeurVrai++;
                            $valeurok[$i]=1;
                    }
                    else{
                        $valeurok[$i]=0;
                    }
                }
                
            }
        } 
    }
    else {
        // si, il n'y a pas c'est valeurs, rien mettre dedans!!
        $valeur="";
        $valeurok="";
    }   
    $resultat=$valeurVrai.'/'.$totalResultat;

        return $this->render('utilisateur/compte.html.twig', [
            'reponse_utilisateurs' => $reponses,
            'valeurReponse'=>$valeur,
            'valeurok'=>$valeurok,
            'resultat'=>$resultat,
        ]);
    }
    /*************************************************************** */
    /**
     * @Route("/resultatAnonyme", name="reponse_utilisateur_anonyme")
     */
    public function anonyme(Request $request)
    {
        return $this->render('reponse_utilisateur/anonyme.html.twig');
    }
    /*************************************************************** */
    /**
     * @Route("/new", name="reponse_utilisateur_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reponseUtilisateur = new ReponseUtilisateur();
        $form = $this->createForm(ReponseUtilisateurType::class, $reponseUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reponseUtilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('reponse_utilisateur_index');
        }

        return $this->render('reponse_utilisateur/new.html.twig', [
            'reponse_utilisateur' => $reponseUtilisateur,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="reponse_utilisateur_show", methods={"GET"})
     */
    public function show(ReponseUtilisateur $reponseUtilisateur): Response
    {
        return $this->render('reponse_utilisateur/show.html.twig', [
            'reponse_utilisateur' => $reponseUtilisateur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reponse_utilisateur_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ReponseUtilisateur $reponseUtilisateur): Response
    {
        $form = $this->createForm(ReponseUtilisateurType::class, $reponseUtilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reponse_utilisateur_index');
        }

        return $this->render('reponse_utilisateur/edit.html.twig', [
            'reponse_utilisateur' => $reponseUtilisateur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reponse_utilisateur_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ReponseUtilisateur $reponseUtilisateur): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponseUtilisateur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reponseUtilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reponse_utilisateur_index');
    }
    


}
