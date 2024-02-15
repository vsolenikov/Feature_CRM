@extends('structure.wrapper')
@include('covid.menu')


@section('content')

    {{--{{ Breadcrumbs::render('citizens') }}--}}
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey=03cd544d-7851-4dc8-9b57-4bfb9e2f28c9" type="text/javascript"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <style>
        #map {
            width: 100%;
            height: 500px;
            padding: 0;
            margin: 0;
        }

        #chart_div {
            width: 100%;
            height: 500px;
        }
        .btn_main{
            position:relative;
            padding-bottom:50px;
        }
        .btn_cat{
            background-color: #2ea1f8;
            text-align: center;
            color: white;
            position: absolute;
            bottom: 0px;
            margin-left: 20%;
            width: 50%;

        }
    </style>

    @php

        $json=json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/data/strat.json'), true);
$link = mysqli_connect("127.0.0.1", "root", "t-denDWM~6umT7S&","laravel");

    $sql_all = $link->query('select count(type) from strat_razvitie');
    $count_all=$sql_all->fetch_row();

    $sql_ecolog = $link->query('select count(type) from strat_razvitie where type="islands#blueGardenCircleIcon"');
    $count_ecolog=$sql_ecolog->fetch_row();

    $sql_jkh = $link->query('select count(type) from strat_razvitie where type="islands#blueRepairShopIcon"');
    $count_jkh=$sql_jkh->fetch_row();

    $sql_roads = $link->query('select count(type) from strat_razvitie where type="islands#blueAutoIcon"');
    $count_roads=$sql_roads->fetch_row();

    $sql_econom = $link->query('select count(type) from strat_razvitie where type="islands#blueMoneyCircleIcon"');
    $count_econom=$sql_econom->fetch_row();

    $sql_soc = $link->query('select count(type) from strat_razvitie where type="islands#blueFamilyIcon"');
    $count_soc=$sql_soc->fetch_row();



   /* $lamps = $link->query("SELECT * FROM strat_razvitie");

    $results=['features'=>['']];
   $results=['type'=>'FeatureCollection','features'=>['']];



    foreach ($lamps as $lamp)
        {
            array_push($results['features'],json_encode(array(
                'type'=>'feature',
                'id'=>$lamp['id'],
                'geometry'=>[
                    'type'=>'Point',
                    'coordinates'=>$lamp['cords'],

                ],
                'properties'=>[
                    'balloonContent'=>$lamp['name'],
                    'clusterCaption'=>$lamp['name'],
                    'hintContent'=>$lamp['name'],
                    'iconCaption'=>$lamp['name']
                ],
                'options'=>[
                    'preset'=>$lamp['type']
                ]

                 )));

        }



$ready_json = json_encode( array($results) );
echo $ready_json;*/

    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <!-- Select2 jquery plugin is used -->
                <a href="/covid/map"><div>Показать все (@php print($count_all[0]); @endphp)</div></a>
                <div class="col-md-3">
                    <!-- Select2 jquery plugin is used -->

                </div>
            </div>
        </div>
    </div>
    <form>
        <div class="container-fluid" id="app">
            <div class="row">

                <div class="col-sm-6 col-lg-3" class="btn_main" >
                    <div class="c-state c-state--success">
                        <h3 class="c-state__title">Экология</h3>
                        <h4 class="c-state__number">@php print($count_ecolog[0]); @endphp</h4>

                        <a href="/covid/map_eclg"><div class="btn_cat">Показать</div></a>

                        <span class="c-state__indicator">
                            <i class="fa fa-tree"></i>
                        </span>
                    </div><!-- // c-state -->

                </div>



                <div class="col-sm-6 col-lg-3" >
                    <div class="c-state">
                        <h3 class="c-state__title">ЖКХ</h3>
                        <h4 class="c-state__number">@php print($count_jkh[0]); @endphp</h4>

                        <a href="/covid/map_jkh"><div class="btn_cat">Показать</div></a>

                        <span class="c-state__indicator">
                            <i class="fa fa-viadeo"></i>
                    </span>
                    </div><!-- // c-state -->
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="c-state c-state--success">
                        <h3 class="c-state__title">Дороги и благосутройство</h3>
                        <h4 class="c-state__number">@php print($count_roads[0]); @endphp</h4>

                        <a href="/covid/map_roads"><div class="btn_cat">Показать</div></a>

                        <span class="c-state__indicator">
                            <i class="fa fa-plus-circle"></i>
                        </span>
                    </div><!-- // c-state -->
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="c-state c-state--warning">
                        <h3 class="c-state__title">Экономика (Инвестиции)<br> и туризм</h3>
                        <h4 class="c-state__number">@php print($count_econom[0]); @endphp</h4>

                        <a href="/covid/map_ecnm"><div class="btn_cat">Показать</div></a>

                        <span class="c-state__indicator">
                            <i class="fa fa-dollar"></i>
                        </span>
                    </div><!-- // c-state -->
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="c-state c-state--danger">
                        <h3 class="c-state__title">Соц. объекты</h3>
                        <h4 class="c-state__number">@php print($count_soc[0]); @endphp </h4>

                        <a href="/covid/map_soc"><div class="btn_cat">Показать</div></a>

                        <span class="c-state__indicator">
                            <i class="fa fa-street-view"></i>
                        </span>
                    </div><!-- // c-state -->
                </div>
            </div>
        </div>

        <div class="col-xl-12">
            <div class="c-card u-p-medium u-mb-medium">
                <div class="u-flex u-justify-between u-align-items-center">
                    <h3 class="c-card__title">Стратегии развития Тутаевского района до 2025 года</h3>
                    <span
                        class="u-text-small u-text-uppercase u-text-mute">в {{env('APP_REGION_NAME')? env('APP_REGION_NAME'):'Тутаевском районе'}}</span>
                </div>
                <div id="map"></div>
            </div>
        </div>


        <script>

            ymaps.ready(function () {
                var myMap_roads = new ymaps.Map('map', {
                    center: [{{env('APP_REGION_CORDS')? env('APP_REGION_CORDS'):'57.877647, 39.537215'}}],
                    zoom: 12,
                    // Обратите внимание, что в API 2.1 по умолчанию карта создается с элементами управления.
                    // Если вам не нужно их добавлять на карту, в ее параметрах передайте пустой массив в поле controls.
                    controls: [],
                }, {
                    balloonPanelMaxMapArea: Infinity
                });


                @foreach($organisations as $organisation)
                if('{{$organisation['type']}}'=="islands#blueAutoIcon") {
                    var myPlacemark_roads = new ymaps.Placemark([{{$organisation['cords']}}], {
                        balloonContentBody: [
                            '<address>',
                            '<strong>{{$organisation['name']}}</strong><br/>',
                            'Адрес: {{$organisation['address']}}',
                            '<br/>',
                            'Год реализации: {{$organisation['year_realease']}}',
                            '<br/>',
                            'Наличие ПСД: {{$organisation['nal_psd']}}',
                            '<br/>',
                            'Стоимость: {{$organisation['price']}}',
                            '</address>'
                        ].join('')
                    }, {
                        preset: '{{$organisation['type']}}'
                    });


                    myMap_roads.geoObjects.add(myPlacemark_roads);
                }
                @endforeach

            });
        </script>

@endsection


