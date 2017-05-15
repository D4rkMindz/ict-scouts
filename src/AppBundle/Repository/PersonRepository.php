<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class PersonRepository extends EntityRepository
{
    public function findAllUsers()
    {
        $alias = 'p';

        $queryBuilder = $this->createQueryBuilder($alias);
        $queryExpr = $queryBuilder->expr();

        $queryBuilder->join(User::class, 'u');

        $queryBuilder->andWhere(
            $queryExpr->eq(sprintf('%s.id', $alias), 'u.person')
        );

        return $queryBuilder
                    ->getQuery()
                    ->getResult();
    }
}
