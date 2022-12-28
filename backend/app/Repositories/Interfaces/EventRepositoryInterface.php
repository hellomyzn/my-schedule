<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;

use Illuminate\Database\Eloquent\Model;

interface EventRepositoryInterface
{
    public function getById(int $id): Model;
    public function getEventUsers(int $id): Collection;
    public function getFutureEvents(Builder $reservedPeople): Collection;
    public function getPastEvents(Builder $reservedPeople): Collection;
    public function create(array $requestData): Model;
    public function update(array $requestData, int $id): Model;
}