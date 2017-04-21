@extends('default')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Branding
                    <a href="{{ url('/user/brandingadd/') }}" class="btn btn-primary btn-sm right"><span class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span> Add</a>
                </div>
    <div>
        @if(Session::has('flash_message'))
            <div class="alert alert-success"><span class="glyphicon glyphicon-ok"></span><em> {{ Session('flash_message') }}</em></div>
        @endif
    </div>
    <div>
        @if(Session::has('flash_message_failed'))
            <div class="alert alert-danger"><span class="glyphicon glyphicon-remove"></span><em> {!! Session('flash_message_failed') !!}</em></div>
        @endif
    </div>
                <div class="panel-body">
                    <table id="rolestable" class="table table-striped table-bordered " cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Institution Name</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach( $branding as $brand )
                            <tr>                                
                                <td>{{ $brand->institution_name }}</td>
                                <td>
                                    <a href="{{  url('/user/brandingedit/'.$brand->id) }}" class="btn btn-default btn-sm" title="Edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>                 
                                    <a href="javascript:;" data-ref="{{  url('/user/brandingdel/'.$brand->id) }}" class="btn btn-default btn-sm confirm" title="Delete"><span class="glyphicon glyphicon-trash" aria-hidden="true" onclick="fancyConfirm();"></span></a>
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
<script type="text/javascript">
    $(document).ready(function() {
    $('#rolestable').DataTable({
    aoColumnDefs: [
  {
     bSortable: false,
     aTargets: [ -1 ]
  }
]
 });
});
</script>
{!! HTML::script(asset('/js/custom/confirm.js')) !!}.

@endsection