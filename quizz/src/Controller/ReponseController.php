<?php

namespace App\Controller;

use App\Entity\Reponse;
use App\Entity\Question;
use App\Form\ReponseType;
use App\Repository\ReponseRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuestionnaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * @Route("/reponse")
 */
class ReponseController extends AbstractController
{
    /**
     * @Route("/", name="reponse_index", methods={"GET"})
     */
    public function index( QuestionnaireRepository $questionnaireRepository, ReponseRepository $reponseRepository, QuestionRepository $questionRepository, Request $request): Response
    {
        // rechercher plusieurs objets reponse correspondant au question
        // dump($request->query->get('id'));

        $reponses = $reponseRepository->findBy(['question' => $request->query->get('id')]);
        dump($reponses);

        $questions=$questionRepository->findBy(['id' => $request->query->get('id')]);
        dump($questions[0]->getQuestionnaire()->getId());


        return $this->render('reponse/index.html.twig', [
            // Afficher que les réponses par rapport à son IdQuestion!!!
            'questions' => $questions[0]->getQuestionnaire()->getId(),
            // Je chercher l'id questionnaire par rapport à l'id de la question (ex: id=4)
            'questionnaires' => $questionnaireRepository->findAll(),
            'reponses' => $reponses,

        ]);
    }
// ********************************************************************** */
    /**
     * @Route("/new", name="reponse_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Il est responsable de l'enregistrement des objets et de leur récupération dans la base de données.
            $entityManager = $this->getDoctrine()->getManager();
            //  L' persist($reponse) appel dit à Doctrine de "gérer" l' $reponse objet.
            $entityManager->persist($reponse);
            // Doctrine examine tous les objets qu'elle parvient à voir s'ils doivent être conservés dans la base de données.
            $entityManager->flush();

            return $this->redirectToRoute('reponse_new');
        }

        return $this->render('reponse/new.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }

//********************************************************************** */
    /**
     * @Route("/{id}", name="reponse_show", methods={"GET"})
     */
    public function show(Reponse $reponse): Response
    {
        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }
//********************************************************************** */
    /**
     * @Route("/{id}/edit", name="reponse_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reponse $reponse, ReponseRepository $reponseRepository): Response
    {
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_admin');
        }


        return $this->render('reponse/edit.html.twig', [
            'reponse' => $reponse,
            'form' => $form->createView(),
        ]);
    }
//********************************************************************** */
    /**
     * @Route("/{id}", name="reponse_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reponse $reponse): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reponse->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reponse);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_admin');
    }
}
