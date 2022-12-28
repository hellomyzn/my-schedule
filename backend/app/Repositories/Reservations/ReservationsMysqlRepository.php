<?php

namespace App\Repositories\Reservations;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Carbon\Carbon;

use App\Models\Reservation;
use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationsMysqlRepository implements ReservationRepositoryInterface
{
    
    /**
     * model
     *
     * @var Reservation
     */
    protected $model;

    public function __construct(Reservation $reservation)
    {
        $this->model = $reservation;
    }
    
    /**
     * getReservedPeople
     *
     * @return Builder
     */
    public function getReservedPeople(): Builder
    {
        try {
            $reservedPeople = DB::table('reservations')
                ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
                ->whereNull('canceled_date')
                ->groupBy('event_id');

            return $reservedPeople;
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
}