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
        <div class="form-group col-5">

            {!! Form::open(array('files' => true,'route' => ['tasks'], 'method' => 'post')) !!}
            {{ csrf_field() }}
            {{ method_field('POST') }}
            {!! Form::hidden('status', 'Новое поручение', ['class' => 'c-input']) !!}
            {!! Form::label('task_num', '№ задачи:') !!}
            {!! Form::text('task_num', null, ['class' => 'c-input']) !!}
            {!! Form::label('name', 'Название задачи:') !!}
            {!! Form::text('name',null, ['class' => 'c-input']) !!}
            {!! Form::label('deadline', 'Крайний срок:') !!}
            {!! Form::text('deadline',null, ['class' => 'c-input hasDatepicker','id'=>'datef']) !!}
            {!! Form::label('responsible_uuids', 'Ответственные:') !!}
            {!! Form::select('responsible_uuids[]',\App\User::getUsersArray(),null, ['id'=>'helpers','class' => 'c-input select2','multiple'=>'multiple']) !!}
            {!! Form::label('attachments', 'Приложения:') !!}
            {!! Form::file('attachments[]',['multiple' => 'multiple']) !!}<br/>
            {!! Form::label('description', 'Описание задачи:') !!}
            {!! Form::textarea('description',null, ['class' => 'c-input']) !!}
            {!! Form::submit('Добавить', ['class' => 'c-btn']) !!}

            {!! Form::close() !!}
        </div>
    </div>

@stop

