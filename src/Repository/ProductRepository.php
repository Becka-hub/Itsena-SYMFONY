<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Product $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Product $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    
    public function searchCart($id,$min,$max,$produit)
    {
        if($min!==null && $max !==null && $produit!==null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->andWhere('p.price > :min')
            ->setParameter('min', $min)
            ->andWhere('p.price < :max')
            ->setParameter('max', $max)
            ->andWhere('p.libelle LIKE :name')
            ->setParameter('name','%'.$produit.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        }elseif($min===null && $max ===null && $produit===null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        }elseif($min!==null && $max ===null && $produit===null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->andWhere('p.price > :min')
            ->setParameter('min', $min)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
        }elseif($min===null && $max !==null && $produit===null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->andWhere('p.libelle LIKE :name')
            ->setParameter('name','%'.$produit.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
        }elseif($min===null && $max ===null && $produit!==null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->andWhere('p.libelle LIKE :name')
            ->setParameter('name','%'.$produit.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
        }elseif($min!==null && $max !==null && $produit===null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->andWhere('p.price > :min')
            ->setParameter('min', $min)
            ->andWhere('p.price < :max')
            ->setParameter('max', $max)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        }elseif($min!==null && $max ===null && $produit!==null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->andWhere('p.price > :min')
            ->setParameter('min', $min)
            ->andWhere('p.libelle LIKE :name')
            ->setParameter('name','%'.$produit.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        }elseif($min===null && $max !==null && $produit!==null){
            return $this->createQueryBuilder('p')  
            ->where('p.category=:id')
            ->setParameter('id', $id)
            ->andWhere('p.price < :max')
            ->setParameter('max', $max)
            ->andWhere('p.libelle LIKE :name')
            ->setParameter('name','%'.$produit.'%')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        }else{
            return $this->createQueryBuilder('p')  
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
        }
        
    }


    public function search($criteria,$id)
    {
        return $this->createQueryBuilder('p')  
        ->where('p.libelle LIKE :name')
        ->setParameter('name','%'.$criteria.'%')
        ->andWhere('p.category =:id')
        ->setParameter('id', $id)
        ->orderBy('p.id', 'ASC')
        ->getQuery()
       ->getResult()
       ;
    }

    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
