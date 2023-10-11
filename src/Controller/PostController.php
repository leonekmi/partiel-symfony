<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CreateCommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post_show')]
    public function showPoste(Request $request, Post $post, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment_form = $this->createForm(CreateCommentType::class, $comment);
        $comment_form->handleRequest($request);
        if ($comment_form->isSubmitted() && $comment_form->isValid()) {
            $this->denyAccessUnlessGranted('ROLE_USER');
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $comment->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($comment);
            $entityManager->flush();
        }
        return $this->render('post/post.html.twig', ['post' => $post, 'comment_form' => $comment_form]);
    }

    #[Route('/post/{id}/like', name: 'app_post_like')]
    public function likePoste(Post $post, EntityManagerInterface $entityManager): Response
    {
        $post->toggleLike($this->getUser());
        $entityManager->flush();
        // return $this->render('base.html.twig');
        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }

    #[Route('/post/{id}/dislike', name: 'app_post_dislike')]
    public function dislikePoste(Post $post, EntityManagerInterface $entityManager): Response
    {
        $post->toggleDislike($this->getUser());
        $entityManager->flush();
        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }
}
