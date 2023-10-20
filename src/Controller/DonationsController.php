<?php

namespace App\Controller;

use App\Service\CartService;
use App\Repository\DonateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DonationsController extends AbstractController
{
    
    #[Route('/donations', name: 'app_donations')]
public function donations(DonateRepository $donateRepository , CartService $cartService): Response
{
    $donates = $donateRepository->findAll();
    $cart = $cartService->getCartWithData();

    return $this->render('donations/index.html.twig', [
        'donates' => $donates,
    ]);
    
}


#[Route('/cart/add/{id}', name: 'cart_add')]
public function cart_add(CartService $cartService, $id): Response
{
    $cartService->add($id);
    $this->addFlash('info', 'Ajouté au panier');
    

    
    return $this->redirectToRoute('app_panier');

}









}
