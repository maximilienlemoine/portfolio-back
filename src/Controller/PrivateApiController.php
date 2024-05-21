<?php

namespace App\Controller;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

class PrivateApiController extends AbstractController
{
    private MailerService $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/private-api/send-mail', name: 'api_private', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $this->mailerService->sendMail('Test', 'maximilien.lemoine.pro@gmail.com', 'Test');

        return new JsonResponse([
            'message' => 'Welcome to your private API',
        ]);
    }
}