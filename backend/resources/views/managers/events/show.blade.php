<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            イベント詳細
        </h2>
    </x-slot>

    <div class="pt-4 pb-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl py-4 mx-auto">
                    <x-jet-validation-errors class="mb-4" />

                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
            
                    <form method="get" action="{{ route('managers.events.edit', $event->id) }}">
            
                        <div>
                            <x-jet-label for="event_name" value="イベント名" />
                            {{ $event->name }}
                        </div>
                        <div class="mt-4">
                            <x-jet-label for="information" value="イベント詳細" />
                            {!! nl2br(e($event->information)) !!}
                        </div>
            
                        <div class="md:flex justify-between">
                            <div class="mt-4 mr-8">
                                <x-jet-label for="event_date" value="日付" />
                                {{ $eventDate }}
                            </div> 

                            <div class="mt-4 mr-8">
                                <x-jet-label for="start_time" value="開始時間" />
                                {{ $startTime }}
                            </div>

                            <div class="mt-4">
                                <x-jet-label for="end_time" value="終了時間" />
                                {{ $endTime }}
                            </div>
                        </div>

                        <div class="md:flex  justify-between items-end">
                            <div class="mt-4">
                                <x-jet-label for="max_people" value="定員数" />
                                {{ $event->max_people }}
                            </div>
                            <div class="flex space-x-4 justify-around">
                                @if($event->is_visible)
                                    表示中
                                @else
                                    非表示
                                @endif
                            </div>
                            @if($event->eventDate >= $today)
                                <x-jet-button class="ml-4">
                                    編集する
                                </x-jet-button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="max-w-2xl py-4 mx-auto">
                    @if( !$reservedUsers->isEmpty() )
                        予約状況
                        @foreach ($reservations as $reservation)
                            {{ $reservation['name'] }}
                            {{ $reservation['number_of_people'] }}
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
 