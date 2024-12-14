<?php

namespace App\Controller\PrivateApi;

use App\HttpClient\PrivateApiHttpClient;
use App\Service\SecurityApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class MailerApiController extends AbstractController
{
    public function __construct(
        private readonly SecurityApiService $securityApiService,
        private readonly PrivateApiHttpClient $privateApiHttpClient,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/private-api/send-mail', name: 'api_private', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        if ($this->securityApiService->checkApiKey($request)) {
            return $this->securityApiService->checkApiKey($request);
        }

        $data = $request->request->all();

        $datas = [
            'subject' => 'Demande de contact',
            'to' => $this->getParameter('receiver_email'),
            'message' => 'Un utilisateur a fait une demande de contact. <br>' . $data['name'] . ' ' . $data['email'] . ' <br> ' . $data['message']
        ];

        try {
            $this->privateApiHttpClient->sendMail($datas);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }

        $datas_confirm = [
            'subject' => 'Confirmation de demande de contact',
            'to' => $data['email'],
            'message' => $data['name'] . ', <br><br> Votre demande de contact au-près de Maximilien LEMOINE a été prise en compte.'
        ];

        try {
            $this->privateApiHttpClient->sendMail($datas_confirm);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }


        return new JsonResponse('Mail sent', 200);
    }
}