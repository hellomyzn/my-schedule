<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;

interface EventRepositoryInterface
{
    public function getById(int $id): Event;
    public function getFutureEvents(): object;
    public function getPastEvents(): object;
    public function create(array $requestData): Event;
    public function update(array $requestData, int $id): Event;
}