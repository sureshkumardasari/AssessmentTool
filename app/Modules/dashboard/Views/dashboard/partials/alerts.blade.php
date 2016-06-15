<div class="alert alert-dismissible {{ $alert->class }}" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <strong>{{ $alert->headline }}</strong>
    {{ $alert->message }}
</div>
