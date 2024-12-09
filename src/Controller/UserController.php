<?php

namespace App\Controller;

use App\Entity\User;
use App\Controller\Trait\ValidationErrorTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    use ValidationErrorTrait;
    #[Route('/user/new', name: 'user.create', methods: ['GET', 'HEAD'])]
    public function newUser() {
        return $this->render('user/new.html.twig');
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): Response {
        $messages = array();
        $body = $request->getContent();
        $user = $serializer->deserialize($body, User::class, 'json');
        $errors = $validator->validate($user);
        $this->handleErrors($errors, $messages);

        if($body['password'] !== $body['password_confirmation']) {
            array_unshift($messages, "Passwords do not match");
        }

        if(count($messages) > 0) {
            return $this->json(['errors' => $messages], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($user);
        $em->flush();

        return $this->json(["user" => $user], Response::HTTP_CREATED);
    }
}
