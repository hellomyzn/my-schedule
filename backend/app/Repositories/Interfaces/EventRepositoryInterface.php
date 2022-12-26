<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;

interface EventRepositoryInterface
{
    public function getById(int $id): Event;
    public function getAllOrderByStartDateAsc(): object;
    public function create(array $requestData): Event;
}