<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function getReservedPeople();
    public function getFirstReservedPeopleByEventId(int $event_id);
    public function create(array $requestData);
}