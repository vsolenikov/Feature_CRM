@extends('structure.wrapper')
@include('edds.address.menu')
@section('content')
    {{--{{ Breadcrumbs::render('tasks') }}--}}
    <div class="container-fluid">

        @php
            $pagination=$edds_address->appends($request->all())->links();
        @endphp
        @if($pagination)
            <nav>
                {!! $pagination !!}
            </nav>
        @endif
        <div class="c-table__title">
            <h3 class="d-inline pr-2 pl-2">Список адресов ({{$edds_address->total()}})</h3>
            <a class="c-btn c-btn--success mb-2" href="edds/uk/create">Добавить+</a>
        </div>


        @include('scuti::table', compact('table', 'pagination'))
    </div>
@stop

