<?php

namespace App\Controller;

use App\Entity\Donate;
use App\Form\DonateType;
use App\Repository\DonateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/donate')]
#[IsGranted('ROLE_RESTAURANT')]
class DonateController extends AbstractController
{

    #[Route('/all', name: 'read_donate')]
    public function index(DonateRepository $donateRepository): Response
    {
        $restaurant = $this->getUser(); 
        $donates = $donateRepository->findBy(['restaurant' => $restaurant]);
    
        return $this->render('donate/index.html.twig', [
            'donates' => $donates,
        ]);
    }

    #[Route('/create', name: 'create_donate')]
    public function create(Request $request, EntityManagerInterface $manager): Response
    {
        $donate = new Donate();
        $form = $this->createForm(DonateType::class, $donate);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $donate->setRestaurant($this->getUser());

            $picture = $form->get('picture')->getData();
            $pictureSrc = date('YmdHis') . '-' . $picture->getClientOriginalName();


            $donate->setPicture($pictureSrc);
            $picture->move($this->getParameter('upload'), $pictureSrc);

            $manager->persist($donate);
            $manager->flush();

            $this->addFlash('success', 'Opération réalisée avec succès');

            return $this->redirectToRoute('read_donate');
        }

        return $this->render('donate/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update/{id}', name: 'update_donate')]
    public function update_donate(Donate $donate, Request $request, EntityManagerInterface $manager): Response
    {

        
        $form = $this->createForm(DonateType::class, $donate);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $picture = $form->get('picture')->getData();

            if ($picture) {

                $pictureSrc = date('YmdHis') . '-' . $picture->getClientOriginalName();


                unlink($this->getParameter('upload') . '/' . $donate->getPicture());


                $donate->setPicture($pictureSrc);


                $picture->move($this->getParameter('upload'), $pictureSrc);
            }


            $manager->persist($donate);
            $manager->flush();


            $this->addFlash('success', 'Opération réalisée avec succès');


            return $this->redirectToRoute('read_donate');
        }


        return $this->render('donate/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/delete/{id}', name: 'delete_donate')]
    public function delete_donate(Donate $donate, EntityManagerInterface $manager): Response
    {

        $pictureSrc = $donate->getPicture();


        unlink($this->getParameter('upload') . '/' . $pictureSrc);


        $manager->remove($donate);
        $manager->flush();


        $this->addFlash('success', 'Donation supprimée');


        return $this->redirectToRoute('read_donate');
    }
}
