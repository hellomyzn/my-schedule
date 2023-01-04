<div>
    <div class="text-center text-sm">
        日付を選択してください。本日から最大30日先まで選択可能です。
    </div>
    <input id="calendar" class="block mt-1 mb-2 mx-auto" 
    type="text" name="calendar" 
    value="{{ $currentDate }}"
    wire:change="getDate($event.target.value)" />

    <div class="flex border border-green-400 mx-auto">
        <x-calendar-time />
        @for($i = 0; $i < 7; $i++)
            <div class="w-32">
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['day']}}</div>
                <div class="py-1 px-2 border border-gray-200 text-center">{{ $currentWeek[$i]['dayOfWeek']}}</div>
                @for($j = 0; $j < 21; $j++)
                    @if(!is_null($eventsOnCalendar[$i][$j]))
                        <div class="py-1 px-2 h-8 border border-gray-200 text-xs">
                            {{ $eventsOnCalendar[$i][$j]->name }}
                        </div>
                    @else
                        <div class="py-1 px-2 h-8 border border-gray-200"></div>
                    @endif
                @endfor
            </div>
        @endfor
    </div>
    
    <div class="flex">
        @for($day=0; $day < 7; $day++)
            {{ $currentWeek[$day]['day']}}
        @endfor
    </div>
    @foreach ($events as $event)
        {{ $event->start_date }} <br>
    @endforeach
</div>
