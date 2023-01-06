<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

use App\Repositories\Interfaces\ReservationRepositoryInterface;

class ReservationService
{    
    /**
     * eventRepo
     *
     * @var EventRepositoryInterface
     */
    protected $reservationRepo;

    /**
     * __construct
     *
     * @param  ReservationRepositoryInterface
     * @return void
     */
    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepo = $reservationRepository;
    }


    public static function createReservationArrayByUsers(Collection $users): array
    {
        $reservations = [];

        foreach($users as $user)
        {
            $reservedData = [
                'name' => $user->name,
                'number_of_people' => $user->pivot->number_of_people,
                'canceled_date' => $user->pivot->canceled_date
            ];

            if (is_null($reservedData['canceled_date']))
            {
                array_push($reservations, $reservedData);
            }
        }

        return $reservations;
    }
    
    /**
     * getReservablePeople
     *
     * @param  Event $event
     * @return int
     */
    public function getNumReservablePeople(object $event): int
    {
        $reservedPeople = $this->reservationRepo->getFirstReservedPeopleByEventId($event->id);

        if(!is_null($reservedPeople)){
            $reservablePeople = $event->max_people - $reservedPeople->number_of_people;
        } else {
            $reservablePeople = $event->max_people; 
        }

        return $reservablePeople;

    }
    
    /**
     * getNumReservedPeople
     *
     * @param  Event $event
     * @return int
     */
    public function getNumReservedPeople(object $event): int
    {
        $reservedPeople = $this->reservationRepo->getFirstReservedPeopleByEventId($event->id);
        if (is_null($reservedPeople)){
            return 0;
        } else {
            $numReservedPeople = $reservedPeople->number_of_people;
            return $numReservedPeople;
        }
    }
    
    /**
     * create
     *
     * @param  Request $request
     * @return Model
     */
    public function create(Request $request): Model
    {
        $requestData = [
            'user_id' => Auth::id(),
            'event_id' => $request->id,
            'number_of_people' => $request->reservablePeople,
        ];

        $reservation = $this->reservationRepo->create($requestData);

        return $reservation;
    }
}