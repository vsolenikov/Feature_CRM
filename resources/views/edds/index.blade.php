@extends('structure.wrapper')
@include('edds.menu')
@section('content')
    <style>
        .c-table.extra_small .service_name{
            display: none;
        }
        .c-table.extra_small .c-table__cell {
            padding: 4px !important;
        }
        td.c-table__cell {
            word-break: break-word;
        }
    </style>
    {{--{{ Breadcrumbs::render('tasks') }}--}}
    <div class="container-fluid">
        <h1 class="text-center">Аварийные отключения</h1>
        @php
        $DATE_START=\Carbon\Carbon::parse("15.11.2019 00:30:00");
        $NOW=\Carbon\Carbon::parse("now");
        //Дата окончания либо не заполнена,либо пуста
        $erequests=\App\EddsRequests::where('type','=','alert')->where('created_at','>',$DATE_START)->whereNull('date_end_problem')->get();
        $derequests=\App\EddsRequests::where('type','=','alert')->where('created_at','>',$DATE_START)->where('date_end_problem','>',$NOW)->get();
        @endphp
        <table class="c-table extra_small">
            <thead class="c-table__head c-table__head--slim">
            <tr class="c-table__row">
                <th class="c-table__cell c-table__cell--head  compact text-center request_num">№ обращения</th>
                <th class="c-table__cell c-table__cell--head  compact text-center date_start_problem">Дата начала</th>
                <th class="c-table__cell c-table__cell--head  compact text-center client_name">ФИО/Название</th>
                <th class="c-table__cell c-table__cell--head  compact text-center address_hidden">Адреса</th>
                <th class="c-table__cell c-table__cell--head  compact text-center service_name">Сервис</th>
                <th class="c-table__cell c-table__cell--head  compact text-center date_end_problem">Дата окончания</th>
            </tr>
            </thead>
            <tbody>
            @foreach($erequests as $erequest)
                <tr class="c-table__row c-table__row--danger">
                    <td class="c-table__cell compact text-center request_num">{{$erequest->request_num}}</td>
                    <td class="c-table__cell compact text-center date_start_problem">{{$erequest->date_start_problem}}</td>
                    <td class="c-table__cell compact text-center client_name"><b>{{$erequest->client_name}}<br></b></td>
                    <td class="c-table__cell compact text-center address_hidden">
                        <div class="hidden_block" style="display: none;">{!! $erequest->AddressList !!}</div>Домов:{{$erequest->house_cnt}}<a href="" class="show">Показать</a><br>{{$erequest->szo_house_cnt}}											</td>
                    <td class="c-table__cell compact text-center service_name">
                        <span class="c-badge c-badge--success">{!!$erequest->ServiceName  !!}</span>											</td>
                    <td class="c-table__cell compact text-center date_end_problem">{{$erequest->date_end_problem}}</td>
                </tr>
            @endforeach
            @foreach($derequests as $erequest)
                <tr class="c-table__row c-table__row--danger">
                    <td class="c-table__cell compact text-center request_num">{{$erequest->request_num}}</td>
                    <td class="c-table__cell compact text-center date_start_problem">{{$erequest->date_start_problem}}</td>
                    <td class="c-table__cell compact text-center client_name"><b>{{$erequest->client_name}}<br></b></td>
                    <td class="c-table__cell compact text-center address_hidden">
                        <div class="hidden_block" style="display: none;">{!! $erequest->AddressList !!}</div>Домов:{{$erequest->house_cnt}}<a href="" class="show">Показать</a><br>{{$erequest->szo_house_cnt}}											</td>
                    <td class="c-table__cell compact text-center service_name">
                        <span class="c-badge c-badge--success">{!!$erequest->ServiceName  !!}</span>											</td>
                    <td class="c-table__cell compact text-center date_end_problem">{{$erequest->date_end_problem}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <h1 class="text-center">Плановые отключения</h1>
        @php
        $DATE_START=\Carbon\Carbon::parse("15.11.2019 00:30:00");
        $NOW=\Carbon\Carbon::parse("now");
        //Дата окончания либо не заполнена,либо пуста
        $erequests=\App\EddsRequests::where('type','=','plan')->where('created_at','>',$DATE_START)->whereNull('date_end_problem')->get();
        $derequests=\App\EddsRequests::where('type','=','plan')->where('created_at','>',$DATE_START)->where('date_end_problem','>',$NOW)->get();
        @endphp
        <table class="c-table extra_small">
            <thead class="c-table__head c-table__head--slim">
            <tr class="c-table__row">
                <th class="c-table__cell c-table__cell--head  compact text-center request_num">№ обращения</th>
                <th class="c-table__cell c-table__cell--head  compact text-center date_start_problem">Дата начала</th>
                <th class="c-table__cell c-table__cell--head  compact text-center client_name">ФИО/Название</th>
                <th class="c-table__cell c-table__cell--head  compact text-center address_hidden">Адреса</th>
                <th class="c-table__cell c-table__cell--head  compact text-center service_name">Сервис</th>
                <th class="c-table__cell c-table__cell--head  compact text-center date_end_problem">Дата окончания</th>
            </tr>
            </thead>
            <tbody>
            @foreach($erequests as $erequest)
                <tr class="c-table__row c-table__row--danger">
                    <td class="c-table__cell compact text-center request_num">{{$erequest->request_num}}</td>
                    <td class="c-table__cell compact text-center date_start_problem">{{$erequest->date_start_problem}}</td>
                    <td class="c-table__cell compact text-center client_name"><b>{{$erequest->client_name}}<br></b></td>
                    <td class="c-table__cell compact text-center address_hidden">
                        <div class="hidden_block" style="display: none;">{!! $erequest->AddressList !!}</div>Домов:{{$erequest->house_cnt}}<a href="" class="show">Показать</a><br>Из них СЗО:{{$erequest->szo_house_cnt}}											</td>
                    <td class="c-table__cell compact text-center service_name">
                        <span class="c-badge c-badge--success">{!!$erequest->ServiceName  !!}</span>											</td>
                    <td class="c-table__cell compact text-center date_end_problem">{{$erequest->date_end_problem}}</td>
                </tr>
            @endforeach
            @foreach($derequests as $erequest)
                <tr class="c-table__row c-table__row--danger">
                    <td class="c-table__cell compact text-center request_num">{{$erequest->request_num}}</td>
                    <td class="c-table__cell compact text-center date_start_problem">{{$erequest->date_start_problem}}</td>
                    <td class="c-table__cell compact text-center client_name"><b>{{$erequest->client_name}}<br></b></td>
                    <td class="c-table__cell compact text-center address_hidden">
                        <div class="hidden_block" style="display: none;">{!! $erequest->AddressList !!}</div>Домов:{{$erequest->house_cnt}}<a href="" class="show">Показать</a><br>Из них СЗО:{{$erequest->szo_house_cnt}}											</td>
                    <td class="c-table__cell compact text-center service_name">
                        <span class="c-badge c-badge--success">{!!$erequest->ServiceName  !!}</span>											</td>
                    <td class="c-table__cell compact text-center date_end_problem">{{$erequest->date_end_problem}}</td>
                </tr>
            @endforeach

            </tbody>
        </table>

        @php
            $pagination=$edds->appends($request->all())->links();
        @endphp
        @if($pagination)
            <nav>
                {!! $pagination !!}
            </nav>
        @endif
        <div class="c-table__title">
            <h3 class="d-inline pr-2 pl-2">Список обращений ({{$edds->total()}})</h3>
            <a class="c-btn c-btn--success mb-2" href="/edds/create">Добавить+</a>
            <form action="/edds/report" style="max-width: 100%; width: 300px;   float: right;   margin: 0 50px;  display: inline-flex;">
                <input type="date" class="c-input" name="date-1" value="2023-01-01">
                <input type="date" class="c-input" name="date-2" value="<?php $today = date("Y-m-d");   ; echo $today;  ?>">
                <button type="submit">
                    <a class="c-table__title-action" href="/edds/report" target="_blank">
                        <i class="fa fa-cloud-download"></i>
                    </a></button>
            </form>
        </div>
        <script>
            $(document).ready(function(){
                $('table.c-table.extra_small  td.service_name').each(function () {
                    service_name=$(this).text().replace(/\s+/g, ' ').trim();
                    switch(service_name){
                        case 'ГВС+ОТОПЛЕНИЕ':
                            $(this).parent().css({backgroundColor: "lightpink"});
                            break;
                        case 'ЭЛЕКТРОЭНЕРГИЯ':
                            $(this).parent().css({backgroundColor: "lightyellow"});
                            break;
                        case 'ХОЛОДНОЕ ВОДОСНАБЖЕНИЕ':
                            $(this).parent().css({backgroundColor: "lightskyblue"});
                            break;
                        case 'ГОРЯЧЕЕ ВОДОСНАБЖЕНИЕ':
                            $(this).parent().css({backgroundColor: "lightskyblue"});
                            break;
                    }

                });
                $('.show').click(function(e){
                    e.preventDefault();
                    $(this).siblings('.hidden_block').toggle();
                })
            });
        </script>
        <div class="c-table-responsive@desktop">
            @include('scuti::table', compact('table', 'pagination'))
        </div>
    </div>
@stop

