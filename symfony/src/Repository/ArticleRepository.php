<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticleByCategory(Request $request): array
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where('a.archived = :false');
        $qb->setParameter('false', false);
        $qb->leftJoin('a.categories', 'c');

        if ($search = $request->request->all()['search']['keyword'] ?? $request->get('searchTerm') ?? false) {
            $qb->andWhere('a.title LIKE :search or a.titleTranslator LIKE :search or c.name LIKE :search or c.nameTranslate LIKE :search');
            $qb->setParameter('search', '%'.$search.'%');
        }
        if ($communes = $request->request->all()['search']['location'] ?? $request->get('location') ?? false) {
            $qb->innerJoin('a.communes', 'co');
            $qb->andWhere('co IN (:communes)');
            $qb->setParameter('communes', $communes);
        }

        if ( $type = $request->get('type')) {
            $qb->andWhere('c.type = :type');
            $qb->setParameter('type', $type);
        }

        return $qb->getQuery()->getResult();
    }
}
