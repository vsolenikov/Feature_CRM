@section('menu_items')
@php
$user_acc=explode(',',Auth::user()->module_access)
@endphp
    <li class="c-sidebar__item">
        <a href="/covid/map" class="c-sidebar__link"><i class="fa fa-id-card-o u-mr-xsmall"></i>Общие сведения</a>

        <a href="/covid/" class="c-sidebar__link"><i class="fa fa-id-card-o u-mr-xsmall"></i>Объекты</a>
</li>


@stop
