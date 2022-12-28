<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ReservationService
{    
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
}