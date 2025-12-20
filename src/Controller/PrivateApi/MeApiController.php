<?php

namespace App\Controller\PrivateApi;

use App\Entity\Me;
use App\Repository\MeRepository;
use App\Service\SecurityApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/private-api/me')]
class MeApiController extends AbstractController
{
    public function __construct(
        private readonly SecurityApiService $securityApiService,
        private readonly MeRepository    $meRepository,
        private readonly SerializerInterface $serializer,
    ) {}

    #[Route('/', name: 'api_private_me_get', methods: ['GET'])]
    public function index(
        Request $request
    ): Response {
        if ($this->securityApiService->checkApiKey($request)) {
            return $this->securityApiService->checkApiKey($request);
        }

        $skills = $this->meRepository->findUnique();
        $skillsJson = $this->serializer->serialize($skills, 'json', ['groups' => Me::READ]);

        return new Response($skillsJson, 200, ['Content-Type' => 'application/json']);
    }
}
