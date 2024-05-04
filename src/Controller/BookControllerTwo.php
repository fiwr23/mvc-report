<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Second controller class for library.
 */
class BookControllerTwo extends AbstractController
{
    #[Route('/api/library/books', name: 'book_show_all')]
    public function showAllBook(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        // return $this->json($books);

        $response = $this->json($books);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/api/library/book/{isbn}', name: 'book_by_isbn')]
    public function showBookById(
        BookRepository $bookRepository,
        string $isbn
    ): Response {
        $book = $bookRepository->findOneBySomeField("" . $isbn);

        if (!$book) {
            $this->addFlash(
                'warning',
                'Can not find book!'
            );
            return $this->redirectToRoute('book_view_all');
        }

        // return $this->json($book);

        $response = $this->json($book);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route('/book/create', name: 'book_create')]
    public function createBook(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle('RandTitle' . rand(1, 999));
        $book->setIsbn('RandIsbn' . rand(1, 999));
        $book->setAuthor('Rand Author ' . rand(1, 999));
        $book->setImage('img/book-cover-template.jpg');

        // tell Doctrine you want to (eventually) save the Book
        // (no queries yet)
        $entityManager->persist($book);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book has been created!'
        );

        // return new Response('Saved new book with id '.$book->getId());
        return $this->redirectToRoute('book_view_all');

    }


}
