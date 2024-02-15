
@extends('structure.wrapper')
@include('covid.menu')


@section('content')

    @php
        $json=json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/data/strat.json'), true);
    @endphp
    <div class="container-fluid">
        <h3>Общие сведения об объектах в {{env('APP_REGION_NAME')? env('APP_REGION_NAME'):'Тутаевском районе'}}</h3>
        <form action="/covid/stat/save" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-4">
                    <label for="ecology_total">Экология:</label>
                    <input type="text" required="required"name="ecology_total" value="{{$json['ecology']['total']}}" class="c-input">
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="jkh_total">ЖКХ:</label>
                    <input type="text" required="required"name="jkh_total" value="{{$json['jkh']['total']}}" class="c-input">
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="roads_total">Дороги и благоустройство:</label>
                    <input type="text" required="required"name="roads_total" value="{{$json['roads']['total']}}" class="c-input">
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="economy_total">Экономика и туризм:</label>
                    <input type="text" required="required" name="economy_total" value="{{$json['economy']['total']}}" class="c-input">
                </div>

            </div>
            <div class="row">
                <div class="col-md-4">
                    <label for="society_total">Соц. объекты:</label>
                    <input type="text" required="required" name="society_total" value="{{$json['society']['total']}}" class="c-input">
                </div>

            </div>
            <input type="submit" class="c-btn" value="СОХРАНИТЬ">
        </form>
    </div>

@endsection



