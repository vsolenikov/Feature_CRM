@extends('structure.wrapper')
@include('edds.menu')
@section('content')
    {{--{{ Breadcrumbs::render('requests_add') }}--}}
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

            {!! Form::open(array('files' => true,'route' => ['edds.services.store'], 'method' => 'post')) !!}
            {{ csrf_field() }}
            {{ method_field('POST') }}
            {!! Form::label('name', 'Сервис:') !!}
            {!! Form::text('name', null, ['class' => 'c-input']) !!}
            {!! Form::label('questions', 'Вопрос:') !!}
            {!! Form::textarea('questions',null, ['class' => 'c-input']) !!}
            {!! Form::submit('Добавить', ['class' => 'c-btn']) !!}
            {!! Form::hidden('redirect_to','/edds/services/') !!}
            {!! Form::close() !!}
        </div>
    </div>

@stop

