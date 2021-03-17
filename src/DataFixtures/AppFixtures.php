<?php

namespace App\DataFixtures;

use App\Entity\DemandeurEmploi;
use App\Entity\Entreprise;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i=0; $i<5; $i++){
            if($i/2 !=0){
                $demandeur = new DemandeurEmploi();
                $demandeur->setProfil('DemandeurEmploi');
                $demandeur->setEmail('emaildemandeur'.($i+1).'@gmail.com');
                $password = $this->encoder->encodePassword($demandeur,'password');
                $demandeur->setPassword($password);
                $demandeur->setNomComplet('Demandeur '.($i+1));
                $manager->persist($demandeur);
            }else{
                $entreprise = new Entreprise();
                $entreprise->setProfil('DemandeurEmploi');
                $entreprise->setEmail('emailentreprise'.($i+1).'@gmail.com');
                $password = $this->encoder->encodePassword($entreprise,'password');
                $entreprise->setPassword($password);
                $entreprise->setNomComplet('Entreprise '.($i+1));
                $manager->persist($entreprise);
            }
        }
        $manager->flush();
    }
}
