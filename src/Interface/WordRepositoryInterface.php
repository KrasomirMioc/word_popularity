<?php

namespace App\Interface;

use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;

interface WordRepositoryInterface extends ServiceEntityRepositoryInterface
{
    public function save(Word $entity, bool $flush = false): void;
    public function remove(Word $entity, bool $flush = false): void;
    public function findOneByName(string $name, string $source): ?Word;
    public function create(array $values): Word;
}