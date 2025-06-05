<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\JobForm;
use App\Repository\JobCategoryRepository;
use App\Repository\JobRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/job')]
final class JobController extends AbstractController
{
    #[Route(name: 'app_job_index', methods: ['GET'])]
    public function index(Request $request, JobRepository $jobRepository, JobCategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        // Get filter parameters
        $categoryId = $request->query->get('category');
        $country = $request->query->get('country');
        $remote = $request->query->has('remote');
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = 10; // Jobs per page
        
        // Build query based on filters
        $queryBuilder = $jobRepository->createQueryBuilder('j')
            ->leftJoin('j.company', 'c')
            ->leftJoin('j.jobType', 'jt')
            ->leftJoin('j.categories', 'jc');
        
        if ($categoryId) {
            $queryBuilder->andWhere('jc.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }
        
        if ($country) {
            $queryBuilder->andWhere('j.country = :country')
                ->setParameter('country', $country);
        }
        
        if ($remote) {
            $queryBuilder->andWhere('j.remoteAllowed = :remote')
                ->setParameter('remote', true);
        }
        
        // Get total count for pagination
        $totalJobs = count($queryBuilder->getQuery()->getResult());
        $pageCount = ceil($totalJobs / $limit);
        
        // Add pagination
        $queryBuilder->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);
        
        $jobs = $queryBuilder->getQuery()->getResult();
        
        // Get all categories for the filter
        $categories = $categoryRepository->findAll();
        
        // Get all unique countries for the filter
        $countriesQuery = $entityManager->createQuery('SELECT DISTINCT j.country FROM App\Entity\Job j ORDER BY j.country ASC');
        $countries = array_column($countriesQuery->getArrayResult(), 'country');
        
        // Prepare pagination data
        $pagination = [
            'currentPage' => $page,
            'pageCount' => $pageCount,
            'routeParams' => array_filter([
                'category' => $categoryId,
                'country' => $country,
                'remote' => $remote ? 'on' : null,
            ])
        ];

        return $this->render('job/index.html.twig', [
            'jobs' => $jobs,
            'categories' => $categories,
            'countries' => $countries,
            'pagination' => $pagination,
        ]);
    }

    #[Route('/new', name: 'app_job_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $job = new Job();
        $form = $this->createForm(JobForm::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($job);
            $entityManager->flush();

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/new.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_show', methods: ['GET'])]
    public function show(Job $job, JobRepository $jobRepository): Response
    {
        // Find related jobs (same category or same company, excluding current job)
        $relatedJobs = $jobRepository->createQueryBuilder('j')
            ->leftJoin('j.categories', 'c')
            ->leftJoin('j.company', 'comp')
            ->where('j.id != :currentJobId')
            ->andWhere('comp.id = :companyId OR c.id IN (:categoryIds)')
            ->setParameter('currentJobId', $job->getId())
            ->setParameter('companyId', $job->getCompany()->getId())
            ->setParameter('categoryIds', $job->getCategories()->map(fn($category) => $category->getId())->toArray())
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();

        return $this->render('job/show.html.twig', [
            'job' => $job,
            'relatedJobs' => $relatedJobs,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_job_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JobForm::class, $job);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('job/edit.html.twig', [
            'job' => $job,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_job_delete', methods: ['POST'])]
    public function delete(Request $request, Job $job, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$job->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($job);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_job_index', [], Response::HTTP_SEE_OTHER);
    }
}
