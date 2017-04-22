@extends('sidebar')

@section('custom-menu')
    <li class="header"><h4>Manage @yield('custom-menu-title') Links</h4></li>
    <li class="active"><a href=@yield('create-link')><span>Create</span></a></li>
    <li class="active"><a href=@yield('edit-link')><span>Edit</span></a></li>
    <li class="active"><a href=@yield('delete-link')><span>Delete</span></a></li>
@stop