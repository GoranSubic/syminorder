<?php

namespace App\Api;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Order;
use Doctrine\ORM\QueryBuilder;

/*
 * Example query extension for Orders - Goran by book:
 * 26.4 Restricting Comments exposed by the API - Symfony 5 The Fast Track...
 * This query extension class applies its logic only for the Order resource
 * and modify Doctrine query builder to only consider orders with CART status
 */
class FilterActiveOrdersQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        // TODO: Implement applyToCollection() method.
        if(Order::class === $resourceClass) {
            $queryBuilder->andWhere(sprintf("%s.status = 'cart'", $queryBuilder->getRootAliases()[0]));
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        // TODO: Implement applyToItem() method.
        if(Order::class === $resourceClass) {
            $queryBuilder->andWhere(sprintf("%s.status = 'cart'", $queryBuilder->getRootAliases()[0]));
        }
    }
}