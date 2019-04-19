<?php

namespace App\Controller;

use App\Document\Message;
use App\Message\EmailNotification;
use App\Repository\MessageRepository;
use Doctrine\ODM\MongoDB\DocumentManager;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/email", name="api_send_email", methods={"POST"})
     */
    public function send(Request $request, MessageBusInterface $messageBus, EmailNotification $emailNotification)
    {
        $message = $request->get('message');
        if (empty($message)) {
            return new JsonResponse(['message' => 'Parameter cant be empty'], Response::HTTP_BAD_REQUEST);
        }

        $messageBus->dispatch($emailNotification->setContent($message));


        return new JsonResponse(['message' => 'Ok'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/password", name="api_reset_password", methods={"POST"})
     */
    public function reset(Request $request, UserManagerInterface $userManager, TokenGeneratorInterface $tokenGenerator)
    {
        $password = $request->get('password');
        if (empty($password)) {
            return new JsonResponse(['message' => 'Parameter cant be empty'], Response::HTTP_BAD_REQUEST);
        }

        $user = $this->getUser();
        $user->setPlainPassword($password);

        $user->setPasswordRequestedAt(new \DateTime());
        $user->setPlainPassword($password);

        $userManager->updateUser($user);

        return new JsonResponse(['message' => 'Ok'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/message", name="api_add_message", methods={"POST"})
     */
    public function addMessage(Request $request, DocumentManager $documentManager)
    {
        $message = new Message();
        $message->setContent($request->get('content'));

        $documentManager->persist($message);
        $documentManager->flush();

        return new JsonResponse(['message' => 'Ok'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/message", name="api_delete_message", methods={"DELETE"})
     */
    public function deleteMessage(Request $request, DocumentManager $documentManager)
    {
        $message = $documentManager->getRepository(Message::class)->find($request->get('id'));

        $documentManager->remove($message);
        $documentManager->flush();

        return new JsonResponse(['message' => 'Ok'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/message", name="api_edit_message", methods={"PUT"})
     */
    public function editMessage(Request $request, DocumentManager $documentManager)
    {
        $message = $documentManager->getRepository(Message::class)->find($request->get('id'));

        if (!$message) {
            return new JsonResponse(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        $message->setContent($request->get('content'));

        $documentManager->persist($message);
        $documentManager->flush();

        return new JsonResponse(['message' => 'Ok'], Response::HTTP_OK);
    }

    /**
     * @Route(
     *     "/api/message",
     *      name="api_list_message",
     *      methods={"GET"},
     *      defaults={"_format": "json"},
     *     )
     */
    public function listMessage(Request $request, MessageRepository $messageRepository, SerializerInterface $serializer)
    {
        $messages = $messageRepository->findBy([], null, 10);
        $data = $serializer->serialize($messages, 'json');

        return new JsonResponse(['data' => json_decode($data)], Response::HTTP_OK);
    }

    /**
     * @Route(
     *     "/api/message/search",
     *      name="api_search_message",
     *      methods={"GET"},
     *      defaults={"_format": "json"},
     *     )
     */
    public function searchMessage(Request $request, DocumentManager $documentManager)
    {
        return new JsonResponse(['message' => 'Ok'], Response::HTTP_OK);
    }
}
