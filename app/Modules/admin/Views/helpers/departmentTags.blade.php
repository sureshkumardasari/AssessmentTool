@foreach($data as $dep)
<div class="contact_listing mb25 clr dep-list-item{{ $dep->Id }} itmes-lested" institutionId='{{ $dep->InstitutionId }}' value='{{$dep->Id}}'>
    <a class="fltL w150 ellipsis mr20 fancybox fancybox.ajax" href="{{ route('department_info_popup',['id'=>str_replace('h_','',$dep->Id)]) }}">{{$dep->Name}}</a>
    @if($userPermissionEdit)
        <i class="icons delete fltL mt0 showTip" id='dep_{{ $dep->Id }}'></i>
    @endif
</div>
<div class="clr"></div>
@endforeach