@extends('structure.wrapper')
@include('edds.menu')
<style>
    textarea.col-md-9.speech-input {
        min-width: 100%;
    }
    .si-wrapper {
        display: inline-block;
        position: relative;
        min-width: 75%;
    }
    pre#question {
        white-space: pre-wrap;
        white-space: -moz-pre-wrap;
        white-space: -pre-wrap;
        white-space: -o-pre-wrap;
        word-wrap: break-word;
    }
    a.webform-small-button.webform-small-button-blue {
        margin-top: 18px;
    }
    span.select2-selection.select2-selection--multiple {
        padding-bottom: 7px;
    }
    input.select2-search__field::placeholder {
        color: gainsboro;
        font-size:14px;
        padding-left:10px !important;
    }
    .c-modal__content {
        background: whitesmoke;
    }
    span.select2-dropdown.select2-dropdown--below {
        z-index: 100 !important;
    }
    span.tag.label.label-info {
        background: green;
        border-radius:5px;
        padding:3px;
        margin:4px;
    }
    .bootstrap-tagsinput {
        padding: .59375rem .9375rem !important;
    }
    ::-webkit-input-placeholder { /* Chrome/Opera/Safari */
        color: gainsboro;
    }
    ::-moz-placeholder { /* Firefox 19+ */
        color: gainsboro;
    }
    :-ms-input-placeholder { /* IE 10+ */
        color: gainsboro;
    }
    :-moz-placeholder { /* Firefox 18- */
        color: gainsboro;
    }
    span.select2-container--disabled {
        width: 100% !important;
    }
</style>
<link rel="stylesheet" href="/css/bootstrap-tagsinput.css">
<link rel="stylesheet" href="/css/datagrid.css">
<link rel="stylesheet" href="/css/speech-input.css">
<link rel="stylesheet" href="/css/jquery.datetimepicker.css">
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
            <div class="c-alert c-alert--info">
                <i class="c-alert__icon fa fa-info-circle"></i>
                <pre id="question">Тут будут вопросы скрипта в зависимости от сервиса</pre>
            </div>
            @php
            $services=\App\EddsServices::select('id','name')->pluck('name','id')->toArray();
            @endphp
            {!! Form::model($edds, ['files' => true,'route' => ['edds.update', $edds->id], 'method' => 'post']) !!}
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
            {!! Form::hidden('request_num', $edds->request_num) !!}
            {!! Form::radio('type', 'alert', $edds->type) !!} Авария
            {!! Form::radio('type', 'fast', $edds->type) !!} Жалоба
            {!! Form::radio('type', 'plan', $edds->type) !!} Плановое отключение
            {!! Form::radio('type', 'other', $edds->type) !!} Разное
            @if (in_array($edds->type,['fast','other']))
                <input type="text" class="c-input"  name="client_name" value="{{$edds->client_name}}" placeholder="ФИО"/>
                <input type="text" class="c-input"  name="client_phone" value="{{$edds->client_phone}}" placeholder="Телефон"/>
            @else
                @php
                $uks=\App\EddsUk::all()->pluck('name','name')->toArray();
                @endphp
                {!! Form::select('client_name', $uks, $edds->client_name,
                ['class' => 'c-select has-search select2-hidden-accessible','id' => 'client_name']) !!}
            @endif
            <br/>
            {!! Form::select('service_id', $services, $edds->service_id,
            ['class' => 'c-select has-search select2-hidden-accessible','id' => 'service_id']) !!}
            <div class="selectable">

            </div>
            {!! Form::text('request_date',$edds->request_date, ['class' => 'c-input datetimepicker','id'=>'request_date','placeholder'=>'Дата/время обращения','autocomplete'=>'off']) !!}
            <div class="row">
                <div class="col-md-6">
                    {!! Form::text('date_start_problem',$edds->date_start_problem, ['class' => 'c-input datetimepicker','id'=>'date_start_problem','placeholder'=>'Дата начала','autocomplete'=>'off']) !!}
                </div>
                <div class="col-md-6">
                    {!! Form::text('date_end_problem',$edds->date_end_problem, ['class' => 'c-input datetimepicker','id'=>'date_end_problem','placeholder'=>'Дата окончания','autocomplete'=>'off']) !!}
                </div>
            </div>

            @foreach (json_decode($edds->address_json) as $addr)
                <div class="container-fluid">
                       <div class="form-group fieldGroup u-mt-small">
                            <div class="input-group row">
                            <input type="hidden" name="city[]" value="{{$addr[0]}}">
                          <div class="col-3 ">
                              @php
                              $cityname=\App\EddsCities::where('code','like','%'.$addr[0].'%')->get();
                              @endphp
                              <select name="city[]" class="city c-select is-disabled select2-hidden-accessible">
                                <option value="{{$addr[0]}}" selected>{{$cityname[0]->name}}<option>
                              </select>
                          </div>
                                @php
                                $street='Выберите улицу';
                                @endphp
                        @if($addr[1]>0)
                            @php
                                $streetname=\App\EddsStreets::where('id','=',$addr[1])->get();
                                if(isset($streetname[0]->name)){
                                    $street=$streetname[0]->name;
                                }
                            @endphp
                        @endif
                      <input type="hidden" name="street[]" value="{{$addr[1]}}">
                      <div class="col-3 ">
                          <select name="street[]" class="street c-select is-disabled select2-hidden-accessible">
                            <option value="{{$addr[1]}}" selected>{{$street}}<option>
                          </select>
                      </div>
                      <div class="col-3 ">
                          <input type="text" name="house[]" data-role="tagsinput" placeholder="дом/дома" value="{{$addr[2]}}"/>
                      </div>
                      <a href="javascript:void(0)" class="c-btn c-btn--danger remove col-3">
                          <span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> -Удалить адрес</a>
                      </div>
                      </div>
                </div>
            @endforeach
            <!-- Мультивыбор    адресов    -->
            <div class="container-fluid">

                <div class="form-group fieldGroup u-mt-medium">
                    <div class="input-group row">
                        <select placeholder='населенный пункт' name='city[]' class="city city-edit c-input col-3 select2 has-search">
                            <option value="" selected>Населенный пункт</option>
                        </select>

                        <select id="street"  name='street[]' class="street street-edit c-input col-3">
                            <option value="">укажите населенный пункт</option>
                        </select>
                        <input type="text" name="house[]" data-role="tagsinput" class=" col-3" placeholder="дом/дома"/>
                        <a href="javascript:void(0)" class="c-btn c-btn--success addMore col-3"><span class="glyphicon glyphicon glyphicon-plus" aria-hidden="true"></span> +Добавить адрес</a><br/>
                    </div>
                </div>
            </div>
            <!-- copy of input fields group -->
            <div class="form-group fieldGroupCopy u-mt-medium" style="display: none;">
                <div class="input-group row">
                    <span class='move'></span>
                    <a href="javascript:void(0)" class="c-btn c-btn--danger remove col-3"><span class="glyphicon glyphicon glyphicon-remove" aria-hidden="true"></span> -Удалить адрес</a>
                </div>
            </div>
            <!-- Мультивыбор    адресов    -->
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-9">
                            {!! Form::number('house_cnt',$edds->house_cnt, ['class' => 'c-input','placeholder' => 'Количество домов','id' => 'house_cnt','autocomplete'=>'off']) !!}
                        </div>
                        <div class="col-md-3">
                            <a class="c-btn c-btn--primary" href="#house_cnt" onclick="house_cnt()">Посчитать дома</a>
                        </div>
                    </div>

                </div>
                <div class="col-md-6">{!! Form::number('szo_house_cnt',$edds->szo_house_cnt, ['class' => 'c-input','placeholder' => 'Из них СЗО','autocomplete'=>'off']) !!}</div>
            </div>
            <textarea name="description" class="speech-input c-input" placeholder="Описание" id="description">{{$edds->description}}</textarea>
            <div class="u-mt-medium">
                <a class="webform-small-button webform-small-button-transparent main-ui-filter-field-button
                main-ui-filter-reset" href="/wrike/edds/">отмена</a>
                <input type="submit" class="webform-small-button webform-small-button-blue
                       main-ui-filter-field-button" value="Сохранить">
            </div>
        </div>
        {!! Form::close() !!}
    </div>

