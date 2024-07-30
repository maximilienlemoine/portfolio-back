<?php

declare(strict_types=1);

namespace App\Controller\PrivateApi;

use App\Entity\Stack;
use App\Repository\StackRepository;
use App\Service\SecurityApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/private-api/stack')]
class StackApiController extends AbstractController
{
    public function __construct(
        private readonly SecurityApiService  $securityApiService,
        private readonly StackRepository     $stackRepository,
        private readonly SerializerInterface $serializer,
    )
    {
    }

    #[Route('/get', name: 'api_private_stack_get', methods: ['POST'])]
    public function index(
        Request $request
    ): Response
    {
        if ($this->securityApiService->checkApiKey($request)) {
            return $this->securityApiService->checkApiKey($request);
        }

        $stacks = $this->stackRepository->findVisible();
        $stacksJson = $this->serializer->serialize($stacks, 'json', ['groups' => [Stack::READ]]);

        return new Response($stacksJson, 200, ['Content-Type' => 'application/json']);
    }
}
