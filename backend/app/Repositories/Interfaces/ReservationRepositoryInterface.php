<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Query\Builder;
use \stdClass;

interface ReservationRepositoryInterface
{
    public function getReservedPeople();
    public function getFirstReservedPeopleByEventId(int $event_id);
    public function create(array $requestData);
}