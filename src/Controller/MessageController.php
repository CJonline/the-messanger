<?php

namespace App\Controller;

use App\Document\Message;
use App\Repository\MessageRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */
    public function index(Request $request, PaginatorInterface $paginator, MessageRepository $messageRepository)
    {
        $messages = $messageRepository->findBy([], null, 10);

        $pagination = $paginator->paginate(
            $messages,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('message/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
