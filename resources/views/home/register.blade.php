@extends('layouts.master')

@section('title') Register @stop

@section('content')
    <img src="{{ asset("/bower_components/AdminLTE/dist/img/odinLogoCurr.png") }}" alt="Odin Logo" height="60px" width="200px" style="position: absolute; left:30px; top:30px;"/>

    <div class="standard-links grey-color"><a href="/upgrade" target="_blank" ><h4 class="top-text-left">Pricing</h4></a></div>
    <div class="standard-links grey-color"><a href="/support" target="_blank"><h4 class="top-text">Support</h4></a></div>

    {{--purple header border--}}
    <section class="content-header content-header-register"></section>

    <div class="container" style="padding-top: 40px;">
        <div class="row">
            <div class="col-md-6 col-md-offset-3" >
                @if (count( $errors ) > 0)
                    @foreach ($errors->all() as $error)
                        <div class='alert error'>{!! $error !!}</div>
                    @endforeach
                @endif
                @if(isset($trial))
                    <div class="free-trial-register">
                        <div class="free-trial-div-register">
                            <p class="free-trial-line2-register">Take it for a spin…no charge, no commitment</p>

                            <p class="alert dark-green-font padding-top-btm-none">
                                    START FREE TRIAL
                            </p>
                            <p class="free-trial-line4-register">No credit card required</p>
                        </div>
                    </div>
                @endif
                <div class="panel panel-default" style="border-color: #4d2970;">
                    <div class="panel-heading"  style="color: white; background-color: #4d2970;">Register Company</div>
                    <div class="panel-body">

                        <form class="form-horizontal register" role="form" method="POST" action="{{ url('/register/company') }}">
                            {{ csrf_field() }}
                            {{--<div class="form-group">--}}
                                {{--<h4 class="register-headings">Company Details</h4>--}}
                            {{--</div>--}}
                            <div class="form-group{{ $errors->has('company') ? ' has-error' : '' }}" >
                                <label for="company" class="col-md-6 control-label">Company Name *</label>

                                {{--<div class="col-md-6">--}}
                                    <input id="company" type="text" class="form-control" name="company" value="{{ old('company') }}" required>

                                    @if ($errors->has('company'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('company') }}</strong>
                                    </span>
                                    @endif
                                {{--</div>--}}
                            </div>

                                <div class="form-group{{ $errors->has('owner') ? ' has-error' : '' }}">
                                    <label for="owner" class="col-md-4 control-label">Owner</label>

                                    {{--<div class="col-md-6">--}}
                                        <input id="owner" type="text" class="form-control" name="owner" value="{{ old('owner') }}">

                                        @if ($errors->has('owner'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('owner') }}</strong>
                                    </span>
                                        @endif
                                    {{--</div>--}}
                                </div>

                                    <div class="form-group">
                                        <p class="register-headings">Primary User Details</p>
                                    </div>

                                    <div class="form-group{{ $errors->has('first') ? ' has-error' : '' }}">
                                        <label for="first" class="col-md-8 control-label">First Name *</label>

                                        {{--<div class="col-md-6">--}}
                                            <input id="first" type="text" class="form-control" name="first" value="{{ old('first') }}" required>

                                            @if ($errors->has('first'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('first') }}</strong>
                                    </span>
                                            @endif
                                        {{--</div>--}}
                                    </div>

                                    <div class="form-group{{ $errors->has('last') ? ' has-error' : '' }}">
                                        <label for="last" class="col-md-8 control-label">Last Name *</label>

                                        {{--<div class="col-md-6">--}}
                                            <input id="last" type="text" class="form-control" name="last" value="{{ old('last') }}" required>

                                            @if ($errors->has('last'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('last') }}</strong>
                                    </span>
                                            @endif
                                        {{--</div>--}}
                                    </div>

                                    <div class="form-group{{ $errors->has('emailUser') ? ' has-error' : '' }}">
                                        <label for="emailUser" class="col-md-4 control-label">E-Mail *</label>

                                        {{--<div class="col-md-6">--}}
                                            <input id="emailUser" type="email" class="form-control" name="emailUser" value="{{ old('emailUser') }}" required>

                                            @if ($errors->has('emailUser'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('emailUser') }}</strong>
                                    </span>
                                            @endif
                                        {{--</div>--}}
                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-8 control-label">Password *</label>

                                        {{--<div class="col-md-6">--}}
                                            <input id="password" type="password" class="form-control" name="password" required>

                                            @if ($errors->has('password'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                            @endif
                                        {{--</div>--}}
                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm" class="col-md-8 control-label">Confirm Password *</label>

                                        {{--<div class="col-md-6">--}}
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                        {{--</div>--}}
                                    </div>

                                    <div class="form-group">

                                        @if(isset($trial))
                                            <div class="col-md-12" style="padding-top: 5px;">
                                                <button type="submit" class="btn btn-primary" style="color: white; background-color: #4d2970; margin-left:5px;
                                                        border: #4d2970;">
                                                        Start Free Trial
                                                </button>
                                                <a href="/login" class="btn btn-primary" style="color: white; background-color: #4d2970; margin-left:5px;
                                                    border: #4d2970;">
                                                    Cancel
                                                </a>
                                            </div>
                                        {{--@else--}}
                                            {{--<div class="col-md-12" style="padding-top: 5px;">--}}
                                                {{--<script--}}
                                                        {{--src="https://checkout.stripe.com/checkout.js" class="stripe-button"--}}
                                                        {{--data-key="pk_test_u5hJw0nEAL2kgix2Za91d3cV"--}}
                                                        {{--data-amount="999"--}}
                                                        {{--data-name="Odin Case Management"--}}
                                                        {{--data-description="Example charge"--}}
                                                        {{--data-image="https://stripe.com/img/documentation/checkout/marketplace.png"--}}
                                                        {{--data-locale="auto"--}}
                                                        {{--data-currency="aud">--}}
                                                {{--</script>--}}
                                                {{--<a href="/login" class="btn btn-primary" style="--}}
                                                    {{--display:inline-block;--}}
                                                    {{--color: white;--}}
                                                    {{--background-color: #4d2970;--}}
                                                    {{--font-size: large;--}}
                                                    {{--border: #4d2970;--}}
                                                    {{--padding: 8px 12px 10px 12px;--}}
                                                    {{--margin-top: -5px;--}}
                                                    {{--margin-left: 10px;">--}}
                                                    {{--Cancel--}}
                                                {{--</a>--}}
                                            {{--</div>--}}
                                        @endif

                                    </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop