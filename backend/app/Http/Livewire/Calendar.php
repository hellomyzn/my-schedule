<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Carbon\CarbonImmutable;

use App\Models\Event;
use App\Services\EventService;

class Calendar extends Component
{   
    /**
     * currentDate
     *
     * @var mixed
     */
    public $currentDate;    
    
    /**
     * currentWeek
     *
     * @var mixed
     */
    public $currentWeek;
    
    /**
     * day
     *
     * @var mixed
     */
    public $day;
    
    
    /**
     * checkDay
     *
     * @var mixed
     */
    public $checkDay;
    
    /**
     * dayOfWeek
     *
     * @var mixed
     */
    public $dayOfWeek;

    /**
     * day
     *
     * @var mixed
     */
    public $seventDaysLater;

    /**
     * day
     *
     * @var mixed
     */
    public $events;
    
    
    /**
     * eventsOnCalendar
     *
     * @var mixed
     */
    public $eventsOnCalendar;

    
    /**
     * countTimeBoxes
     *
     * @param  mixed $reserved_event
     * @return int
     */
    protected function countTimeBoxes(object $reserved_event): int
    {
        $s_date = $reserved_event->start_date;
        $e_date = $reserved_event->end_date;
        $diff = CarbonImmutable::parse($s_date)->diffInMinutes($e_date);
        $interval = 30;                    
        $timeBoxes =  $diff / $interval - 1;

        return $timeBoxes;
    }

    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->currentDate = CarbonImmutable::today();
        $this->seventDaysLater = $this->currentDate->addDays(7);
        $this->currentWeek = [];
        $this->eventsOnCalendar = [];
        
        $this->events = EventService::getWeekEvents(
            $this->currentDate->format('Y-m-d'),
            $this->seventDaysLater->format('Y-m-d'),
        );

        for ($i = 0; $i < 7; $i++){
            $this->date_jp = $this->currentDate->addDays($i)->format('m月d日');
            $this->date = $this->currentDate->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = $this->currentDate->addDays($i)->dayName;
            array_push($this->currentWeek, [
                'date_jp' => $this->date_jp, 
                'date' => $this->date, 
                'dayOfWeek' => $this->dayOfWeek
            ]);
            
            for ($j = 0; $j < 21; $j++){
                $dateTime =  $this->date . " " . Event::EVENT_TIME[$j];
                // return ObjectSTD object or Null
                $reserved_event = $this->events->firstWhere('start_date', $dateTime);
                $this->eventsOnCalendar[$i][$j] = $reserved_event;

                // If reserved events are more thant 30 mins
                if (!is_null($reserved_event)){
                    $timeBoxes = $this->countTimeBoxes($reserved_event);
                    if ($timeBoxes > 0){
                        for ($k = 1; $k <= $timeBoxes; $k++){
                            $this->eventsOnCalendar[$i][$j+$k] = "reserved";
                        }
                    }
                    $j += $timeBoxes;
                }
            }   
        }
    }

    public function getDate($date)
    {
        $this->currentDate = $date;
        $this->currentWeek = [];
        $this->seventDaysLater = CarbonImmutable::parse($this->currentDate)->addDays(7);

        $this->events = EventService::getWeekEvents(
            CarbonImmutable::parse($this->currentDate)->format('Y-m-d'),
            $this->seventDaysLater->format('Y-m-d'),
        );
        
        for ($i = 0; $i < 7; $i++){
            
            $this->date_jp = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('m月d日');
            $this->date = CarbonImmutable::parse($this->currentDate)->addDays($i)->format('Y-m-d');
            $this->dayOfWeek = CarbonImmutable::parse($this->currentDate)->addDays($i)->dayName;
            array_push($this->currentWeek, [
                'date_jp' => $this->date_jp, 
                'date' => $this->date, 
                'dayOfWeek' => $this->dayOfWeek
            ]);

            for ($j = 0; $j < 21; $j++){
                $dateTime =  $this->date . " " . Event::EVENT_TIME[$j];
                // return ObjectSTD object or Null
                $reserved_event = $this->events->firstWhere('start_date', $dateTime);
                $this->eventsOnCalendar[$i][$j] = $reserved_event;

                // If reserved events are more thant 30 mins
                if (!is_null($reserved_event)){
                    $timeBoxes = $this->countTimeBoxes($reserved_event);
                    if ($timeBoxes > 0){
                        for ($k = 1; $k <= $timeBoxes; $k++){
                            $this->eventsOnCalendar[$i][$j+$k] = "reserved";
                        }
                    }
                    $j += $timeBoxes;
                }
            }   
        }
    }
    
    public function render()
    {
        return view('livewire.calendar');
    }
}
