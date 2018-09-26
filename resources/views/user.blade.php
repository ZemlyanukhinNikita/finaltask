@include('layouts.app')
@foreach(['danger', 'success'] as $status)

    @if (session()->has($status))
        <div class="alert alert-{{$status}}">
            {{ session()->get($status) }}
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container">
    <div class="row">
        <div class="col-6">
            <h4><a href="/users">Вернуться к пользователям</a></h4>
            {{$user->first_name}}
            {{$user->last_name}} <br>
            Баланс: {{$user->balance}}<br>

            Перевести деньги:<br>
            Кому:<br>


            {!! Form::open(['action' => 'TransferController@store']) !!}
            @csrf

            <ul>

                @foreach($other_users as $otherUser)
                    <div>
                        <label>
                            <input type="radio" value="{{ $otherUser->id }}" name="receiverId" required="required"/>
                            {{ $otherUser->first_name }}
                            {{ $otherUser->last_name }}
                        </label>
                    </div>
                @endforeach

                <input type="hidden" name="senderId" value="{{$user->id}}">

                Сумма перевода:
                <input style='width: 200px' type="number" required class="form-control" placeholder="Сумма"
                       name="amount"
                       step="0.5"
                       min="0.5"
                       max="1000000">
                <br>
                Дата и время перевода:<br><br>
                <input
                    name="dateTime"
                    id="datetime-local"
                    type="datetime-local"
                    step=3600 format="YYYY-MM-DD H:00">
                <br>
                <input type="submit" class="btn btn-primary" value="Отправить">
            </ul>
            {!! Form::close() !!}

        </div>
        <div class="col-6">
            @foreach($transfers as $transfer)
                <div>
                    @if($transfer->status_id == 3)

                        <p class="alert-info">Запланированная транзакция:</p> Перевод
                        пользователю {{$transfer->receiver->first_name}}<br>
                        Сумма перевода: {{$transfer->amount}}<br>
                        Дата и время перевода: {{$transfer->scheduled_time}}
                        {!! Form::open(['method'=>'delete','action'=>'TransferController', 'route' => ['transfers.destroy',$transfer->id]]) !!}
                        <input type="submit" class="btn btn-primary" value="Отменить">
                        {!! Form::close()!!}
                    @endif
                </div>

                <div>
                    @if($transfer->status_id == 1)
                        <p class="alert-success">Совершенная транзакция:</p>  Перевод
                        пользователю {{$transfer->receiver->first_name}}<br>
                        Сумма перевода: {{$transfer->amount}}<br>
                        Дата и время перевода: {{$transfer->scheduled_time}}
                    @endif
                </div>

                <div>
                    @if($transfer->status_id == 2)
                        <p class="alert-danger">Невыполненная транзакция:</p> Перевод
                        пользователю {{$transfer->receiver->first_name}}<br>
                        Сумма перевода: {{$transfer->amount}}<br>
                        Дата и время перевода: {{$transfer->scheduled_time}}
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
</div>
