<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SudmitType;
// use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Bibliotheque;
use App\Repository\BibliothequeRepository;

class BibliothequeController extends AbstractController
{
    //FONCTION: Bibliothèque-----------------------------------------
    /**
     * @Route("/bibliotheque", name="bibliotheque")
     */
    public function index(BibliothequeRepository $repo)
    {
        // $repo=$this->getDoctrine()->getRepository(Bibliotheque::class); //Plus besoin de cette ligne comme on entre un repository !!
        $livres=$repo->findAll(); // recherche * all -> tous * les objets produits
        return $this->render('bibliotheque/livre.html.twig', [
            'controller_name' => 'BibliothequeController',
            'livres'=>$livres
        ]);
    }

    //FONCTION: Accueil----------------------------------------------
    /**
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('bibliotheque/home.html.twig');
    }

    //FONCTION Ajouter un livre: -----------------------------------
    //Injection de dépendances (Symfony peut fournir les éléments dont on a besoin)
    /**
     * @Route("/bibliotheque/newLivre", name="bibliotheque_create")
     */
    public function create(Request $request){ //HttpFoundation\Request (C'est la classe qui permet d'analyser/manipuler la requête HTTP)
        $livre= new Bibliotheque(); //, ObjectManager $manager

        $form =$this->createFormBuilder($livre)
                    ->add('titre',TextType::class,[
                        'attr' => [
                            'placeholder' => 'Titre du livre'
                        ]
                    ])
                    ->add('auteur',TextType::class,[
                        'attr' => [
                            'placeholder' => 'L\'auteur du livre'
                        ]
                    ])
                    ->add('categorie',TextType::class,[
                        'attr' => [
                            'placeholder' => 'La catégorie du livre'
                        ]
                    ])
                    ->add('description',TextareaType::class,[
                        'attr' => [
                            'placeholder' => 'La description du livre'
                        ]
                    ])
                    ->getForm();

        return $this->render('bibliotheque/create.html.twig', [
            'formLivre' => $form->createView()
        ]);
    }

    //FONCTION: Un livre de la Bibliothèque---------------------------
    /**
     * @Route("/bibliotheque/{id}", name="bibliotheque_show")
     */
    // public function show(BibliothequeRepository $repo, $id){ //On a besoin de récupérer l'ID pour afficher chaque livre et l'injection de dépendance
    //     $livre=$repo->find($id);
    //     return $this->render('bibliotheque/show.html.twig',[
    //         'livre'=>$livre
    //     ]);
    // }
    //Pour raccourcir la function 'show' -> ParamConverter
        public function show(Bibliotheque $livre){  //Comme la route utilise un ID, la fonction c'est qu'elle doit montrer un livre
        return $this->render('bibliotheque/show.html.twig',[
            'livre'=>$livre
        ]);
    }


}
