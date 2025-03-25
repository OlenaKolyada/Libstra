<?php

namespace App\Controller;

use App\Document\Book;
use App\Document\Author;
use App\Document\Genre;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(DocumentManager $dm): Response
    {
        $books = $dm->getRepository(Book::class)->findAll();
        $authors = $dm->getRepository(Author::class)->findAll();
        $genres = $dm->getRepository(Genre::class)->findAll();

        return $this->render('home/index.html.twig', [
            'books' => $books,
            'authors' => $authors,
            'genres' => $genres,
        ]);
    }
}