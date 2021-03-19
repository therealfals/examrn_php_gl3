<?php
namespace App\Controller;

use App\Entity\Emploi;
use App\Repository\CategorieRepository;
use App\Repository\CVRepository;
use App\Repository\EmploiRepository;
use App\Service\DefaultService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EmploiController extends AbstractController{
    private $emploiRepository;
    private $paginator;
    private  $categorieRepository;
    private $defaultService;
    private $mailer;
    public function __construct(\Swift_Mailer $mailer,DefaultService $defaultService,EmploiRepository $emploiRepository, PaginatorInterface $paginator, CategorieRepository $categorieRepository)
    {
        $this->defaultService=$defaultService;
        $this->emploiRepository=$emploiRepository;
        $this->paginator=$paginator;
        $this->mailer=$mailer;
        $this->categorieRepository= $categorieRepository;
    }
    /**
     * @Route("/emploi/create", name="create_emploi")
     */
    public function addEmploi(Request $request,CategorieRepository $categorieRepository,CVRepository $cvRe){
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');
            $entreprise=$this->getUser();
        $categories=$categorieRepository->findAll();
        if($request->isMethod('post')){
            $emploiData = $request->request->all();
             $image  = $request->files->get("avatar");
            $emploi = new Emploi();
            if (!empty($emploiData['categorie'])){
                $cat=$categorieRepository->find($emploiData['categorie']);
                 $emploi->setCategorie($cat);
                $cvs=$cat->getCv();
                    //$cvRe->findBy(["categories"=>$emploiData['categorie']]);
                if(count($cvs)>0){

                    foreach ($cvs as $cv){

                         $message = (new \Swift_Message('Nouvelle offre d\'emploi postée'))
                            ->setFrom('sac3g8@gmail.com')
                            ->setTo($cv->getEmail())
                            ->setBody(
                                "<h3>Type: ".$emploiData['libelle']."<br>Contact de l'entreprise: ".$entreprise->getEmail()."</h3><p>Description: ".$emploiData['description']."</p>"
                                ,
                                'text/html'
                            )
                        ;
                        $this->mailer->send($message);
                    }
                }
            }
            if(!empty($emploiData['salaire'])){
                if ($emploiData['salaire']>0){
                    $emploi->setSalaire((int)$emploiData['salaire']);
                }
            }
            $emploi->setLibelle($emploiData['libelle']);
            $emploi->setDescription($emploiData['description']);
            if ($image){
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $emploi->setAvatar($fichier);
            }
            $emploi->setEntreprise($this->getUser());
            $em=$this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();
         }
        return $this->render("emploi/create.html.twig",[
            "categories"=>$categories
        ]);
        }


    /**
     * @Route("/emploi/update/{id}", name="update_emploi")
     */
    public function updateEmploi($id,Request $request,CategorieRepository $categorieRepository,EmploiRepository $emploiRepository){
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $emploi=$emploiRepository->findOneBy(["id"=>$id,"entreprise"=>$this->getUser()->getId()]);
        if (!$emploi){
            return $this->redirectToRoute('my_emplois_entreprise');
        }
        $categories=$categorieRepository->findAll();
        if($request->isMethod('post')){;
            $emploiData = $request->request->all();
              $image  = $request->files->get("avatar");
            $emploi->setCategorie($categorieRepository->find($emploiData['categorie']));
            if(!empty($emploiData['salaire'])){
                if ($emploiData['salaire']>0){
                    $emploi->setSalaire((int)$emploiData['salaire']);
                }
            }
            $emploi->setLibelle($emploiData['libelle']);
            $emploi->setDescription($emploiData['description']);
            if ($image){
                $fichier = md5(uniqid()).'.'.$image->guessExtension();
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $emploi->setAvatar($fichier);
            }
            $emploi->setEntreprise($this->getUser());
            $em=$this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();
            return $this->redirectToRoute('my_emplois_entreprise');

        }
        return $this->render("emploi/update.html.twig",[
            "categories"=>$categories,
            "emploi"=>$emploi
        ]);
        }

    /**
     * @Route("/emploi/entreprise/list", name="my_emplois_entreprise")
     */
    public function myEmplois(Request $request){
        $this->denyAccessUnlessGranted('ROLE_ENTREPRISE');

        $donnees = $this->emploiRepository->findBy(["entreprise"=>$this->getUser()->getId(),"isDeleted"=>false]);
        $emplois = $this->paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3// Nombre de résultats par page
        );
        return $this->render('emploi/my_emplois_entreprise.html.twig', ['emplois' => $emplois]);

    }
    /**
     * @Route("/", name="app_home")
     */
    public function home(Request $request){

        return $this->redirectToRoute('all_emplois');

    }
    /**
     * @Route("/emploi/details/{id}", name="detail_emploi")
     */
    public function detailEmploi(EmploiRepository $emploiRepository, $id){
        $emploi=$emploiRepository->find($id);
        return $this->render('emploi/details_emploi.html.twig', ['emploi' => $emploi]);
    }
    /**
     * @Route("/emploi/list", name="all_emplois")
     */
    public function allEmplois(Request $request){
        $categories=$this->categorieRepository->findAll();
       $cat=$request->query->get('categorie' );
       if($cat){
           $donnees = $this->emploiRepository->findBy(["isDeleted"=>false,"categorie"=>$cat]);

       }else{
           $donnees = $this->emploiRepository->findBy(["isDeleted"=>false]);

       }
        $emplois = $this->paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            3// Nombre de résultats par page
        );
        return $this->render('emploi/all_emplois.html.twig', [
            'emplois' => $emplois,
            'categories'=>$categories
        ]);

    }
    /**
     * @Route("/emploi/delete", name="delete_emploi")
     */
    public function deleteEmploi(){
        $emploi = $this->emploiRepository->find($_POST['id']);
        if ($emploi){
            $emploi->setIsDeleted(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();
            return new JsonResponse('ok',200);
        }
     }
    /**
     * @Route("/emploi/postuler", name="delete_emploi")
     */
    public function postulerEmploi(\Swift_Mailer $mailer, CVRepository $CVRepository){
        $this->denyAccessUnlessGranted('ROLE_DEMANDEUREMPLOI');

        $emploi = $this->emploiRepository->find($_POST['id']);
        $cv= $CVRepository->find($this->getUser()->getCv()->getId());
             // $this->defaultService->sendMail($emploi->getEntreprise()->getEmail(),"erfgh","demande emploi",$this->mailer);
            $message = (new \Swift_Message('Demande d\'emploi'))
                ->setFrom('sac3g8@gmail.com')
                ->setTo($this->getUser()->getEmail())
                ->setBody(
                    $this->renderView('emploi/postuler.html.twig',[
                        "cv"=>$cv
                    ])
                     ,
                    'text/html'
                )
            ;
              $mailer->send($message);
             return new JsonResponse('ok',200);
        }
 }
