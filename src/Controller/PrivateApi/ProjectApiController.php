<?php

namespace App\Controller\PrivateApi;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Service\SecurityApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/private-api/project')]
class ProjectApiController extends AbstractController
{
    public function __construct(
        private readonly SecurityApiService $securityApiService,
        private readonly ProjectRepository  $projectRepository,
        private readonly SerializerInterface $serializer,
    ) {}
    #[Route('/', name: 'api_private_project_get', methods: ['GET'])]
    public function index(
        Request $request
    ): Response {
        if ($this->securityApiService->checkApiKey($request)) {
            return $this->securityApiService->checkApiKey($request);
        }

        $projects = $this->projectRepository->findVisible();
        $projectsJson = $this->serializer->serialize($projects, 'json', ['groups' => Project::READ]);

        return new JsonResponse($projectsJson, 200, [], true);
    }
}
