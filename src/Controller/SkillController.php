<?php

namespace App\Controller;

use App\Entity\Skill;
use App\Form\SkillType;
use App\Repository\SkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/skill')]
class SkillController extends AbstractController
{
    #[Route('/', name: 'app_skill_index', methods: ['GET'])]
    public function index(SkillRepository $skillRepository): Response
    {
        return $this->render('skill/index.html.twig', [
            'skills' => $skillRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_skill_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $skill = new Skill();
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();
            if ($icon) {
                $iconName = md5(uniqid()) . '.' . $icon->guessExtension();
                $icon->move($this->getParameter('skill_upload_dir'), $iconName);
                $skill->setIcon($iconName);
            }

            $entityManager->persist($skill);
            $entityManager->flush();

            return $this->redirectToRoute('app_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('skill/new.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_skill_show', methods: ['GET'])]
    public function show(Skill $skill): Response
    {
        return $this->render('skill/show.html.twig', [
            'skill' => $skill,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_skill_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SkillType::class, $skill);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $icon = $form->get('icon')->getData();
            if ($icon) {
                if ($skill->getIcon()) {
                    if (file_exists($this->getParameter('skill_upload_dir') . '/' . $skill->getIcon())) {
                        unlink($this->getParameter('skill_upload_dir') . '/' . $skill->getIcon());
                    }
                }

                $iconName = md5(uniqid()) . '.' . $icon->guessExtension();
                $icon->move($this->getParameter('skill_upload_dir'), $iconName);
                $skill->setIcon($iconName);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_skill_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('skill/edit.html.twig', [
            'skill' => $skill,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_skill_delete', methods: ['POST'])]
    public function delete(Request $request, Skill $skill, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $skill->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($skill);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_skill_index', [], Response::HTTP_SEE_OTHER);
    }
}
