<?php

namespace App\Controller;

use App\Form\MeType;
use App\Repository\MeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/me')]
class MeController extends AbstractController
{

    #[Route('/edit', name: 'app_me_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, MeRepository $meRepository): Response
    {
        $me = $meRepository->findUnique();
        $form = $this->createForm(MeType::class, $me);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                if ($me->getImage()) {
                    if (file_exists($this->getParameter('me_upload_dir') . '/' . $me->getImage())) {
                        unlink($this->getParameter('me_upload_dir') . '/' . $me->getImage());
                    }
                }

                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('me_upload_dir'), $imageName);
                $me->setImage($imageName);
            }

            $cv = $form->get('cv')->getData();
            if ($cv) {
                if ($me->getCv()) {
                    if (file_exists($this->getParameter('me_upload_dir') . '/' . $me->getCv())) {
                        unlink($this->getParameter('me_upload_dir') . '/' . $me->getCv());
                    }
                }

                $cvName = md5(uniqid()) . '.' . $cv->guessExtension();
                $cv->move($this->getParameter('me_upload_dir'), $cvName);
                $me->setCv($cvName);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_me_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('me/edit.html.twig', [
            'me' => $me,
            'form' => $form,
        ]);
    }
}
