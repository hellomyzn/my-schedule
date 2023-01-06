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

                    <form id="cancel_{{ $event->id }}" method="post" action="{{ route('mypage.cancel', $event->id) }}">
                        @csrf
                        <div class="md:flex  justify-between items-end">
                            <div class="mt-4">
                                <x-jet-label  value="予約人数" />
                                {{ $reservation->number_of_people }}
                            </div>
                            @if($event->eventDate >= $today)
                                <a href="#" data-id="{{ $event->id }}" onclick="cancelPost(this)" class="ml-4 bg-black text-white py-2 px-4" >
                                    キャンセルする
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function cancelPost(e) {
            'use script';
            if (confirm('本当にキャンセルしてもよろしいですか？')){
                document.getElementById('cancel_' + e.dataset.id).submit();
            }
        }
    </script>
</x-app-layout>
 