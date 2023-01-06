<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\Reservation;
use App\Services\ReservationService;

class ReservationController extends Controller
{
    /**
     * eventRepo
     *
     * @var ReservationService
     */
    protected $reservationService;

    /**
     * __construct
     *
     * @param  ReservationRepositoryInterface
     * @return void
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }
        
    /**
     * dashboard
     *
     * @return void
     */
    public function dashboard()
    {
        return view('users.reservations.dashboard');
    }
    
    /**
     * detail
     *
     * @param  mixed $id
     * @return void
     */
    public function detail($id)
    {
        $event = Event::findOrFail($id);
        $eventDate = $event->eventDate;
        $startTime = $event->startTime;
        $endTime = $event->endTime;
        $reservablePeople = $this->reservationService->getNumReservablePeople($event);
        
        return view('users.reservations.event-detail', 
            compact([
                'event', 
                'eventDate',
                'startTime',
                'endTime',
                'reservablePeople'
            ]));
    }
    
    /**
     * reserve
     *
     * @param  mixed $request
     * @return void
     */
    public function reserve(Request $request)
    {
        $event = Event::findOrFail($request->id);
        $numReservablePeople = $this->reservationService->getNumReservedPeople($event);
        $reservedPeopleAndRequestNumber = $numReservablePeople + $request->reservablePeople;

        if($event->max_people >= $reservedPeopleAndRequestNumber ){
            $this->reservationService->create($request);
            session()->flash('status', '予約が完了しました。');
            return to_route('dashboard');
        } else{
            session()->flash('status', 'この人数は予約できません。');
            return to_route('dashboard');
        }
    }
}
