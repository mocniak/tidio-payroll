<?php

namespace App\Repository;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineEmployeeRepository implements EmployeeRepository
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Employee::class);
    }

    public function add(Employee $employee): void
    {
        $this->entityManager->persist($employee);
        $this->entityManager->flush();
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function removeAll(): void
    {
        foreach ($this->repository->findAll() as $part) {
            $this->entityManager->remove($part);
        }
        $this->entityManager->flush();
    }
}
