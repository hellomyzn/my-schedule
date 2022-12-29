<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\Carbon;

class Calendar extends Component
{

    public $currentDate;
    public $currentWeek;
    public $day;

    public function mount()
    {
        $this->currentDate = Carbon::today();
        $this->currentWeek = [];

        for ($i = 0; $i < 7; $i++){
            $this->day = Carbon::today()->addDays($i)->format('m月d日');
            array_push($this->currentWeek, $this->day);
        }

    }
    
    public function render()
    {
        return view('livewire.calendar');
    }
}
