@include('layouts.app')
@if($users)
    <ul>
        @foreach($users as $user)<br>
        <li><a href="/users/{{$user->id}}">{{$user->senderFirstName}} {{$user->senderLastName}}</a></li>
        @if($user->amount)
            Информация о последней транзакции:<br>
            Сумма перевода: {{$user->amount}} <br>
            Назначенное время: {{$user->scheduled_time}} <br>
            Кому: <br>
            {{$user->receiverFirstName}} {{$user->receiverLastName}}
        @else Пользователь еще не совершал транзакций.
        @endif<br>
        @endforeach

    </ul>
@endif
