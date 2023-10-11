<?php

namespace App\Controller;

use App\Entity\HelpGroup;
use App\Entity\Post;
use App\Entity\PostAttachment;
use App\Form\CreatePostType;
use App\Form\HelpGroupType;
use App\Repository\HelpGroupRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/group')]
class HelpGroupController extends AbstractController
{
    #[Route('/', name: 'app_help_group_index', methods: ['GET'])]
    public function index(HelpGroupRepository $helpGroupRepository): Response
    {
        return $this->render('help_group/index.html.twig', [
            'help_groups' => $helpGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_help_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $helpGroup = new HelpGroup();
        $form = $this->createForm(HelpGroupType::class, $helpGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $helpGroup
                ->setCreatedAt(new \DateTimeImmutable())
                ->setOwner($this->getUser());
            $entityManager->persist($helpGroup);
            $entityManager->flush();

            return $this->redirectToRoute('app_help_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('help_group/new.html.twig', [
            'help_group' => $helpGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_help_group_show', methods: ['GET', 'POST'])]
    public function show(Request $request, HelpGroup $helpGroup, PostRepository $postRepository, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();
        $form = $this->createForm(CreatePostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post
                ->setCreatedAt(new \DateTimeImmutable())
                ->setAuthor($this->getUser())
                ->setHelpGroup($helpGroup);
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
        }

        $posts = $postRepository->findBy(['helpGroup' => $helpGroup], ['createdAt' => 'DESC'], 25);

        return $this->render('help_group/show.html.twig', [
            'help_group' => $helpGroup,
            'createPostForm' => $form,
            'posts' => $posts
        ]);
    }

    #[Route('/{id}/edit', name: 'app_help_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HelpGroup $helpGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HelpGroupType::class, $helpGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_help_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('help_group/edit.html.twig', [
            'help_group' => $helpGroup,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_help_group_delete', methods: ['POST'])]
    public function delete(Request $request, HelpGroup $helpGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $helpGroup->getId(), $request->request->get('_token'))) {
            $entityManager->remove($helpGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_help_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
