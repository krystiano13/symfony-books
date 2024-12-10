<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\Trait\ValidationErrorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    use ValidationErrorTrait;
    #[Route('/register', name: 'user.create', methods: ['GET', 'HEAD'])]
    public function register() {
        return $this->render('user/new.html.twig');
    }

    #[Route('/login', name: 'user.login', methods: ['GET', 'HEAD'])]
    public function login() {
        return $this->render('user/login.html.twig');
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response {
        $messages = array();
        $body = $request->getContent();
        $bodyDeserialized = json_decode($body, true);
        $user = $serializer->deserialize($body, User::class, 'json');
        $errors = $validator->validate($user);

        $user->setUsername(trim($user->getUsername()));

        $this->handleErrors($errors, $messages);

        if($bodyDeserialized['password'] !== $bodyDeserialized['password_confirmation']) {
            array_unshift($messages, 'Password does not match');
        }

        if(count($messages) > 0) {
            return $this->json(['errors' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $user->setPassword($hasher->hashPassword($user, $user->getPassword()));

        $em->persist($user);
        $em->flush();

        return $this->json(["user" => $user], Response::HTTP_CREATED);
    }
}
