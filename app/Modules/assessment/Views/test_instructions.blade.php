@extends('default')
@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-10 col-md-offset-1">
      <div class="panel panel-default">
        <div class="panel-heading">Instructions         
        </div>
        <div class="panel-body">
          <!--  -->
          <div class="i_header mb40">
              {{--{!! $instructions['header'] !!}--}}
          </div>
          <div class="i_title_page mb40">
              {{--{!! $instructions['title_page'] !!}--}}
          </div>
          <div class="i_begin_instructions mb40">
              {!! $instructions['begin_instructions'] !!}
          </div>
          <div class="i_footer mb40">
              {{--{!! $instructions['footer'] !!}--}}
          </div>
          @if (empty($flag))
            <div class="pt20 pb40">
              <a href="{{ route('tests-detail', array('id' => $id)) }}" class="btn btn-primary">Start Test</a>
              <div class="clr"></div>
            </div>
          @endif
          <!--  -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection