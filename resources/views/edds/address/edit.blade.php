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
            <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <div class="form-group">

            {!! Form::model($tasks, ['files' => true,'route' => ['tasks.update', $tasks->id], 'method' => 'post']) !!}
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            {!! Form::label('task_num', '№ задачи:') !!}
            {!! Form::text('task_num', $tasks->task_num, ['class' => 'c-input']) !!}
            {!! Form::hidden('request_id', $tasks->request_id) !!}
            {!! Form::label('name', 'Название задачи:') !!}
            {!! Form::text('name',$tasks->name, ['class' => 'c-input']) !!}
            {!! Form::label('important', 'Уровень важности:') !!}
            {!! Form::select('important', $important, $tasks->important, ['class' => 'c-input']) !!}
            {!! Form::label('deadline', 'Крайний срок:') !!}
            {!! Form::date('deadline',date('Y-m-d', strtotime($tasks->deadline)), ['class' => 'c-input hasDatepicker']) !!}
            {!! Form::label('responsible_uuids', 'Ответственные:') !!}
            {!! Form::select('responsible_uuids[]',$tasks->UserArray[0],$tasks->UserArray[1], ['id'=>'responsible_uuids','class' => 'c-input select2','multiple'=>'multiple']) !!}
            {!! Form::label('attachments', 'Приложения:') !!}
            {!! Form::file('attachments[]',['multiple' => 'multiple']) !!}<br/>
            {!! Form::label('description', 'Описание задачи:') !!}
            {!! Form::textarea('description',$tasks->description, ['class' => 'c-input']) !!}
            {!! Form::submit('Сохранить', ['class' => 'c-btn']) !!}

            {!! Form::close() !!}
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <script>
        $('#responsible_uuids').select2({
            placeholder: "Выберите сотрудников...",
            minimumInputLength: 3,
            ajax: {
                url: '/users/find',
                dataType: 'json',
                data: function (params) {
                    return {
                        q: $.trim(params.term)
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
    </script>
@stop

