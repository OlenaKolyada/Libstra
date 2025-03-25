<?php

namespace App\Controller;

use App\Document\Author;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/author')]
class AuthorController extends AbstractController
{
    #[Route('/', name: 'author_index', methods: ['GET'])]
    public function index(DocumentManager $dm): Response
    {
        $authors = $dm->getRepository(Author::class)->findAll();

        return $this->render('author/index.html.twig', [
            'authors' => $authors,
        ]);
    }

    #[Route('/new', name: 'author_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentManager $dm): Response
    {
        $author = new Author();

        if ($request->isMethod('POST')) {
            $author->setName($request->request->get('name'));
            $author->setLastName($request->request->get('lastName'));
            $author->setYear((int)$request->request->get('year'));
            $author->setCountry($request->request->get('country'));

            $dm->persist($author);
            $dm->flush();

            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/new.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/{id}', name: 'author_show', methods: ['GET'])]
    public function show(Author $author): Response
    {
        return $this->render('author/show.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/{id}/edit', name: 'author_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Author $author, DocumentManager $dm): Response
    {
        if ($request->isMethod('POST')) {
            $author->setName($request->request->get('name'));
            $author->setLastName($request->request->get('lastName'));
            $author->setYear((int)$request->request->get('year'));
            $author->setCountry($request->request->get('country'));

            $dm->flush();

            return $this->redirectToRoute('author_index');
        }

        return $this->render('author/edit.html.twig', [
            'author' => $author,
        ]);
    }

    #[Route('/{id}', name: 'author_delete', methods: ['POST'])]
    public function delete(Request $request, Author $author, DocumentManager $dm): Response
    {
        if ($this->isCsrfTokenValid('delete'.$author->getId(), $request->request->get('_token'))) {
            $dm->remove($author);
            $dm->flush();
        }

        return $this->redirectToRoute('author_index');
    }
}