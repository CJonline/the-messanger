<?php

namespace App\Controller;

use App\Message\EmailNotification;
use FOS\UserBundle\Form\Type\ProfileFormType;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
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
}
