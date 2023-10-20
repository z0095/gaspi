<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Entity\Panier;
use App\Service\CartService;
use App\Repository\UserRepository;
use App\Repository\DonateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{

    #[Route('/panier', name: 'app_panier')]
    public function index(CartService $cartService): Response
    {
        $cart = $cartService->getCartWithData();
        // dd($cart);
        return $this->render('panier/index.html.twig', [
            'panier' => $cart,
        ]);
    }

    #[Route('/panier/supprimer/{id}', name: 'supprimer_from_panier')]
    public function supprimerFromPanier(CartService $cartService, EntityManagerInterface $manager, $id): Response
    {

        $cartService->remove($id);


        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/recuperer/{id}', name: 'recuperer_to_donate')]
    public function recupererToDonate(CartService $cartService, EntityManagerInterface $entityManager, $id, DonateRepository $donateRepository, Mail $mail, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        $donation = $donateRepository->find($id);

        if ($donation && $donation->getQuantity() > 0) {
            $addedQuantity = 1;
            $currentQuantity = $donation->getQuantity();


            if ($addedQuantity <= $currentQuantity) {

                $donation->setQuantity($currentQuantity - $addedQuantity);
                $entityManager->persist($donation);
                $entityManager->flush();
            }
        }
        $resto = new User();
        $myCart = $cartService->getCartWithData();
        foreach ($myCart as $item) {
            $panier = new panier();
            $panier->setUser($this->getUser());
            $panier->setDonate($item['product']);
            $resto =  $userRepository->findById($item['product']->getRestaurant()->getId());
            $panier->setQuantity($item['quantity']);
            $entityManager->persist($panier);
            $entityManager->flush($panier);
        }
        $association = $userRepository->findOneByEmail($user->getEmail());
       
        $restaurant = $resto[0];

        $associationSubject = 'Confirmation de récupération de don';
        $associationContent = "Cher {$association->getName()}, la récupération de {$donation->getQuantity()} don de {$donation->getName()} a été confirmée. Veuillez venir chercher la nourriture le {$donation->getPickupDateTime()->format('Y-m-d')} à {$donation->getPickupDateTime()->format('H:i')}.";

        $restaurantSubject = 'Information de réservation de don';
        $restaurantContent = "L'association {$association->getName()} a réservé {$donation->getQuantity()} don(s) de {$donation->getName()} pour le {$donation->getPickupDateTime()->format('Y-m-d')} à {$donation->getPickupDateTime()->format('H:i')}. Veuillez préparer la commande.";

        $mail->send($association->getEmail(), $association->getName(), $associationSubject, $associationContent);
        $mail->send($restaurant->getEmail(), $restaurant->getName(), $restaurantSubject, $restaurantContent);









        return $this->redirectToRoute('app_my_account');
    }
}
