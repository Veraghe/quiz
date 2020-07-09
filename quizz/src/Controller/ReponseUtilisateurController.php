<?php

namespace App\Controller;

use App\Entity\ReponseUtilisateur;
use App\Form\ReponseUtilisateurType;
use App\Repository\ReponseUtilisateurRepository;
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
    public function index(ReponseUtilisateurRepository $reponseUtilisateurRepository): Response
    {
        return $this->render('reponse_utilisateur/index.html.twig', [
            'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
        ]);
    }

     /**
     * @Route("/resultat", name="reponse_utilisateur_resultat", methods={"GET"})
     * @param ReponseUtilisateur $reponseUtilisateur
     * @return Response
     */
    public function resultat(ReponseUtilisateurRepository $reponseUtilisateurRepository, Request $request): Response
    {
        // récupère l'id de la personne connecté :
        $utilisateur = $this -> getUser();
        dump($utilisateur);
        // afficher que les réponses de l'id Utilisateur connecé:
        $reponses = $reponseUtilisateurRepository->findBy(['id' => $request->query->get('Reponse')]);
        dump($reponses);
        return $this->render('reponse_utilisateur/resultat.html.twig', [
            // 'reponse_utilisateurs' => $reponseUtilisateurRepository->findAll(),
            'reponse_utilisateurs' => $reponses,
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
