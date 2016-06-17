@extends('default')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Branding
                    <a href="{{ url('/user/brandingadd/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
                </div>

                <div class="panel-body">
                    <table id="rolestable" class="table table-striped table-bordered datatableclass" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Institution Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $branding as $brand )
                            <tr>                                
                                <td>{{ $brand->institution_name }}</td>
                                <td>
                                    <a href="{{  url('/user/brandingedit/'.$brand->id) }}" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>                 
                                    <a href="javascript:;" data-ref="{{  url('/user/brandingdel/'.$brand->id) }}" class="btn btn-default btn-sm confirm"><span class="glyphicon glyphicon-trash" aria-hidden="true" onclick="fancyConfirm();"></span></a>
                                </td>
                            </tr>
                            @endforeach                         
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{!! HTML::script(asset('/js/custom/confirm.js')) !!}
@endsection