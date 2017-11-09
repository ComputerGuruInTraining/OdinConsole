@extends('layouts.master')

@section('title') Register @stop

@section('content')
    <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px" width="200px" style="position: absolute; left:30px; top:30px;"/>

    <div class="container" style="padding-top: 70px;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2" >
                @if (count( $errors ) > 0)
                    @foreach ($errors->all() as $error)
                        <div class='alert error'>{{ $error }}</div>
                    @endforeach
                @endif
                <div class="panel panel-default" style="border-color: #4d2970;">
                    <div class="panel-heading"  style="color: white; background-color: #4d2970;">Register Company</div>
                    <div class="panel-body">

                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/register/company') }}">
                            {{--{{ csrf_field() }}--}}
                            <div class="form-group">
                                <h4 class="register-headings">Company Details</h4>
                            </div>
                            <div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}">
                                <label for="company" class="col-md-4 control-label">Company Name</label>

                                <div class="col-md-6">
                                    <input id="company" type="text" class="form-control" name="company" value="{{ old('company') }}" required>

                                    @if ($errors->has('company'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                                <div class="form-group{{ $errors->has('owner') ? ' has-error' : '' }}">
                                    <label for="owner" class="col-md-4 control-label">Owner</label>

                                    <div class="col-md-6">
                                        <input id="owner" type="text" class="form-control" name="owner" value="{{ old('owner') }}" required>

                                        @if ($errors->has('owner'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('owner') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                    <div class="form-group">
                                        <h4 class="register-headings padding-top">Primary User Details</h4>
                                    </div>

                                    <div class="form-group{{ $errors->has('first') ? ' has-error' : '' }}">
                                        <label for="first" class="col-md-4 control-label">First Name</label>

                                        <div class="col-md-6">
                                            <input id="first" type="text" class="form-control" name="first" value="{{ old('first') }}" required>

                                            @if ($errors->has('first'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('first') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('last') ? ' has-error' : '' }}">
                                        <label for="last" class="col-md-4 control-label">Last Name</label>

                                        <div class="col-md-6">
                                            <input id="last" type="text" class="form-control" name="last" value="{{ old('last') }}" required>

                                            @if ($errors->has('last'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('last') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('emailUser') ? ' has-error' : '' }}">
                                        <label for="emailUser" class="col-md-4 control-label">E-Mail</label>

                                        <div class="col-md-6">
                                            <input id="emailUser" type="email" class="form-control" name="emailUser" value="{{ old('emailUser') }}" required>

                                            @if ($errors->has('emailUser'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('emailUser') }}</strong>
                                    </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">Password</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password" class="form-control" name="password" required>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                                        <div class="col-md-6">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-primary" style="color: white; background-color: #4d2970;">
                                                Register
                                            </button>
                                            <a href="/login" class="btn btn-primary" style="
                                                color: white;
                                                background-color: #4d2970;
                                                font-size: large;">Cancel</a>
                                        </div>
                                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop