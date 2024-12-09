<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BooksController extends AbstractController
{
    #[Route('/book', name: 'app_book', methods: ['GET'])]
    public function index(BookRepository $bookRepository): Response
    {
       $books = $bookRepository->findAll();
       return $this->json(['books' => $books], Response::HTTP_OK);
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

    #[Route('/books', name: 'app_books', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, BookRepository $br): Response {
        $body = $request->getContent();
        $book = $serializer->deserialize($body, Book::class, 'json');
        $errors = $validator->validate($book);
        $messages = array();

        $this->checkForLimitations($br, $messages, $book, true);
        $this->handleErrors($errors, $messages);

        if(count($messages) > 0)
        {
            return $this->json(["errors" => $messages], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($book);
        $em->flush();

        return $this->json($book, Response::HTTP_CREATED);
    }

    #[Route('/books/{id}', name: 'app_books_update', methods: ['PATCH'])]
    public function update(Request $request, int $id, BookRepository $bookRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $book = $bookRepository->find($id);
        $messages = array();

        if($book)
        {
            $body = $serializer->deserialize($request->getContent(), Book::class, 'json');
            $errors = $validator->validate($body);
            //$this->checkForLimitations($bookRepository, $messages, $book, false);
            $this->handleErrors($errors, $messages);

            return $this->json(["errors" => $messages], Response::HTTP_BAD_REQUEST);

            if(count($messages) > 0)
            {

            }

//            $book->setTitle($body->getTitle());
//            $book->setAuthor($body->getAuthor());
//            $book->setIsbn($body->getIsbn());
//            $book->setReleaseDate($body->getReleaseDate());
//
//            $em->flush();
//
//            return $this->json($book, Response::HTTP_CREATED);
        }

        return $this->json(["errors" => ["book not found"]], Response::HTTP_NOT_FOUND);
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
        return $this->render('books/edit.html.twig', [
            "year" => $currentYear,
            "mode" => "edit",
            "book" => $book,
        ]);
    }

    #[Route('/books/delete/{id}', name: 'app_books_delete', methods: ['DELETE'])]
    public function destroy(int $id, BookRepository $bookRepository, EntityManagerInterface $em): Response
    {
        $book = $bookRepository->find($id);

        if($book)
        {
            $em -> remove($book);
            $em->flush();
            return $this->json(null, Response::HTTP_NO_CONTENT);
        }

        return $this->json(["errors" => ["book not found"]], Response::HTTP_NOT_FOUND);
    }

    private function handleErrors(&$errors, array &$messages)
    {
        if(count($errors) > 0 || count($messages) > 0)
        {
            if(count($errors) > 0) {
                foreach ($errors as $violation) {
                    $msg = "{$violation->getPropertyPath()} - {$violation->getMessage()}";
                    array_unshift($messages,$msg);
                }
            }
        }
    }

    private function checkForLimitations(BookRepository &$br, array &$messages, Book $book, bool $isCreatingNewBook)
    {
        $booksWithSameAuthor = $br->count(["author" => $book->getAuthor()]);
        $allBooksCount = $br->count();
        $bookCombinationCount = $br->count(["author" => $book->getAuthor(), "title" => $book->getTitle()]);

        if($bookCombinationCount > 0 && $isCreatingNewBook) {
            array_unshift($messages, "This book is already in our database.");
        }

        if($booksWithSameAuthor >= 5) {
            array_unshift($messages, "You can't add more than 5 books from same author.");
        }

        if($allBooksCount >= 100 && $isCreatingNewBook) {
            array_unshift($messages, "Online Library reached its maximum capacity (100 books)");
        }
    }
}
