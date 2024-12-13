<?php

namespace App\Tests;

use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookTest extends WebTestCase
{
    public function testBooksAuthorLimit() {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $faker = \Faker\Factory::create();
        $bookRepository = $entityManager->getRepository(Book::class);

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        for ($i = 0; $i < 6; $i++) {
            $book = new Book();
            $book->setTitle("123");
            $book->setAuthor("Test Author");
            $book->setReleaseDate($faker->numberBetween(1900, 2024));
            $book->setIsbn($faker->isbn10());

            $entityManager->persist($book);
        }

        $entityManager->flush();
        $crawler = $client->jsonRequest('POST', '/api/books', [
            'title' => 'New Test Book',
            'author' => "Test Author",
            'release_date' => 2024,
            'isbn' => $faker->isbn10(),
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
    public function testBooksLimit(): void
    {
        $client = self::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $faker = \Faker\Factory::create();
        $bookRepository = $entityManager->getRepository(Book::class);

        $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($metadata);
        $schemaTool->createSchema($metadata);

        for ($i = 0; $i < 100; $i++) {
            $book = new Book();
            $book->setTitle($faker->sentence(3));
            $book->setAuthor($faker->name());
            $book->setReleaseDate($faker->numberBetween(1900, 2024));
            $book->setIsbn($faker->isbn10());

            $entityManager->persist($book);
        }

        $entityManager->flush();
        $crawler = $client->jsonRequest('POST', '/api/books', [
            'title' => 'New Test Book',
            'author' => 'Test Author',
            'release_date' => 2024,
            'isbn' => $faker->isbn10(),
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
}
