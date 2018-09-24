@include('layouts.app')
<a href="/users">Вернуться к пользователям</a>
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
<div class="col-3 float-right">
    @foreach($transactions as $transaction)
        <div>
            @if($transaction->status_id == 3)

                <p class="alert-info">Запланированная транзакция:</p> Перевод
                пользователю {{$transaction->receiver->first_name}}<br>
                Сумма перевода: {{$transaction->amount}}<br>
                Дата и время перевода: {{$transaction->scheduled_time}}
                {!! Form::open(['method'=>'delete','action'=>'TransactionController@destroy', 'route' => ['transactions.destroy',$transaction->id]]) !!}
                <input type="submit" class="btn btn-primary" value="Отменить">
                {!! Form::close()!!}
            @endif
        </div>

        <div>
            @if($transaction->status_id == 1)
                <p class="alert-success">Совершенная транзакция:</p>  Перевод
                пользователю {{$transaction->receiver->first_name}}<br>
                Сумма перевода: {{$transaction->amount}}<br>
                Дата и время перевода: {{$transaction->scheduled_time}}
            @endif
        </div>

        <div>
            @if($transaction->status_id == 2)
                <p class="alert-danger">Невыполненная транзакция:</p> Перевод
                пользователю {{$transaction->receiver->first_name}}<br>
                Сумма перевода: {{$transaction->amount}}<br>
                Дата и время перевода: {{$transaction->scheduled_time}}
            @endif
        </div>
    @endforeach
</div>
<div class="col-9 float-left">
    {{$user->first_name}}
    {{$user->last_name}} <br>
    Баланс: {{$user->balance}}<br>

    Перевести деньги:<br>
    Кому:<br>


    {!! Form::open(['action' => 'TransactionController@store']) !!}
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
        <div class="row">
            <div class="col-sm-2">
                <input type="number" required class="form-control" placeholder="Сумма" name="amount" step="0.5"
                       min="0.5"
                       max="1000000">
            </div>
        </div>
        <br>
        Дата и время перевода:<br><br>

        <div class="row">
            <div class='col-sm-2'>
                <input type='text' class="form-control" id='datetimepicker4' name="dateTime"/>
            </div>
            <script type="text/javascript">
                $(function () {
                    $('#datetimepicker4').datetimepicker({
                        locale: 'ru',
                        minDate: new Date(),
                        format: 'YYYY-MM-DD HH:00:00',
                    });
                });
            </script>
        </div>

        <br>

        <input type="submit" class="btn btn-primary" value="Отправить">
    </ul>
    {!! Form::close() !!}

</div>
