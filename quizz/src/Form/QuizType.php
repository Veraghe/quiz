<?php

use Symfony\Component\HttpFoundation\Request;

class QuizType extends AbstractType
{
    public function contact(Request $request)
    {
    $defaultData = ['question' => 'Tapez votre question ici'];
    $form = $this->createFormBuilder($defaultData)
        ->add('utilisateur')
        ->add('reponse')
        ->add('valider', SubmitType::class)
        ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        //les données sont un tableau "utilisateur" et "reponse"
        $data = $form->getData();
    }

    // ... render the form
    }
}