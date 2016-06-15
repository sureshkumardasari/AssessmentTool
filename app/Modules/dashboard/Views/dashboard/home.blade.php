@extends('default')
@section('header-assets')
    @parent
    {!! HTML::script(asset('assets/js/dashboard/dashboard.js')) !!}
    {!! HTML::script(asset('assets/js/reports/fusion-charts/fusioncharts.js')) !!}
    {!! HTML::script(asset('assets/js/reports/fusion-charts/fusioncharts.charts.js')) !!}
    {!! HTML::script(asset('assets/js/reports/fusion-charts/fusioncharts.powercharts.js')) !!}
    {!! HTML::script(asset('assets/js/dashboard/dashboard_multiselect.js')) !!}
@stop
@section('content')

    <script type="text/javascript">
      $(document).ready(function() {
        grading.init();
      });
  </script>
        <style>
        .dashboardDropdown {
          box-sizing: unset;
        }
        </style>
   
        <div class="dashboard-warpper">
            <section class="dashboard-block">
                <div class="headings">
                    <h1>{{$user->name}}'s Dashboard</h1>


                </div>
                @if($studensList != '')
                    <div class="headings dashboardDropdown"> 
                        <div class="mb20">
                            <label class="txt_17_b mt12 fltL w110" for="students">{{ $dropDownLabel }}</label>
                            {!! $studensList !!}
                        </div> 
                    </div>
                @endif
                <div class="widget-holder">
                    @foreach ($widgets as $key=>$widget)

                        <section class="dashboard-widget {{ $widget->template == 'plans' ? 'act-plans':'' }}">
                            <h1>{{ $widget->headline }}</h1>
                            <div class="content-holder">
                                @include ('dashboard::dashboard.partials.' . $widget->template)
                            </div>
                            <a href="{{ ($widget->link == 'NULL' )?'#':$widget->link }}" class="upload_btn">{{ $widget->button == 'NULL' ?'View Report':$widget->button }}</a>
                        </section>
                    @endforeach
                </div>
            </section>
        </div>
@endsection
@section('footer-assets')
    @parent

@stop