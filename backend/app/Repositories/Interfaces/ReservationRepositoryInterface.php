<?php

namespace App\Repositories\Interfaces;

interface ReservationRepositoryInterface
{
    public function getReservationByUserIdAndEventId(int $user_id, int $event_id);
    public function getReservedPeople();
    public function getFirstReservedPeopleByEventId(int $event_id);
    public function create(array $requestData);
    public function cancel(int $id);
}