<?php

namespace App\Controller;


use App\Entity\User;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    #[Route('/my/account', name: 'app_my_account')]
    public function order(Request $request, EntityManagerInterface $manager): Response
    {

        $user = $this->getUser(); 
        $paniers = $user->getPaniers();
        $donations = [];
    
        foreach ($paniers as $panier) {
            $donate = $panier->getDonate();
            $donateId = $donate->getId();
    
            if (!isset($donations[$donateId])) {
                $donations[$donateId] = [
                    'donate' => $donate,
                    'totalQuantity' => 0,
                ];
            }
    
            $donations[$donateId]['totalQuantity'] = $panier->getQuantity();
        }
        return $this->render('my_account/index.html.twig', [
            'controller_name' => 'MyAccountController',
            'paniers' => $paniers, 
            'donations' => $donations,
        ]);
    }
}
