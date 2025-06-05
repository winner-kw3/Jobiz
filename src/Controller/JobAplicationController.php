<?php

namespace App\Controller;

use App\Entity\JobAplication;
use App\Form\JobAplicationForm;
use App\Repository\JobAplicationRepository;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/job/apply', name: 'app_job_aplication_apply', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function apply(Request $request, EntityManagerInterface $entityManager, JobRepository $jobRepository): Response
    {
        // CSRF token validation
        if (!$this->isCsrfTokenValid('job_application', $request->request->get('_csrf_token'))) {
            $this->addFlash('error', 'Token CSRF invalide. Veuillez réessayer.');
            return $this->redirectToRoute('app_home');
        }
        
        // Get form data with validation
        $jobId = $request->request->get('job_id');
        $coverLetter = $request->request->get('cover_letter');
        
        if (!$jobId) {
            $this->addFlash('error', 'Aucun emploi spécifié. Veuillez réessayer.');
            return $this->redirectToRoute('app_job_index');
        }
        
        if (!$coverLetter || strlen(trim($coverLetter)) < 10) {
            $this->addFlash('error', 'Veuillez fournir une lettre de motivation significative (au moins 10 caractères).');
            return $this->redirectToRoute('app_job_show', ['id' => $jobId]);
        }
        
        // Check if job exists
        $job = $jobRepository->find($jobId);
        if (!$job) {
            $this->addFlash('error', 'L\'offre d\'emploi pour laquelle vous postulez n\'existe pas.');
            return $this->redirectToRoute('app_job_index');
        }
        
        // Check if user already applied for this job
        $existingApplication = $entityManager->getRepository(JobAplication::class)->findOneBy([
            'user' => $this->getUser(),
            'job' => $job
        ]);
        
        if ($existingApplication) {
            $this->addFlash('error', 'Vous avez déjà postulé pour cette position.');
            return $this->redirectToRoute('app_job_show', ['id' => $jobId]);
        }
        
        try {
            // Create new application
            $application = new JobAplication();
            $application->setUser($this->getUser());
            $application->setJob($job);
            $application->setCoverLetter($coverLetter);
            $application->setCreatedAt(new \DateTimeImmutable());
            
            // Save to database
            $entityManager->persist($application);
            $entityManager->flush();
            
            // Add success flash message
            $this->addFlash('success', 'Votre candidature a été soumise avec succès ! Nous vous contacterons prochainement.');
        } catch (\Exception $e) {
            // Log the error (ideally to a proper logging system)
            error_log($e->getMessage());
            
            // Notify the user without revealing technical details
            $this->addFlash('error', 'Une erreur s\'est produite lors de l\'envoi de votre candidature. Veuillez réessayer plus tard.');
        }
        
        // Redirect to job details page
        return $this->redirectToRoute('app_job_show', ['id' => $jobId]);
    }
}
