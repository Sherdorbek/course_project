<?php

namespace App\Repository;

use App\Entity\AttributeCv;
use App\Entity\UserAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserAttribute>
 */
class UserAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAttribute::class);
    }

    public function numericAggregates(int $attribute, array $users): array
    {
        return $this->createQueryBuilder('ua')
            ->select('MIN(ua.valNumber) AS min', 'MAX(ua.valNumber) AS max', 'AVG(ua.valNumber) AS avg')
            ->where('ua.attribute = :attr')
            ->andWhere('ua.valNumber IS NOT NULL')
            ->andWhere('IDENTITY(ua.user) IN (:users)')
            ->setParameter('attr', $attribute)
            ->setParameter('users', $users)
            ->getQuery()
            ->getSingleResult();
    }

    public function  popularString(int $attribute, array $users): array
    {
        $row = $this->createQueryBuilder('ua')
            ->select('ua.valString AS popular', 'COUNT(ua.id) AS cnt')
            ->where('ua.attribute = :attr')
            ->andWhere('ua.valString IS NOT NULL')
            ->andWhere('IDENTITY(ua.user) IN (:users)')
            ->setParameter('attr', $attribute)
            ->setParameter('users', $users)
            ->groupBy('ua.valString')
            ->orderBy('cnt', 'DESC')
            ->addOrderBy('ua.valString', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return ['popular' => $row['popular'] ?? null];
    }

    public function dropdownPopular(int $attribute, array $oneOfManies, array $users): ?array
    {
        $row = $this->createQueryBuilder('ua')
            ->select('ua.valDropdown AS popular', 'COUNT(ua.id) AS cnt')
            ->where('ua.attribute = :attr')
            ->andWhere('ua.valDropdown IS NOT NULL')
            ->andWhere('IDENTITY(ua.user) IN (:users)')
            ->setParameter('attr', $attribute)
            ->setParameter('users', $users)
            ->groupBy('ua.valDropdown')
            ->orderBy('cnt', 'DESC')
            ->addOrderBy('ua.valDropdown', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        $val = null;
        foreach ($oneOfManies as $option) {
            if (!empty($option)){
                break;
            }
            if ($option['id'] == $row['popular']) {
                $val  = $option['value'];
                break;
            }
        }
        return ['popular' => $val];
    }
}
