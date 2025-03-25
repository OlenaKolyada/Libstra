<?php

namespace App\Controller;

use App\Document\Genre;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/genre')]
class GenreController extends AbstractController
{
    #[Route('/', name: 'genre_index', methods: ['GET'])]
    public function index(DocumentManager $dm): Response
    {
        $genres = $dm->getRepository(Genre::class)->findAll();

        return $this->render('genre/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/new', name: 'genre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentManager $dm): Response
    {
        $genre = new Genre();

        if ($request->isMethod('POST')) {
            $genre->setName($request->request->get('name'));

            $dm->persist($genre);
            $dm->flush();

            return $this->redirectToRoute('genre_index');
        }

        return $this->render('genre/new.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/{id}', name: 'genre_show', methods: ['GET'])]
    public function show(Genre $genre): Response
    {
        return $this->render('genre/show.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/{id}/edit', name: 'genre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Genre $genre, DocumentManager $dm): Response
    {
        if ($request->isMethod('POST')) {
            $genre->setName($request->request->get('name'));

            $dm->flush();

            return $this->redirectToRoute('genre_index');
        }

        return $this->render('genre/edit.html.twig', [
            'genre' => $genre,
        ]);
    }

    #[Route('/{id}', name: 'genre_delete', methods: ['POST'])]
    public function delete(Request $request, Genre $genre, DocumentManager $dm): Response
    {
        if ($this->isCsrfTokenValid('delete'.$genre->getId(), $request->request->get('_token'))) {
            $dm->remove($genre);
            $dm->flush();
        }

        return $this->redirectToRoute('genre_index');
    }
}