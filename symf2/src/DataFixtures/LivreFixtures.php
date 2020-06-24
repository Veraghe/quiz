<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Bibliotheque;

class LivreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for($i=1; $i<=10;$i++){
            $livre = new Bibliotheque();
            $livre  ->setTitre("Titre du livre n°$i")
                    ->setAuteur("L'auteur du livre n°$i")
                    ->setCategorie("La catégorie du livre n°$i")
                    ->setDescription("<p>Résumé du livre n°$i: Lorem ipsum dolor sit amet consectetur adipisicing elit. Odit odio harum obcaecati earum illum doloremque sunt necessitatibus, dicta esse quos consectetur non laboriosam doloribus minus placeat similique aut. Commodi dolorum doloribus esse est veniam quisquam rem praesentium velit asperiores, aut hic autem ad sequi itaque harum sed quis deleniti neque!</p>");
            $manager->persist($livre);
        }
        $manager->flush();
        
    }
}
