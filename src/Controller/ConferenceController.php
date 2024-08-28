<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Conference;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{

    public function __construct(
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'homepage')]
    public function index(ConferenceRepository $conferenceRepository ): Response
    {
        return $this->render('conference/index.html.twig',[
                'conferences' => $conferenceRepository->findAll(),
            ]);


    }

    #[Route('/conference/{slug}', name: 'conference')]
    public function show(Request $request,Environment $twig,Conference $conference,CommentRepository $commentRepository) : Response
    {
        $comment =  new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $comment->setConference($conference);

            $this->



        }

        $offset = max(0,$request->query->getInt('offset',0));
        $paginator = $commentRepository->getCommentPaginator($conference, $offset);

        return $this->render('conference/show.html.twig',[
            'conference' => $conference,
            'comments' => $paginator,
            'previous' => $offset - CommentRepository::COMMENTS_PER_PAGE,
            'next' => min(count($paginator),$offset + CommentRepository::COMMENTS_PER_PAGE),
            'comment_form' => $form,
        ]);
    }

}
