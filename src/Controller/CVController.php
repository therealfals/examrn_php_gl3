<?php

namespace App\Controller;

use App\Entity\CV;
use App\Form\CVType;
use App\Repository\CVRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cv")
 */
class CVController extends AbstractController
{
    /**
     * @Route("/", name="c_v_index", methods={"GET"})
     */
    public function index(CVRepository $cVRepository): Response
    {
        return $this->render('cv/index.html.twig', [
            'c_vs' => $cVRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="c_v_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cV = new CV();
        $form = $this->createForm(CVType::class, $cV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
             $cV->setDemandeurEmploi($this->getUser());
            $entityManager->persist($cV);
            $entityManager->flush();

            return $this->redirectToRoute('c_v_show');
        }

        return $this->render('cv/new.html.twig', [
            'c_v' => $cV,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/view", name="c_v_show", methods={"GET"})
     */
    public function show(  CVRepository $CVRepository): Response
    {
        if ($this->getUser()->getCv()){
            $cv=$CVRepository->find($this->getUser()->getCv()->getId());

        }else{
            $cv= null;
        }

        return $this->render('cv/show.html.twig', [
            'c_v' => $cv,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="c_v_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CV $cV): Response
    {
        $form = $this->createForm(CVType::class, $cV);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('c_v_index');
        }

        return $this->render('cv/edit.html.twig', [
            'c_v' => $cV,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="c_v_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CV $cV): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cV->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cV);
            $entityManager->flush();
        }

        return $this->redirectToRoute('c_v_index');
    }
}
