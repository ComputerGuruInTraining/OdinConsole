@extends('layouts.master')

@section('title') Reset Password @stop

@section('content')
    <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px" width="200px" style="position: absolute; left:30px; top:30px;"/>

    <div class="container" style="padding-top: 150px;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" >
                @if (count( $errors ) > 0)
                    @foreach ($errors->all() as $error)
                        <div class='bg-danger alert'>{{ $error }}</div>
                    @endforeach
                @endif
                <div class="panel panel-default" style="border-color: #663974;">
                    <div class="panel-heading"  style="color: white; background-color: #663974;">Reset Password</div>
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form class="form-horizontal" role="form" method="GET" action="{{ url('/reset/pw') }}">
{{--                            {{ csrf_field() }}--}}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary" style="color: white; background-color: #663974;">
                                        Send Password Reset Link
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop