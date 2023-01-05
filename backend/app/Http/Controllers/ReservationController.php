<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Models\Event;

class ReservationController extends Controller
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
    public function __construct(
        ReservationRepositoryInterface $reservationRepository
    )
    {
        $this->reservationRepo = $reservationRepository;
    }


    public function dashboard()
    {
        return view('users.reservations.dashboard');
    }

    public function detail($id)
    {
        $event = Event::findOrFail($id);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;

        $reservedPeople = $this->reservationRepo->getReservedPeople();
        $reservedPeople = $reservedPeople->having('event_id', $event->id)->first();

        if(!is_null($reservedPeople)){
            $reservablePeople = $event->max_people - $reservedPeople->number_of_people;
        } else {
            $reservablePeople = $event->max_people; 
        }
        
        

        return view('users.reservations.event-detail', 
            compact([
                'event', 
                'eventDate',
                'startTime',
                'endTime',
                'reservablePeople'
            ]));
    }
}
