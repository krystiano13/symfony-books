<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BooksController extends AbstractController
{
    #[Route('/books', name: 'app_books')]
    public function index(): Response
    {
        return $this->render('books/index.html.twig', [
            'controller_name' => 'BooksController',
        ]);
    }

    #[Route('/books/create', name: 'app_books_create')]
    public function create(): Response
    {
        $currentYear = date('Y');
        return $this->render('books/create.html.twig', ["year" => $currentYear]);
    }
}
