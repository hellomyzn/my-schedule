<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Query\Builder;

interface ReservationRepositoryInterface
{
    public function getReservedPeople(): Builder;
}