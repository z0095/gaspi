<?php
namespace App\Controller;

use App\Repository\DonateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/my/donations')]
class MyDonationsController extends AbstractController
{
    #[Route('/', name: 'app_my_donations')]
    public function index(DonateRepository $donateRepository): Response
    {
        
        $user = $this->getUser();

        $donations = $donateRepository->findBy(['restaurant' => $user]);

        return $this->render('my_donations/index.html.twig', [
            'donations' => $donations,
        ]);
    }
}
