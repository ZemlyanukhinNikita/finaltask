@include('layouts.app')
<div class="col-3">
    {{$user->first_name}}
    {{$user->last_name}} <br>
    Счет: {{$user->balance}}<br>

    Перевести деньги:<br>
    Кому:<br>
</div>
{!! Form::open(['action' => 'TransactionController@store']) !!}
{!! Form::token() !!}
<ul>

    @foreach($other_users as $otherUser)
        <div>
            <label>
                <input type="radio" value="{{ $otherUser->id }}" name="toUser" required="required"/>
                {{ $otherUser->first_name }}
                {{ $otherUser->last_name }}
            </label>
        </div>
    @endforeach

    <input type="hidden" name="from_user_id" value="{{$user->id}}">

    Сумма перевода:
    <div class="row">
        <div class="col-sm-2">
            <input type="number" required class="form-control" placeholder="Сумма" name="amount" value=0
                   min="1"
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
                    format: 'YYYY-MM-DD HH:00:00'
                });
            });
        </script>
    </div>

    <br>
    {{--Дата и время перевода:--}}
    {{--<input type="datetime-local" value="00">--}}

    <input type="submit" class="btn btn-primary" value="Отправить">
</ul>
{!! Form::close() !!}
@foreach(['danger', 'success'] as $status)
    @if (session()->has($status))
        <div class="alert alert-{{$status}}">
            {{ session()->get($status) }}
        </div>
    @endif
@endforeach
