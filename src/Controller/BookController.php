<?php

namespace App\Controller;

use App\Document\Book;
use App\Document\Author;
use App\Document\Genre;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/book')]
class BookController extends AbstractController
{
    #[Route('/', name: 'book_index', methods: ['GET'])]
    public function index(DocumentManager $dm): Response
    {
        $books = $dm->getRepository(Book::class)->findAll();

        return $this->render('book/index.html.twig', [
            'books' => $books,
        ]);
    }

    #[Route('/new', name: 'book_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentManager $dm): Response
    {
        $book = new Book();

        if ($request->isMethod('POST')) {
            $book->setTitle($request->request->get('title'));
            $book->setDescription($request->request->get('description'));
            $book->setCover($request->request->get('cover'));
            $book->setYear((int)$request->request->get('year'));

            // Получаем автора
            $authorId = $request->request->get('author');
            if ($authorId) {
                $author = $dm->getRepository(Author::class)->find($authorId);
                if ($author) {
                    $book->setAuthor($author);
                }
            }

            // Получаем жанры
            $genreIds = $request->request->get('genres', []);
            foreach ($genreIds as $genreId) {
                $genre = $dm->getRepository(Genre::class)->find($genreId);
                if ($genre) {
                    $book->addGenre($genre);
                }
            }

            $dm->persist($book);
            $dm->flush();

            return $this->redirectToRoute('book_index');
        }

        $authors = $dm->getRepository(Author::class)->findAll();
        $genres = $dm->getRepository(Genre::class)->findAll();

        return $this->render('book/new.html.twig', [
            'book' => $book,
            'authors' => $authors,
            'genres' => $genres,
        ]);
    }

    #[Route('/{id}', name: 'book_show', methods: ['GET'])]
    public function show(Book $book): Response
    {
        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/{id}/edit', name: 'book_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Book $book, DocumentManager $dm): Response
    {
        if ($request->isMethod('POST')) {
            $book->setTitle($request->request->get('title'));
            $book->setDescription($request->request->get('description'));
            $book->setCover($request->request->get('cover'));
            $book->setYear((int)$request->request->get('year'));

            // Получаем автора
            $authorId = $request->request->get('author');
            if ($authorId) {
                $author = $dm->getRepository(Author::class)->find($authorId);
                if ($author) {
                    $book->setAuthor($author);
                }
            }

            // Сбрасываем жанры и добавляем новые
            $book->getGenre()->clear();

            $genreIds = $request->request->get('genres', []);
            foreach ($genreIds as $genreId) {
                $genre = $dm->getRepository(Genre::class)->find($genreId);
                if ($genre) {
                    $book->addGenre($genre);
                }
            }

            $dm->flush();

            return $this->redirectToRoute('book_index');
        }

        $authors = $dm->getRepository(Author::class)->findAll();
        $genres = $dm->getRepository(Genre::class)->findAll();

        return $this->render('book/edit.html.twig', [
            'book' => $book,
            'authors' => $authors,
            'genres' => $genres,
        ]);
    }

    #[Route('/{id}', name: 'book_delete', methods: ['POST'])]
    public function delete(Request $request, Book $book, DocumentManager $dm): Response
    {
        if ($this->isCsrfTokenValid('delete'.$book->getId(), $request->request->get('_token'))) {
            $dm->remove($book);
            $dm->flush();
        }

        return $this->redirectToRoute('book_index');
    }
}