@endsection
@section('add_scripts')
    {{--<script src="/js/select2.min.js"></script>--}}
    <script src="/js/speech-input.js"></script>
    <script src="/js/jquery.datetimepicker.js"></script>
    <script src="/js/php-date-formatter.min.js"></script>
    <script src="/js/bootstrap-tagsinput.min.js"></script>

    <script>
        //Мультивыбор адресов
        $(document).ready(function(){
            var id=0;
            var city='<option value="" selected>Выберите населенный пункт</option>';
            $.ajax({
                url: '/edds/uk/getCities',
                async: false,
                dataType: 'json',
                success: function (data) {
                    var items = [];
                    //console.log(data)

                    $.each( data, function( key, item ) {
                        name=item.name;
                        code=item.code;
                        city+='<option value="'+code+'">'+name+'</option>';

                    });
                    //console.log(city);
                }
            });
            $('.city-edit').html(city);
            //add more fields group
            $(".addMore").click(function(){
                id++;
                var newTag = $('<input type="text" name="house[]" class="taginput move_me c-input" placeholder="дом/дома"/>');
                var fieldHTML = '<div class="form-group fieldGroup sub">'+$(".fieldGroupCopy").html()+'</div>';
                var selectStreet=$('<select id="street'+id+'" name="street[]" class="street c-input col-3 select2 has-search"><option value="" selected>укажите населенный пункт</option></select>');
                var selectCity=$('<select id="city'+id+'" class="city c-input col-3 select2 has-search" name="city[]" street_id="'+id+'">'+city+'</select>');

                $('body').find('.fieldGroup:last').after(fieldHTML);
                $('body').find('.sub:last').children('.input-group').children('.move').html(newTag);
                $('body').find('.sub:last').children('.input-group').children('.move').before(selectCity);
                $('body').find('.sub:last').children('.input-group').children('.move').before(selectStreet);
                initializeTag(newTag);
                addSelectChange();
            });
            //function to initialize tagsinput
            function initializeTag(selectElementObj) {
                selectElementObj.tagsinput({});
            }
            //remove fields group
            $("body").on("click",".remove",function(){
                $(this).parents(".fieldGroup").remove();
            });

            $(".city").change(function() {
                var val = $(this).val();
                $('#street').html(get_options(val));
            });
            $('select.city').select2();
            $('select.street').select2();
            function addSelectChange() {
                $('select.city').bind('change', function() {
                    var val = $(this).val();
                    $('#street'+$(this).attr("street_id")).html(get_options(val));
                });
                $('select.city').select2();
                $('select.street').select2();
            }
            function get_options(val){
                var street='<option value="" selected>Выберите улицу</option>';
                $.ajax({
                    url: '/edds/uk/getStreets/?get_street='+val,
                    async: false,
                    dataType: 'json',
                    success: function (data) {
                        var items = [];
                        //console.log(data)

                        $.each( data, function( key, item ) {
                            name=item.name;
                            code=item.id;
                            street+='<option value="'+code+'">'+name+'</option>';

                        });
                    }
                });
                return street;
            }


        });
        $(".hasDatepicker").datepicker({
            monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июнь", "Июль", "Авг", "Сен", "Окт", "Ноя", "Дек"],
            format: "dd.mm.yyyy",
            daysMin: ["Вс", "Пн", "Вт", "Cр", "Чт", "Пт", "Сб"],
            autoHide: true
        });
        $.datetimepicker.setLocale('ru');

        $('.datetimepicker').datetimepicker({dayOfWeekStart:1
        });
        $(".hasMultipleSelect").select2({
            placeholder: "Выберите из списка",
            width: "100%",
            multiple: true
        });
        //Расчет количества домов по улице, населенному пункту, либо явно указано
        function house_cnt() {

            var result = 0;
            $('.fieldGroup').each(function (index, value) {
                city = $(value).find('.city').find(":selected").val();
                street = $(value).find('.street').find(":selected").text();
                house = $(value).find('.tag').length;
                //Если явно не указаны дома
                if(house==0){
                    if(city!='') {
                        console.log(city);
                        //Если выбран только населенный пункт без улицы
                        if (street == 'Выберите улицу' || street == 'укажите населенный пункт') {
                            if (city != 'Выберите населенный пункт') {
                                console.log('Ищем по городу...' + city);
                                result = result + house_cnt_by_address(city, '');
                            }
                        } else {
                            console.log('ищем по улице...' + street + '...в населенном пункте....' + city)
                            result = result + house_cnt_by_address(city, street);
                        }
                    }
                }else{
                    console.log('Считаем дома');
                    result=result+house
                }
            });
            console.log('Итого - '+result);
            $('#house_cnt').val(result);
        }
        function house_cnt_by_address(city,street){
            result=0;
            if(city!='') {
                $.ajax({
                    url: '/edds/uk/getHouseCnt/?code=' + city + '&street=' + street,
                    async: false,
                    success: function (data) {
                        var items = [];
                        result = result + parseInt(data);
                    }
                });
            }
            return result
        }
    </script>
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>-->
    <script>
        //Radio switch
        $(document).ready(function() {
            $('input:radio[name^="type"]').change(function() {
                if (this.value == 'alert'||this.value == 'plan') {
                    var serv='<option value="" disabled selected>Выберите сервисную компанию</option>';
                    $.ajax({
                        url: '/edds/uk/getServices',
                        async: false,
                        dataType: 'json',
                        success: function (data) {
                            var items = [];
                            //console.log(data)

                            $.each( data, function( key, item ) {
                                name=item.name;
                                code=item.id;
                                serv+='<option value="'+name+'">'+name+'</option>';

                            });
                        }
                    });
                    $('.selectable').html('<select name="client_name"  class="c-input" >'+serv+'</select>');
                }
                else if (this.value == 'fast'||this.value == 'other') {
                    $('.selectable').html('' +
                            '<input type="text" class="c-input" name="client_name" placeholder="ФИО"/>' +
                            '<input type="text" class="c-input" name="client_phone" placeholder="Телефон"/>'
                    );
                }
            });
        });

        $('#service_id').on('change', function() {
            $.get( "/edds/uk/getAnswers", { get_by_id: "true", ID: this.value }, function( data ) {
                result=$.parseJSON(data);
                $('#question').html( result.questions );
            } );


        });
        $( document ).ready(function() {
            $('#o_modal_corresp').click(function (e) {
                //#myInput - id элемента, которому необходимо установить фокус
                alert('test');
            })
        });


    </script>
@endsection