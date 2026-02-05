<?php

namespace App\Repositories\Interfaces;

use App\Models\Company;

interface CompanyRepositoryInterface
{
    public function findBySlug(string $slug): ?Company;
    public function create(array $data): Company;
    public function update(Company $company, array $data): bool;
    public function delete(Company $company): bool;
    public function getAll();
    public function findById(int $id): ?Company;
}