<?php

/**
 * TrickRepository File Doc Comment
 *
 * PHP Version 8.3.1
 *
 * @category Repository
 * @package  App\Repository
 * @author   Marine Sanson <marine_sanson@yahoo.fr>
 * @license  https://opensource.org/licenses/gpl-license.php GNU Public License
 */

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * TrickRepository Class Doc Comment
 *
 * @extends ServiceEntityRepository<Trick>
 *
 * @category Repository
 * @package  App\Repository
 * @author   Marine Sanson <marine_sanson@yahoo.fr>
 * @license  https://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    /**
     * Summary of function __construct
     *
     * @param ManagerRegistry $registry ManagerRegistry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    /**
     * Summary of findTricksPaginated
     *
     * @param int $page Page
     * @param int $limit Limit
     * 
     * @return array
     */
    public function findTricksPaginated(int $page, int $limit = 4): array
    {
        $limit = abs($limit);

        $result = [];

        $query = $this->getEntityManager()->createQueryBuilder()
            ->select('trick')
            ->from('App\Entity\Trick', 'trick')
            ->setMaxResults($limit)
            ->setFirstResult(($page * $limit) - $limit)
            ->orderBy('trick.id', 'DESC');
        
            $paginator = new Paginator($query);
            $tricks = $paginator->getQuery()->getResult();

            if(empty($tricks)){
                return $result;
            }

            $pages = ceil($paginator->count() / $limit);

            $result['tricks'] = $tricks;
            $result['pages'] = $pages;
            $result['page'] = $page;
            $result['limit'] = $limit;
        
        return $result;
    }

    /**
     * Summary of saveTrick
     *
     * @param Trick $trick Trick
     * 
     * @return void
     */
    public function saveTrick(Trick $trick): void
    {
        $this->getEntityManager()->persist($trick);
        $this->getEntityManager()->flush();
    }

    /**
     * Summary of delete
     *
     * @param Trick $trick Trick
     * 
     * @return void
     */
    public function delete(Trick $trick): void
    {
        $this->getEntityManager()->remove($trick);
        $this->getEntityManager()->flush();
    }

//    /**
//     * @return Trick[] Returns an array of Trick objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Trick
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
