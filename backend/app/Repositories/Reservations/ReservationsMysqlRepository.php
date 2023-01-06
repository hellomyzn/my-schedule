<?php

namespace App\Repositories\Reservations;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use stdClass;

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

    /**
     * getReservedPeopleWithEventId
     *
     * @param  mixed $event_id
     * @return stdClass or Null
     */
    public function getFirstReservedPeopleByEventId(int $event_id): ?stdClass
    {
        try {
            $reservedPeople = DB::table('reservations')
                ->select('event_id', DB::raw('sum(number_of_people) as number_of_people'))
                ->whereNull('canceled_date')
                ->groupBy('event_id')
                ->having('event_id', $event_id)
                ->first();

            return $reservedPeople;
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
    
    /**
     * create
     *
     * @param  array $requestData
     * @return Model
     */
    public function create(array $requestData): Model
    {
        try {
            return DB::transaction(function () use ($requestData) {
                $reservation = $this->model->create($requestData);

                return $reservation;
            });
        } catch(Exceptions $e) {
            \Log::error(__METHOD__.'@'.$e->getLine().': '.$e->getMessage());

            return [
                'msg' => $e->getMessage(),
                'err' => false,
            ];
        }
    }
}