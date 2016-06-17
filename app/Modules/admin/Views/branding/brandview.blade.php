@extends('default')
@section('content')
    <div class="container-fluid" xmlns="http://www.w3.org/1999/html">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    {{--<a class=class="btn btn-primary btn-sm right name="new user" href="{{ url('user/branding/brand') }}">Add </a>--}}
                    <a href="{{ url('user/branding/brand') }}" class="btn btn-primary btn-sm right">
                        <span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
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
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (is_array($branding) || is_object($branding))

                                    @foreach ($branding as $brand)

                                        <tr>
                                            <td>{{ $brand->institution_name }}</td>
                                            <td>
                                                <a href="{{ url('user/'.$brand->id.'/edit') }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                                <a href="javascript:;" data-ref="{{ url('user/delete/'.$brand->id) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
    {!! HTML::script(asset('/js/custom/confirm.js')) !!}

@endsection
<script>
@if(isset($from) && $from == 'search')
    $(document).ready(function() {
    $('.datatableclass').DataTable({
    language: {
    paginate: {
    previous: '‹',
    next:     '›'
    },
    aria: {
    paginate: {
    previous: 'Previous',
    next:     'Next'
    }
    }
    }
    });
    });
    @endif
</script>
