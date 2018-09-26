@include('layouts.app')
<div class="container">
    <div class="row">
        <div class="col-6">
            @if($users)
                <ul>
                    @foreach($users as $user)<br>
                    <li><a href="/users/{{$user->id}}">{{$user->senderFirstName}} {{$user->senderLastName}}</a></li>
                    @if($user->amount)
                        Информация о последней транзакции:<br>
                        Статус транзакции:
                        @if($user->status_id == 1) Выполнена<br>
                        @elseif($user->status_id == 2) Невыполнена<br>
                        @else Ожидается перевод<br>
                        @endif
                        Сумма перевода: {{$user->amount}} <br>
                        Назначенное время: {{$user->scheduled_time}} <br>
                        Кому:
                        {{$user->receiverFirstName}} {{$user->receiverLastName}}<br>
                    @else Пользователь еще не совершал транзакций.
                    @endif<br>
                    @endforeach

                </ul>
            @endif
        </div>
        <div class="col-6">

            {!! Form::open(['action' => 'TransferController@loadCsvWithAllTransfers']) !!}
            @csrf
            <p>Выгрузить все произведенные переводы</p>
            Выберите интервал времени:<br>
            С <input
                name="dateLast"
                id="datetime-local"
                type="datetime-local"
                step=3600
            >
            По
            <input
                name="dateFuture"
                id="datetime-local"
                type="datetime-local"
                step=3600
            >
            <input type="submit" class="btn btn-primary" value="Выгрузить">
            {!! Form::close() !!}
        </div>
    </div>
</div>
