<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DoctrineDepartmentRepository implements DepartmentRepository
{
    private EntityManagerInterface $entityManager;
    private EntityRepository $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository(Department::class);
    }
    public function add(Department $department): void
    {
        $this->entityManager->persist($department);
        $this->entityManager->flush();
    }

    public function getByName(string $departmentName): Department
    {
        $bike = $this->repository->findOneBy(['name' => $departmentName]);
        if ($bike === null) {
            throw new \RuntimeException('Department with the name ' . $departmentName . ' not found');
        }
        return $bike;
    }

    public function get(string $id): Department
    {
        return $this->repository->find($id);
    }

    public function removeAll(): void
    {
        foreach ($this->repository->findAll() as $part) {
            $this->entityManager->remove($part);
        }
        $this->entityManager->flush();
    }
}
