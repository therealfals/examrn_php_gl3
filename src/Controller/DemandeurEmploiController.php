<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DemandeurEmploiController extends AbstractController{
    private  $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder =$encoder;
    }

    /**
     * @Route("/cv/create", name="create_cv")
     */
    public function createCv(Request $request){
        $this->denyAccessUnlessGranted('ROLE_DEMANDEUREMPLOI');

        return $this->render("demandeur_emploi/create_cv.html.twig");

    }
}
