<?php

namespace App\Controller;

use App\Entity\ReponseUtilisateur;
use App\Form\ReponseUtilisateurType;
use App\Repository\ReponseUtilisateurRepository;
use App\Repository\ReponseRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
    public function index(ReponseUtilisateurRepository $reponseUtilisateurRepository,ReponseRepository $reponseRepository, Request $request): Response
    {
        // On récupère toutes les réponses
        $reponses = $reponseUtilisateurRepository->findAll();
        // Plus desplication en dessous ;)
        for($i=0;$i<count($reponses);$i++){ 
            $idReponse = $reponses[$i]->getReponse()->getId();
            $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
            $valeur[$i] = $laReponse[0]->getValeurReponse();
        }
        return $this->render('reponse_utilisateur/index.html.twig', [
            'valeurReponse'=>$valeur,
            'reponse_utilisateurs' => $reponses,
            
        ]);
    }

     /**
     * @Route("/resultat", name="reponse_utilisateur_resultat", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function resultat(ReponseUtilisateurRepository $reponseUtilisateurRepository,ReponseRepository $reponseRepository, Request $request): Response
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
            $anonyme = 1;
            $reponses = $reponseUtilisateurRepository->findBy(['Anonyme'=> $anonyme]);
        }
        // -----------Savoir si c'est la bonne réponse (pour l'affichage)-----------------
        for($i=0;$i<count($reponses);$i++){ 
            $idReponse = $reponses[$i]->getReponse()->getId();
        //    dump($idReponse);
            // on veut la valeurReponse, des réponses répondues!
            // chercher l'id de la réponse pour afficher sa valeur
            $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
            // dump($laReponse);
            // Je dois avoir comme valeur 0 ou 1
            $valeur[$i] = $laReponse[0]->getValeurReponse();
            // dump($valeur);
        }
        return $this->render('reponse_utilisateur/resultat.html.twig', [
            // 'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
            'reponse_utilisateurs' => $reponses,
            'valeurReponse'=>$valeur,
        ]);
    }

         /**
     * @Route("/compte", name="compte", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function compte(ReponseUtilisateurRepository $reponseUtilisateurRepository, ReponseRepository $reponseRepository, Request $request): Response
    {
        // récupère l'id de la personne connecté :
        $utilisateur = $this -> getUser()->getId();

        // afficher que les réponses de l'id Utilisateur connecté:
        $reponses = $reponseUtilisateurRepository->findBy(['Utilisateur' => $utilisateur]);
       dump($reponses);
    //    dump(count($reponses));
    // -----------Savoir si c'est la bonne réponse (pour l'affichage)-----------------
    for($i=0;$i<count($reponses);$i++){ 
        $idReponse = $reponses[$i]->getReponse()->getId();
    //    dump($idReponse);
        // on veut la valeurReponse, des réponses répondues!
        // chercher l'id de la réponse pour afficher sa valeur
        $laReponse = $reponseRepository->findBy(['id'=>$idReponse]);
        // dump($laReponse);
        // Je dois avoir comme valeur 0 ou 1
        $valeur[$i] = $laReponse[0]->getValeurReponse();
        // dump($valeur);
    }

        return $this->render('utilisateur/compte.html.twig', [
            'reponse_utilisateurs' => $reponses,
            'valeurReponse'=>$valeur,
        ]);
    }

    // /**
    //  * @Route("/reponseQuiz", name="reponse_utilisateur_quiz")
    //  * @param ReponseUtilisateur $reponseUtilisateur
    //  * @return Response
    //  */
    // public function quiz(Request $request): Response
    // {
    //     $quiz= new ReponseUtilisateur();

    //     $form =$this->createFormBuilder($quiz)
    //                 ->add('reponse')
    //                 ->add('utilisateur')
    //                 ->getForm();

    //     // $form->handleRequest($request);
    //     // dump($quiz);
    //     return $this->render('reponse_utilisateur/quiz.html.twig',[
    //         'formReponseUtilisateur'=> $form->createView(),
    //     ]);
    // }

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
    

    // /**
    //  * @Route("/action", name="reponse_utilisateur_action", methods={"POST"})
    //  */
    // public function action(Request $request, ReponseUtilisateur $reponseUtilisateur): Response
    // {

    //     $defaultData = ['question' => 'Ici la question'];
    //     $formRU = $this->createFormBuilder($defaultData)
    //         ->add('email')

    //         ->add('reponses');

    //   $formRU->handleRequest($request);


    //     if ($formRU->isSubmitted() && $formRU->isValid()) {
    //         //les données sont un tableau "utilisateur" et "reponse"
    //         $data = $formRU->getData();
    //     }

    //     return $this->render('question/quiz.html.twig', [
    //         'reponse_utilisateur' => $reponseUtilisateur,
    //         'formRU' => $formRU->createView(),
    //     ]);
    // }



}
