<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/user/new', name: 'user.create', methods: ['GET', 'HEAD'])]
    public function newUser() {
        return $this->render('user/new.html.twig');
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): Response {
        $body = $request->getContent();
        $user = $serializer->deserialize($body, User::class, 'json');
        $errors = $validator->validate($user);

    }
}
