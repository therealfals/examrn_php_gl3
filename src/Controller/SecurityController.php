<?php

namespace App\Controller;

use App\Entity\DemandeurEmploi;
use App\Entity\Entreprise;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private  $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder =$encoder;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
          if ($this->getUser()) {
              if ($this->getUser()->getRoles()[0]=='ROLE_DEMANDEUREMPLOI'){
                  return $this->redirectToRoute('all_emplois');

              }else{
                  return $this->redirectToRoute('my_emplois_entreprise');

              }
          }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request,UserRepository $userRepository)
    {
        if($request->isMethod('post')){
            $userData = $request->request->all();
            if($userData['type']=='demandeurEmploi'){
                $user=new DemandeurEmploi();
                $user->setProfil('DemandeurEmploi');
            }else{
                $user=new Entreprise();
                $user->setProfil('Entreprise');

            }
             $image  = $request->files->get("avatar");

            if ($image){
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $user->setAvatar($fichier);
            }
            $user->setEmail($userData['email']);
            $user->setNomComplet($userData['nomComplet']);
             $password=$this->encoder->encodePassword($user,$userData['password']);
            $user->setPassword($password);
            $user->setTelephone($userData['telephone']);
            $user->setAdresse($userData['adresse']);
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

        }

        return $this->render('security/register.html.twig');
    }
}
