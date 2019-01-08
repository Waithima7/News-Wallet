@if(isset($_GET['t_optimized']) && Request::ajax())
    @endsection
@section('t_optimized')
    @include($tabs_folder.".".$tab)
@endsection
@section('content_2')
    @endif

    <?php
    if($tab == ''){
        $tab = $tabs[0];
    }
    ?>

    <ul class="nav nav-tabs">
        @foreach($tabs as $single_tab)
            <li class="nav-item">
                <a class="auto-tab {{ $tab==$single_tab ? 'active':'' }} nav-link tab_{{ $single_tab }} load-paeege" href="{{ url($base_url."?tab=".$single_tab) }}"  onclick="return ajaxLoad('{{ url($base_url."?tab=".$single_tab) }}&t_optimized=1','ajax_tab_content','tab_{{ $single_tab }}')">{{ ucwords(str_replace('_',' ',$single_tab)) }}<span class="tab_badge_{{ $single_tab }}"></span> </a>
            </li>
        @endforeach
    </ul>

    <div class="tab-content ajax_tab_content" style="margin-top: 5px;">
        @if(!isset($_GET['t_optimized']) || Request::ajax() == false)
            @include($tabs_folder.".".$tab)
        @endif
    </div>
