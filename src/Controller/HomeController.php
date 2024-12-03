<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/')]
    public function index(BookRepository $bookRepository): Response
    {
        $bookRepository->count();
        return $this->render('home/index.html.twig', ["count" => $bookRepository->count()]);
    }

    #[Route('/books/{id}', name: 'book', methods: ['GET'])]
    public function show(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);

        if(!$book)
        {
            return $this->json(["errors" => ["book not found"]], Response::HTTP_NOT_FOUND);
        }

        return $this->json(["book" => $book],200);
    }
}
