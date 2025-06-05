<?php

namespace App\Controller;

use App\Entity\JobAplication;
use App\Form\JobAplicationForm;
use App\Repository\JobAplicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job/aplication')]
final class JobAplicationController extends AbstractController
{
    #[Route(name: 'app_job_aplication_index', methods: ['GET'])]
    public function index(JobAplicationRepository $jobAplicationRepository): Response
    {
        return $this->render('job_aplication/index.html.twig', [
            'job_aplications' => $jobAplicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_job_aplication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $jobAplication = new JobAplication();
        $form = $this->createForm(JobAplicationForm::class, $jobAplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jobAplication);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_aplication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job_aplication/new.html.twig', [
            'job_aplication' => $jobAplication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_aplication_show', methods: ['GET'])]
    public function show(JobAplication $jobAplication): Response
    {
        return $this->render('job_aplication/show.html.twig', [
            'job_aplication' => $jobAplication,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_aplication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JobAplication $jobAplication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobAplicationForm::class, $jobAplication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_job_aplication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job_aplication/edit.html.twig', [
            'job_aplication' => $jobAplication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_aplication_delete', methods: ['POST'])]
    public function delete(Request $request, JobAplication $jobAplication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$jobAplication->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($jobAplication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_job_aplication_index', [], Response::HTTP_SEE_OTHER);
    }
}
