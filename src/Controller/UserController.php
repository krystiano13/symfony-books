<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/user/new', name: 'user.create', methods: ['GET', 'HEAD'])]
    public function newUser() {
        return $this->render('user/new.html.twig');
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function create(): Response {

    }
}
