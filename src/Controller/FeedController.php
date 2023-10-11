<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostAttachment;
use App\Form\CreatePostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FeedController extends AbstractController
{
    #[Route('/', name: 'main_feed')]
    public function index(Request $request, PostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $post = new Post();
        $form = $this->createForm(CreatePostType::class, $post);
        $form->handleRequest($request);

        $post_created = false;

        if ($form->isSubmitted() && $form->isValid()) {
            $post
                ->setCreatedAt(new \DateTimeImmutable())
                ->setAuthor($this->getUser());
            $entityManager->persist($post);
            // Creating post attachments
            foreach ($form['images']->getData() as $uploadedFile) {
                $postAttachment = new PostAttachment();
                $postAttachment
                    ->setFile($uploadedFile)
                    ->setPost($post);
                $entityManager->persist($postAttachment);
            }
            $entityManager->flush();
            $post_created = true;
        }

        $posts = $postRepository->findBy(['helpGroup' => null], ['createdAt' => 'DESC'], 25);

        return $this->render('home/index.html.twig', ['posts' => $posts, 'createPostForm' => $form, 'postCreated' => $post_created]);
    }
}
