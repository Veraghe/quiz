<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


 /**
  * Require ROLE_ADMIN for *every* controller method in this class.
  *
  * @IsGranted("ROLE_ADMIN")
  */

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

     /**
      * Require ROLE_ADMIN for only this controller method.
      *
      * @IsGranted("ROLE_ADMIN", message="Si vous n'êtes pas administrateur, vous ne pouvez pas aller sur cette page !")
      */
    public function adminDashboard()
    {
    $this->denyAccessUnlessGranted('ROLE_ADMIN');

    // or add an optional message - seen by developers
    $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'User tried to access a page without having ROLE_ADMIN');
    }

    /**
     * @Route("/reponseUtilisateur", name="reponse_utilisateur")
     */
    public function reponse()
    {
        return $this->render('admin/reponse.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }



}
