<?php

namespace App\Repository;

use App\Entity\Word;
use App\Interface\WordRepositoryInterface;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class WordRepository extends ServiceEntityRepository implements WordRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, Word::class);
    }

    public function save(Word $entity, bool $flush = false): void
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function remove(Word $entity, bool $flush = false): void
    {
        $this->entityManager->remove($entity);

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByName(string $name, string $source): ?Word
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.name = :name')
            ->andWhere('w.source = :source')
            ->setParameters(
                [
                    'name' => $name,
                    'source' => $source
                ]
            )
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    public function create(array $values): Word
    {
        $word = new Word();
        $word->setName($values['name']);
        $word->setSource($values['source']);
        $word->setPopularityScore($values['score']);
        $word->setPositiveCount($values['positive_count']);
        $word->setNegativeCount($values['negative_count']);
        $word->setTotalCount($values['total_count']);
        $word->setCreatedAt(Carbon::now());

        $this->save($word, true);

        return $word;
    }
}
