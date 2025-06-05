<?php

namespace App\Repository;

use App\Entity\Job;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Job>
 *
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
     * Search for jobs by keywords in title and description
     *
     * @param string $query Search query
     * @return Job[] Returns an array of Job objects
     */
    public function searchByQuery(string $query)
    {
        // Create a QueryBuilder instance
        $qb = $this->createQueryBuilder('j');

        // Split the query into words
        $keywords = explode(' ', trim($query));
        
        if (empty($keywords) || empty($keywords[0])) {
            return [];
        }
        
        // Build OR conditions for each keyword
        $orX = $qb->expr()->orX();
        
        foreach ($keywords as $key => $keyword) {
            $keyword = trim($keyword);
            if (empty($keyword)) continue;
            
            $paramName = 'keyword' . $key;
            
            // Add conditions for title and description
            $orX->add($qb->expr()->like('j.title', ':' . $paramName));
            $orX->add($qb->expr()->like('j.description', ':' . $paramName));
            
            // Set parameter
            $qb->setParameter($paramName, '%' . $keyword . '%');
        }
        
        // Add the conditions to the query
        $qb->andWhere($orX);
        
        // Join necessary related entities
        $qb->leftJoin('j.company', 'c')
           ->leftJoin('j.jobType', 'jt')
           ->leftJoin('j.categories', 'jc');
        
        // Order by most relevant first (simplified)
        $qb->orderBy('j.id', 'DESC');
        
        return $qb->getQuery()->getResult();
    }
}
