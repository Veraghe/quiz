<?php

namespace App\Controller;

use App\Entity\ReponseUtilisateur;
use App\Form\ReponseUtilisateurType;
use App\Repository\ReponseUtilisateurRepository;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizImageRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
        // dump($questions);
        // dump($questions);
        if(!empty($questions)){
            // récupère l'id des questions
            foreach($questions as $clef =>$question){
                $idQuestion= $question->getId();    
                // dump($idQuestion); 
                $tab_question[$clef]=$idQuestion;
                // dump($tab_question);
            }
            // afficher les réponses utilisateur part rapport à l'idQuestion
            $reponses = $reponseUtilisateurRepository->findBy(['Question'=>$tab_question]);
//----------// ajouter les types textarea !!
            // dump($reponses);
        }
        else {
            $reponses="";
        }
        // Plus d'explication en dessous ;)
        if (!empty($reponses)){
            for($i=0;$i<count($reponses);$i++){ 
                $questionReponse=$reponses[$i]->getReponse();
                $reponseTextarea=$reponses[$i]->getReponseTextarea();
                // dump($reponseTextarea);
                if( $questionReponse != null){
                    $idReponse = $reponses[$i]->getReponse()->getId();
                    // dump($idReponse);
                    $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
                    // dump($laReponse);
                    $valeur[$i] = $laReponse[0]->getValeurReponse();
                }
                // // *******Question/ReponseTextarea***************************
                else{
                    $valeur[$i]=2;
                }

            }
        }
        // Si, il n'y a pas de réponses, il n'y a pas de valeur:
        else {
            $valeur="";
        }
        // $reponsePagination=$paginator->paginate(
        //     $reponses,
        //     $request->query->getInt('page',1),
        //     15
        // );
        // dump($reponsePagination);
        // dump($valeur);

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
            // dump($utilisateur);
            // afficher que les réponses de l'id Utilisateur connecté:
            $reponses = $reponseUtilisateurRepository->findBy(['Utilisateur' => $utilisateur]);
            // dump($reponses);

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
            dump($questionReponse);
            $reponseTextarea=$reponses[$i]->getReponseTextarea();
            // *******Question/Reponse***************************
            if( $questionReponse != null){
            // if( $reponses == null){
                $idReponse = $reponses[$i]->getReponse()->getId();
            //    dump($idReponse);
                // on veut la valeurReponse, des réponses répondues!
                // chercher l'id de la réponse pour afficher sa valeur
                $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
                // dump($laReponse);
                // Je dois avoir comme valeur 0 ou 1
                $valeur[$i] = $laReponse[0]->getValeurReponse();
                // dump($valeur);
                if($valeur[$i]==1)
                $valeurVrai++;

                $valeurok[$i]="";
                $totalResultat++;
            }
            // *******Question/ReponseTextarea***************************
            elseif($reponseTextarea != null){
                $valeur[$i]=2;
                // $image="";
                $quizImage="";
                $valeurok[$i]="";
                $idReponseTextarea = $reponses[$i]->getReponseTextarea();
                // dump($idReponseTextarea);

            }
            else {
                $valeur[$i]="";
            }  
        }
        $resultat=$valeurVrai.'/'.$totalResultat;
   
        return $this->render('reponse_utilisateur/resultat.html.twig', [
            // 'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
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
    public function indexQuizImage(ReponseUtilisateurRepository $reponseUtilisateurRepository,ReponseRepository $reponseRepository, UtilisateurRepository $utilisateurRepository,QuizImageRepository $quizImageRepository, Request $request): Response
    {
        // récupère l'id de la personne connecté :
        if($this->getUser()){
            $utilisateur = $utilisateurRepository->findAll();
            dump($utilisateur);
            // afficher que les réponses de l'id Utilisateur connecté:
            $reponses = $reponseUtilisateurRepository->findBy(['Utilisateur' => $utilisateur]);   
            dump($reponses);
        }
        // $reponses=5;
        $valeurVrai=0;
        $total=0;
        // afficher les images et les réponseImage
        for($i=0;$i<count($reponses);$i++){
           
                $image[$i]=$reponses[$i]->getImage();
                $reponse[$i]=$reponses[$i]->getReponseImage();
                // dump($image);

            if($reponses[$i]!=null){
                $afficheImage=$quizImageRepository->findBy(['image'=> $reponses]);
                dump($afficheImage);
            }
            if($reponse[$i]!=null){
                $total++;
                if($image[$i]==$reponse[$i] ){
                        $valeurVrai++;
                }
            }
        }
        $resultat=$valeurVrai.'/'.$total;
        // voir pour afficher les images au lieu des chiffres
        // dump($image);
        // $afficheImage= $quizImageRepository->findBy();
        return $this->render('reponse_utilisateur/indexQuizImage.html.twig', [
            // 'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
            'reponse_utilisateurs' => $reponses,
            'image'=>$image,
            'reponseQuizImage'=>$reponse,
            'resultat'=>$resultat,
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
            // 'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
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
    //    dump($reponses);
      
    //    dump(count($reponses));
    // -----------Savoir si c'est la bonne réponse (pour l'affichage)-----------------
    $valeurVrai=0;
    $totalResultat=0;
    if (!empty($reponses)){
        // dump($reponses);

        for($i=0;$i<count($reponses);$i++){ 
            // si c'est une question reponse
            $questionReponse=$reponses[$i]->getReponse();
            $reponseTextarea=$reponses[$i]->getReponseTextarea();
            // *******Question/Reponse***************************
            if( $questionReponse != null){
                $idReponse = $reponses[$i]->getReponse()->getId();
                // dump($idReponse);
                // on veut la valeurReponse, des réponses répondues!
                // chercher l'id de la réponse pour afficher sa valeur
                $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
                // dump($laReponse);
                // Je dois avoir comme valeur 0 ou 1
                $valeur[$i] = $laReponse[0]->getValeurReponse();
                if($valeur[$i]==1)
                $valeurVrai++;

                // $image="";
                $quizImage="";
                $valeurok[$i]="";
                $totalResultat++;
            }
            // *******Question/ReponseTextarea***************************
            elseif($reponseTextarea != null){
                $valeur[$i]=2;
                // $image="";
                $quizImage="";
                $valeurok[$i]="";
                $idReponseTextarea = $reponses[$i]->getReponseTextarea();
                // dump($idReponseTextarea);

            }
            // *******QuizImage***************************
            else{
                $valeur[$i]="";
                
                $image[$i]=$reponses[$i]->getImage();
                dump($image[$i]);

                $imageQuiz=$quizImageRepository->findAll();
                dump($imageQuiz);

                $reponse[$i]=$reponses[$i]->getReponseImage();
                dump($reponse[$i]);
                // Voir pour afficher le nom de la réponses et l'image
                    // por que ça soit plus parlant pour l'utilisateur, à la place des id !!
                $quizImage=$quizImageRepository->find($reponse[$i]);
                dump($quizImage);
                
                $totalResultat++;
                if($reponse[$i]!=null){
                    // Si c'est la bonne réponse :
                    if($image[$i]==$reponse[$i] ){
                            $valeurVrai++;
                            $valeurok[$i]=1;
                            // dump($valeurok);
                    }
                    else{
                        $valeurok[$i]=0;
                    }
                }
                
            }

            // dump($valeur);
        } 
    }
    else {
        // si, il n'y a pas c'est valeurs, rien mettre dedans!!
        $valeur="";
        $valeurok="";
        // $image="";
        // $quizImage="";
    }   
    $resultat=$valeurVrai.'/'.$totalResultat;
    // dump($resultat);

        return $this->render('utilisateur/compte.html.twig', [
            'reponse_utilisateurs' => $reponses,
            'valeurReponse'=>$valeur,
            'valeurok'=>$valeurok,
            'resultat'=>$resultat,
            // 'image'=>$image,
            // 'reponseQuizImages'=>$quizImage,
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
