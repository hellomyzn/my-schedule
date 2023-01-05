<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;

class ReservationController extends Controller
{
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

        return view('users.reservations.event-detail', 
            compact([
                'event', 
                'eventDate',
                'startTime',
                'endTime',
            ]));
    }
}
