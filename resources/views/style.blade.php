<?php

  $bg_color = '#008fb3';
  $font_color = '#ffffff';

?>
<style>
/* new theme*/
.navbar-default {
    background-color: {{$bg_color}}; /*#26acd9;*/    
}
.navbar-default .navbar-nav > li > a {
    color: {{$font_color}};
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
    color: {{$bg_color}};
    font-weight: bolder;
}
.breadcrumb > li > a{
    color: {{$bg_color}};
}
.panel-default > .panel-heading {
    background-color: {{$bg_color}};
    color: {{$font_color}};
    font-weight: bolder;
}
.panel-heading.searchfilter{
  background-color: #f5f5f5;
  color: {{$bg_color}};
}
.nav-tabs > li > a{
    color: {{$bg_color}};
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
      background-color: {{$bg_color}};
      color: {{$font_color}};
}
.rcorners1 {
    border-radius: 25px;
    background: #73AD21;
    padding: 20px;
    width: 200px;
    height: 150px;
}
</style>