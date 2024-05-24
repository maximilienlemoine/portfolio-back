<?php

namespace App\Controller;

use App\Service\MailerService;
use App\Service\SecurityApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Attribute\Route;

class PrivateApiController extends AbstractController
{
    private MailerService $mailerService;
    private SecurityApiService $securityApiService;

    public function __construct(
        MailerService      $mailerService,
        SecurityApiService $securityApiService,
    )
    {
        $this->mailerService = $mailerService;
        $this->securityApiService = $securityApiService;
    }

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/private-api/send-mail', name: 'api_private', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        if ($this->securityApiService->checkApiKey($request)) {
            return $this->securityApiService->checkApiKey($request);
        }

        $data = $request->request->all();

        if (!$this->mailerService->domainExists($data['email'])) {
            return new JsonResponse(['error' => 'Invalid email'], 400);
        }

        // Send contact's request
        try {
            $this->mailerService->sendMail(
                'Demande de contact',
                $this->getParameter('receiver_email'),
                'Un utilisateur a fait une demande de contact. <br>' . $data['name'] . ' ' . $data['email'] . ' <br> ' . $data['message']
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }


        // Send confirmation mail
        try {
            $this->mailerService->sendMail(
                'Confirmation de demande de contact',
                $data['email'],
                $data['name'] . ', <br><br> Votre demande de contact au-près de Maximilien LEMOINE a été prise en compte.'
            );
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }


        return new JsonResponse('Mail sent', 200);
    }
}