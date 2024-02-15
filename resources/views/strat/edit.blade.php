@extends('structure.wrapper')
@include('covid.menu')
@section('content')

    {{--{{ Breadcrumbs::render('users.edit',$users->id) }}--}}
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="form-group">
  {!! Form::model($covid, ['route' => ['covid.update', $covid->id], 'method' => 'post']) !!}
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            {!! Form::label('name', 'Название организации:') !!}
            {!! Form::text('name',null, ['class' => 'c-input']) !!}

            {!! Form::label('cords', 'Координаты(широта,долгота):') !!}
            {!! Form::text('cords',null, ['class' => 'c-input']) !!}

            {!! Form::label('address', 'Адрес:') !!}
            {!! Form::text('address',null, ['class' => 'c-input']) !!}

            {!! Form::label('price', 'Стоимость:') !!}
            {!! Form::text('price',null, ['class' => 'c-input']) !!}

            {!! Form::label('nal_psd', 'Наличине ПСД:') !!}
            {!! Form::text('nal_psd',null, ['class' => 'c-input']) !!}

            {!! Form::label('year_realease', 'Год реализации:') !!}
            {!! Form::text('year_realease',null, ['class' => 'c-input']) !!}
            {!! Form::label('type', 'Тип учреждения:') !!}
 {!! Form::select('type', ['islands#blueGardenCircleIcon'=>'Экология и безопасность','islands#blueAutoIcon'=>'Дороги и благоустройство','islands#blueFamilyIcon'=>'Социальные объекты','islands#blueRepairShopIcon'=>'ЖКХ','islands#blueMoneyCircleIcon'=>'Экономика и туризм'], null, ['class' => 'c-input']) !!}
            {!! Form::submit('Сохранить', ['class' => 'c-btn']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@stop

