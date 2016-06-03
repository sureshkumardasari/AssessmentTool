@extends('default')
@section('content')
    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <a class="btn btn-success pull-right" name="new user" href="{{ url('user/branding/brand') }}">Add </a>
                    <div class="panel-heading">Brands View

                    </div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form class="form-horizontal" role="form" method="get" action="{{ url('branding/brand/{id}/edit') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table table-bordered table-hover table-striped" id="brandview">
                                <thead>
                                <tr>
                                    <th>Institution Name</th>
                                    {{--<th>Title</th>  --}}
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (is_array($branding) || is_object($branding))

                                    @foreach ($branding as $brand)

                                        <tr>
                                            <td>{{ $brand->institution_name }}</td>
                                            {{-- <td>{{ $brand->title }}</td>--}}
                                            <td><a href="{{ url('user/'.$brand->id.'/edit') }}" >Edit</a>
                                                &nbsp;&nbsp;|&nbsp;&nbsp;
                                                <a href="{{ url('user/delete/'.$brand->id) }}" onclick="return confirm('Are you sure you want delete this Brand ?');">Delete</a>

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                </tbody>
                            </table>

                        </form>

                    </div>
                    <div>

                        @if(Session::has('flash_message'))
                            <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {!! Session('flash_message') !!}</em></div>
                        @endif
                    </div>
                    <div>

                        @if(Session::has('flash_message_failed'))
                            <div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
                        @endif
                    </div>


                </div>
                <!-- <div> <a href="{{ URL::to('downloadExcelforusers/csv') }}"><button class="btn btn-info">Download CSV</button></a></div> -->
            </div>
        </div>
    </div>


@endsection