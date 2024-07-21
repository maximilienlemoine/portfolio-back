<?php

namespace App\Controller\PrivateApi;

use App\Entity\Skill;
use App\Repository\SkillRepository;
use App\Service\SecurityApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/private-api/skill')]
class SkillApiController extends AbstractController
{
    public function __construct(
        private readonly SecurityApiService $securityApiService,
        private readonly SkillRepository    $skillRepository,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Route('/get', name: 'api_private_skill_get', methods: ['POST'])]
    public function index(
        Request $request
    ): Response
    {
        if ($this->securityApiService->checkApiKey($request)) {
            return $this->securityApiService->checkApiKey($request);
        }

        $skills = $this->skillRepository->findAll();
        $skillsJson = $this->serializer->serialize($skills, 'json', ['groups' => Skill::READ]);

        return new Response($skillsJson, 200, ['Content-Type' => 'application/json']);
    }
}
