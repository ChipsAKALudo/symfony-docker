<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/post')]
final class PostController extends AbstractController
{
    #[Route('/new', name: 'app_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $response = null;
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($this->getUser());
            $entityManager->persist($post);
            $entityManager->flush();
            $response = new Response();
            $response->setStatusCode(Response::HTTP_CREATED);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ], $response);
    }

    #[Route('/list', name: 'app_post_index', methods: ['GET'])]
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $group = $request->query->getInt('group');
        $tags = $request->query->get('tags');
        $tags = $tags ? explode(',', $tags) : null;
        $offset = $request->query->getInt('offset', 0);

        $posts = $postRepository->findByFilters(
            offset: $offset,
            group: $group,
            tags: $tags
        );

        return $this->json($posts);
    }

    #[Route('/{id}/reply', name: 'app_post_reply', methods: ['GET', 'POST'])]
    public function reply(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $response = null;
        $reply = new Post();
        $reply->setParent($post);
        $reply->setAuthor($this->getUser());
        $form = $this->createForm(PostType::class, $reply);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reply);
            $entityManager->flush();
            $response = new Response();
            $response->setStatusCode(Response::HTTP_CREATED);
        }

        return $this->render('post/reply.html.twig', [
            'post' => $post,
            'reply' => $reply,
            'form' => $form,
        ], $response);
}

    #[Route('/{id}', name: 'app_post_show', methods: ['GET'])]
    public function show(Post $post): Response
    {
        return $this->json($post);
    }

    //todo: make a voter for this
    #[Route('/{id}', name: 'app_post_delete', methods: ['DELETE'])]
    public function delete(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post_index', [], Response::HTTP_SEE_OTHER);
    }
}
