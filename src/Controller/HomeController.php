<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\GroupRepository;
use App\Repository\PostRepository;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TokenStorageInterface $tokenStorage, GroupRepository $groupRepository, PostRepository $postRepository): Response
    {
        $user = $tokenStorage->getToken()->getUser();
        $groups = $groupRepository->findAll();
        $posts = $postRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'groups' => $groups,
            'posts' => $posts,
        ]);
    }
}
