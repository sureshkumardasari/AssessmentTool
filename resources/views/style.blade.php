<?php

  $bg_color = '#008fb3';
  $font_color = '#ffffff';
  $branding = (array)getbranding();
  //var_dump($branding);
  if(is_array($branding) && count($branding))
  {
    $header_bg_color = $branding['header_bg_color'];
    $header_text_color = $branding['header_text_color'];
    
    $box_header_bg_color = $branding['box_header_bg_color'];
    $box_header_text_color = $branding['box_header_text_color'];
    $box_text_color = $branding['box_text_color'];
    
    $button_bg_color = $branding['button_bg_color'];
    $button_text_color = $branding['button_text_color'];
    $breadcrumb_text_color = $header_bg_color;
    $breadcrumb_bg_color = '#f5f5f5';
  } 
  else{
    $header_bg_color = '#008fb3';
    $header_text_color = '#ffffff';
    $box_header_bg_color = '#008fb3';
    $box_header_text_color = '#ffffff';
    $box_text_color = '#ffffff';
    $button_bg_color = '#337ab7';
    $button_text_color = '#ffffff';
    $breadcrumb_text_color = $header_bg_color;
    $breadcrumb_bg_color = '#f5f5f5';
   }
?>
<style>
/* new theme*/
.navbar-default {
    background-color: {{$header_bg_color}}; /*#26acd9;*/    
}
.navbar-default .navbar-nav > li > a {
    color: {{$header_text_color}};
    font-size: 16px;
    font-weight: bold;
    text-transform : uppercase;
}
.dropdown-menu > li > a {
   /* font-size: 16px;*/
    font-weight: bolder;
    text-transform : uppercase;
}
.breadcrumb{
    background-color: {{$breadcrumb_bg_color}};
    border:1px solid #dddddd;
    color: {{$breadcrumb_text_color}};
    font-weight: bolder;
}
.breadcrumb > li > a{
    color: {{$breadcrumb_text_color}};
}
.panel-default > .panel-heading {
    background-color: {{$box_header_bg_color}};
    color: {{$box_header_text_color}};
    font-weight: bolder;
}
.panel-heading.searchfilter{
  background-color: {{$breadcrumb_bg_color}};
  color: {{$box_header_bg_color}};
}
.nav-tabs > li > a{
    color: {{$box_header_bg_color}};
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
      background-color: {{$box_header_bg_color}};
      color: {{$box_header_text_color}};
}
.btn-primary, .btn-primary:hover, .btn-primary:active, .btn-default, .btn-default:hover, .btn-default:active {
    background-color: {{$button_bg_color}};
    border-color: {{$button_bg_color}};
    color: {{$button_text_color}};
}
.rcorners1 {
    border-radius: 25px;
    background: #73AD21;
    padding: 20px;
    width: 200px;
    height: 150px;
}
</style>