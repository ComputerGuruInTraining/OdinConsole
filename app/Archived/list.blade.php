@extends('resources.views.layouts.master_layout')
<!-- .content -->
<!-- Content Header 1 -->
@section('page-content')
    <!-- Main content area 1-->
    <section class="content">
        <!-- Your Page Content Here -->
        <div id="list-item">
        @yield('content-item')
        </div>
    </section>
    <!-- Content Header 2 -->
    <section class="content-header">
        <h1>
            @yield('title-list')
        </h1>
    </section>
    <!-- Main content area 2-->
    <section class="content">
        @yield('content-list')
    </section>
@stop
<!-- /.content -->