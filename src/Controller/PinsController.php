<?php

namespace App\Controller;

use App\Entity\Pin;
use App\Form\PinType;
use App\Repository\PinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    
    /**
     * @Route("/", name="app_home", methods="GET")
     */
    public function index(PinRepository $pinRepository): Response
    {
        $pins = $pinRepository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    /**
     * @Route("/pins/{slug}", name="app_pins_show", methods="GET")
     */
    public function show(Pin $pin): Response
    {
        
        return $this->render('pins/show.html.twig', compact('pin'));
    }

    /**
     * @Route("/pins/create", name="app_pins_create", methods={"GET", "POST"}, priority="1")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        
        $pin = new Pin;
        $form = $this->createForm(PinType::class, $pin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pin->setUser($this->getUser());
            $em->persist($pin);
            $em->flush();

            $this->addFlash('success', 'Pin Created! Knowledge is power!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/edit", name="app_pins_edit", methods={"GET", "PUT"})
     */
    public function edit(Request $request, Pin $pin, EntityManagerInterface $em): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(PinType::class, $pin, [
            'method' =>'PUT'
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Pin Updated! Knowledge is power!');


            return $this->redirectToRoute('app_home');
        }

        return $this->render('pins/create.html.twig', [
            'form' => $form->createView(),
            'pin' => $pin
        ]);
    }

    /**
     * @Route("/pins/{id<[0-9]+>}/delete", name="app_pins_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Pin $pin, EntityManagerInterface $em)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        if ($this->isCsrfTokenValid('pin_deletion_' . $pin->getId(), $request->request->get('csrf_token'))) {
            $em->remove($pin);
            $em->flush();

            $this->addFlash('info', 'Pin successfully deleted!');

        }

        return $this->redirectToRoute('app_home');
    }

}
 