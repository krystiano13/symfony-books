<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BooksController extends AbstractController
{
    #[Route('/books', name: 'app_books')]
    public function index(): Response
    {
        return $this->render('books/index.html.twig', [
            'controller_name' => 'BooksController',
        ]);
    }

    #[Route('/books/new', name: 'app_books_new')]
    public function new(): Response
    {
        $currentYear = date('Y');
        return $this->render('books/create.html.twig', [
            "year" => $currentYear,
            "mode" => "create",
        ]);
    }

    #[Route('/books', name: 'app_books', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, BookRepository $br): Response {
        $body = $request->getContent();
        $book = $serializer->deserialize($body, Book::class, 'json');
        $errors = $validator->validate($book);
        $messages = array();

        $this->checkForLimitations($br, $messages, $book);

        if(count($errors) > 0 || count($messages) > 0)
        {
            if(count($errors) > 0) {
                foreach ($errors as $violation) {
                    $msg = "{$violation->getPropertyPath()} - {$violation->getMessage()}";
                    array_unshift($messages,$msg);
                }
            }

            return $this->json(["errors" => $messages], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($book);
        $em->flush();

        return $this->json($serializer->serialize($book, 'json'), Response::HTTP_CREATED);
    }

    #[Route('/books/edit/{id}', name: 'app_books_edit')]
    public function edit(int $id, BookRepository $bookRepository): Response
    {
        $book = $bookRepository->find($id);

        if(!$book)
        {
            return $this->render("not_found.html.twig");
        }

        $currentYear = date('Y');
        return $this->render('books/create.html.twig', [
            "year" => $currentYear,
            "mode" => "edit",
            "book" => $book,
        ]);
    }

    private function checkForLimitations(BookRepository &$br, array &$messages, Book $book)
    {
        $booksWithSameAuthor = $br->count(["author" => $book->getAuthor()]);
        $allBooksCount = $br->count();
        $bookCombinationCount = $br->count(["author" => $book->getAuthor(), "title" => $book->getTitle()]);

        if($bookCombinationCount > 0) {
            array_unshift($messages, "This book is already in our database.");
        }

        if($booksWithSameAuthor >= 5) {
            array_unshift($messages, "You can't add more than 5 books from same author.");
        }

        if($allBooksCount >= 100) {
            array_unshift($messages, "Online Library reached its maximum capacity (100 books)");
        }
    }
}
