@extends('structure.wrapper')
@include('covid.menu')
@section('content')
    @php
        $pagination=$covid->links();
$user_acc=explode(',',Auth::user()->module_access)
    @endphp
    <div class="container-fluid">
        <div class="c-table__title">
            <h3 class="d-inline pr-2 pl-2">Список объектов ({{$covid->total()}})</h3>
@if(in_array('COVID',$user_acc))
            <a class="c-btn c-btn--success" href="/covid/create">Добавить+</a>
@endif
        </div>
        {{-- Render table with parameters --}}
        @include('scuti::table', compact('table', 'pagination'))
    </div>

@endsection


