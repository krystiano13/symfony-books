<?php

namespace App\Controller;

use App\Entity\Book;
use App\Controller\Trait\ValidationErrorTrait;
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
    use ValidationErrorTrait;
    #[Route('/book', name: 'app_book', methods: ['GET'])]
    public function index(BookRepository $bookRepository, Request $request, EntityManagerInterface $em): Response
    {
        $sorting = $request->get("sort");
        $filters = $request->get("filters");

        $queryBuilder = $em->getRepository(Book::class)->createQueryBuilder("book");

        if($sorting) {
            foreach ($sorting as $key => $value) {
                $queryBuilder->addOrderBy("book.$key", $value);
            }
        }

        if($filters) {
            foreach ($filters as $key => $value) {
                $queryBuilder
                    ->andWhere("book.$key LIKE :$key")
                    ->setParameter($key, "%$value%");;
            }
        }

        $books = $queryBuilder->getQuery()->getResult();
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

    #[Route('/api/books', name: 'app_books', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, BookRepository $br): Response {
        $body = $request->getContent();
        $book = $serializer->deserialize($body, Book::class, 'json');
        $errors = $validator->validate($book, groups: ['default']);
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

    #[Route('/api/books/{id}', name: 'app_books_update', methods: ['PATCH'])]
    public function update(Request $request, int $id, BookRepository $bookRepository, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $book = $bookRepository->find($id);
        $messages = array();
        $errors = array();

        if($book)
        {
            $body = $serializer->deserialize($request->getContent(), Book::class, 'json');

            if($body->getIsbn() === $book->getIsbn()) {
                $errors = $validator->validate($body, groups: ['update']);
            }
            else {
                $errors = $validator->validate($body, groups: ['default']);
            }

            $this->checkForLimitations($bookRepository, $messages, $book, false);
            $this->handleErrors($errors, $messages);

            if(count($messages) > 0)
            {
                return $this->json(["errors" => $messages], Response::HTTP_BAD_REQUEST);
            }

            $book->setTitle($body->getTitle());
            $book->setAuthor($body->getAuthor());
            $book->setIsbn($body->getIsbn());
            $book->setReleaseDate($body->getReleaseDate());

            $em->flush();

            return $this->json($book, Response::HTTP_CREATED);
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

    #[Route('/api/books/{id}', name: 'app_books_delete', methods: ['DELETE'])]
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
