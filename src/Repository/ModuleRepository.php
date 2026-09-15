<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Module>
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    //    /**
    //     * @return Module[] Returns an array of Module objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Module
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
        public function getModule (?string $mot,int $page,int $limit,?string $categ):Paginator
        {
        $data = $this->createQueryBuilder('m')
            ->andWhere('m.titre LIKE :search')
                ->setParameter('search','%'.$mot.'%')
                ->setFirstResult(($page - 1)*$limit)
                ->setMaxResults($limit);
            if($categ && $categ != '1'){
                return new Paginator($data
                ->innerJoin('m.categorie','c')
                ->andWhere('c.id = :categ')
                ->setParameter('categ',$categ));
            }
            return new Paginator($data->getQuery());
    }
}
