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

            {!! Form::model($edds_services, ['files' => true,'route' => ['edds.services.update', $edds_services->id], 'method' => 'post']) !!}
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            {!! Form::label('name', '№ задачи:') !!}
            {!! Form::text('name', $edds_services->name, ['class' => 'c-input']) !!}
            {!! Form::textarea('questions',$edds_services->questions, ['class' => 'c-input']) !!}
            {!! Form::submit('Сохранить', ['class' => 'c-btn']) !!}
            {!! Form::hidden('redirect_to','/edds/services/') !!}
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

