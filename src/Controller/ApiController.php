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
        $messageBus->dispatch($emailNotification->setContent($request->get('message')));

        return new JsonResponse(['message' => 'Message was added to queue'], Response::HTTP_OK);
    }

    /**
     * @Route("/api/password", name="api_reset_password", methods={"POST"})
     */
    public function reset(Request $request, UserManagerInterface $userManager, TokenGeneratorInterface $tokenGenerator)
    {
        $email = $request->query->get('email');
        $password = $request->query->get('password');
        $user = $userManager->findUserByEmail($email);
        if (null === $user) {
            throw $this->createNotFoundException();
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $user->setPasswordRequestedAt(new \DateTime());
        $user->setPlainPassword($password);

        $form = $this->createForm(ProfileFormType::class, $user);
        $this->processForm($request, $form);
        if (!$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);
            $data = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ];

            return new JsonResponse($data, 400);
        }

        $userManager->updateUser($user);

        return new JsonResponse('', Response::HTTP_OK);
    }
}
