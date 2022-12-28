<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

use App\Models\Event;
use App\Models\User;

interface EventRepositoryInterface
{
    public function getById(int $id): Event;
    public function getEventUsers(int $id): User;
    public function getFutureEvents(Builder $reservedPeople): Collection;
    public function getPastEvents(Builder $reservedPeople): Collection;
    public function create(array $requestData): Event;
    public function update(array $requestData, int $id): Event;
}