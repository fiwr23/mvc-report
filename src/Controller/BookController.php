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
 * First controller class for library.
 */
class BookController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/book/create_new_book/', name: 'create_new_book_form')]
    public function createNewBook(): Response
    {
        return $this->render('book/create_new_book.html.twig');
    }

    #[Route('/book/view/{bookid}', name: 'view_book_by_id')]
    public function viewBookById(
        BookRepository $bookRepository,
        int $bookid
    ): Response {
        // One book
        $book = $bookRepository->find($bookid);

        if (!$book) {
            $this->addFlash(
                'warning',
                'Can not find book!'
            );
            return $this->redirectToRoute('book_view_all');
        }

        /** @var string $checkIfLocal */
        $checkIfLocal = $book->getImage();
        $localImg = false;
        if (substr($checkIfLocal, 0, 3) === 'img') {
            $localImg = true;
        }
        $data = [
            'book' => $book,
            'localimg' => $localImg
        ];

        return $this->render('book/view_one_book.html.twig', $data);
    }

    #[Route('/book/update/{bookid}', name: 'update_book_by_id')]
    public function updateBookById(
        BookRepository $bookRepository,
        int $bookid
    ): Response {
        // One book
        $book = $bookRepository->find($bookid);

        if (!$book) {
            $this->addFlash(
                'warning',
                'Can not find book!'
            );
            return $this->redirectToRoute('book_view_all');
        }

        $data = [
            'book' => $book
        ];

        return $this->render('book/update_one_book.html.twig', $data);
    }

    #[Route("/book/update_book_post", name: "update_book_post", methods: ['POST'])]
    public function updateBookPost(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        /** @var string $bookTitle */
        $bookTitle = $request->request->get('bookTitle');
        /** @var string $bookImage */
        $bookImage = $request->request->get('bookImage');
        /** @var string $bookAuthor */
        $bookAuthor = $request->request->get('bookAuthor');
        /** @var string $bookIsbn */
        $bookIsbn = $request->request->get('bookIsbn');
        /** @var string $bookId */
        $bookId = $request->request->get('bookId');

        $entityManager = $doctrine->getManager();

        $book = $entityManager->getRepository(Book::class)->find((int)$bookId);

        if (!$book) {
            $this->addFlash(
                'warning',
                'Can not find book!'
            );
            return $this->redirectToRoute('book_view_all');
        }

        $book->setTitle($bookTitle);
        $book->setIsbn($bookIsbn);
        $book->setImage($bookImage);
        $book->setAuthor($bookAuthor);

        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book has been updated!'
        );

        return $this->redirectToRoute('view_book_by_id', ["bookid" => $bookId]);
        // $this->drawManyCards($numCardsToDraw);
    }

    #[Route("/book/create_new_book_post", name: "create_new_book_post", methods: ['POST'])]
    public function createNewBookPost(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {
        $bookTitle = $request->request->get('bookTitle');
        $bookImage = $request->request->get('bookImage');
        $bookAuthor = $request->request->get('bookAuthor');
        $bookIsbn = $request->request->get('bookIsbn');


        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle("" . $bookTitle);
        $book->setIsbn("" . $bookIsbn);
        $book->setImage("" . $bookImage);
        $book->setAuthor("" . $bookAuthor);

        // tell Doctrine you want to (eventually) save the Book
        // (no queries yet)
        $entityManager->persist($book);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book has been created!'
        );
        return $this->redirectToRoute('book_view_all');
    }

    /*
    #[Route('/book/delete/{id}', name: 'book_delete_by_id')]
    public function deleteBookById(
        ManagerRegistry $doctrine,
        int $id
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($id);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$id
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('book_show_all');
    }
    */

    #[Route('/book/confirm_deletion/{bookid}', name: 'confirm_deletion_by_id')]
    public function confirmDeletionById(
        BookRepository $bookRepository,
        int $bookid
    ): Response {
        // One book
        $book = $bookRepository->find($bookid);

        if (!$book) {
            $this->addFlash(
                'warning',
                'Can not find book!'
            );
            return $this->redirectToRoute('book_view_all');
        }

        /** @var string $checkIfLocal */
        $checkIfLocal = $book->getImage();
        $localImg = false;
        if (substr($checkIfLocal, 0, 3) === 'img') {
            $localImg = true;
        }
        $data = [
            'book' => $book,
            'localimg' => $localImg
        ];

        return $this->render('book/confirm_deletion.html.twig', $data);
    }

    #[Route("/book/delete_book_post", name: "delete_book_post", methods: ['POST'])]
    public function confirmDeletionPost(
        Request $request,
        ManagerRegistry $doctrine
    ): Response {

        $bookId = $request->request->get('bookId');

        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookId);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$bookId
            );
        }

        $entityManager->remove($book);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Book has been deleted!'
        );
        return $this->redirectToRoute('book_view_all');
    }


    #[Route('/book/update/{bookid}/{isbn}', name: 'book_update')]
    public function updateBook(
        ManagerRegistry $doctrine,
        int $bookid,
        string $isbn
    ): Response {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookid);

        if (!$book) {
            throw $this->createNotFoundException(
                'No book found for id '.$bookid
            );
        }

        $book->setIsbn($isbn);
        $entityManager->flush();

        return $this->redirectToRoute('book_show_all');
    }

    #[Route('/book/view', name: 'book_view_all')]
    public function viewAllBook(
        BookRepository $bookRepository
    ): Response {
        $books = $bookRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('book/view.html.twig', $data);
    }
}
