@extends('layouts.default')
@section('content')
    <div class="dashboard-wrapper">
        <div class="dashboard-header in-error">
            <span class="headline">Something Went Wrong</span>
        </div>
        <div class="dashboard-content">
            <ul class="widget">
                <li id="is-dynamic-1" class="pull-left columnHeight">
                    <div class="dash-widget-header">
                    <span>{{ $errorMessage }}

                    </span>
                    </div>
                    <div class="dash-widget-content">
                        {{ $errorDetails }}
                    </div>
                </li>
            </ul>
        </div>
    </div>
@endsection
@section('footer-assets')
    @parent
@stop