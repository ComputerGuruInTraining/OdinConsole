{{--TODO: change to extend a create_layout once layout exists--}}
{{--TODO: fix sidemenu--}}
@extends('layouts.master_layout')
@extends('sidebar')


@section('title-item')
    Create Location
@stop

@section('page-content')

    <div class='col-lg-4 col-lg-offset-4 form-pages'>

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ Form::open(['role' => 'form', 'url' => '/location/created']) }}
            @include('map')
            {{--FIXME: the app needs to run entirely independent of technical support. Therefore, the address input needs to check the address is right or interpretable,
             and if not not allow the entry to be added. Possibly via a service that checks the address is valid, or otherwise using the entered address to allow
             user flexibility but this may mean the address will not display on the map, unless a segment of the address can be taken and interpreted into a valid geo-code.--}}
            <div class='form-group'>
                {{ Form::label('name', 'Address Alias *') }}
                {{ Form::text('name', null, ['placeholder' => 'eg UC Building 25', 'class' => 'form-control']) }}
            </div>

            <div class='form-group'>
                {{ Form::label('address', 'Address *') }}
                {{ Form::text('address', null, ['placeholder' => 'eg University of Canberra, Bruce ACT 2617', 'class' => 'form-control']) }}
            </div>

            <div class='form-group'>
                {{ Form::label('info', 'Additional Address Details') }}
                {{ Form::text('info', null, ['placeholder' => 'ie Building Number or Name, Unit Number, Company Name, etc.', 'class' => 'form-control']) }}
            </div>

            <div class='form-group form-buttons'>
                {{--TODO: ??confirm button first, ask user to check input, then save as a measure to ensure address correct--}}
                {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                {{--TODO: cancel btn code--}}
                {{ Form::button('Cancel', ['class' => 'btn btn-primary']) }}
            </div>

            {{--TODO: include?? If the user cannot select the address from the map in some rare cases--}}
            <div id="address-catch">
                <div class='form-group'>
                    {{ Form::label('typedName', 'Address alias') }}
                    {{ Form::text('typedName', null, ['placeholder' => 'eg Building 25', 'class' => 'form-control']) }}
                </div>

                <div class='form-group'>
                    {{ Form::label('address1', 'Address Line 1') }}
                    {{ Form::text('address1', null, ['placeholder' => 'eg University of Canberra, Pantowora St', 'class' => 'form-control']) }}
                    <p>Street address, company name</p>
                </div>

                <div class='form-group'>
                    {{ Form::label('address2', 'Address Line 2') }}
                    {{ Form::text('address2', null, ['placeholder' => 'eg Building 25', 'class' => 'form-control']) }}
                    <p>Unit, building, floor, etc.</p>
                </div>

                <div class='form-group'>
                    {{ Form::label('suburb', 'Suburb') }}
                    {{ Form::text('suburb', null, ['class' => 'form-control']) }}
                </div>

                <div class='form-group'>
                    {{ Form::label('state', 'State') }}
                    {{ Form::text('state', null, ['class' => 'form-control']) }}
                </div>

                <div class='form-group'>
                    {{ Form::label('postcode', 'Postcode') }}
                    {{ Form::text('postcode', null, ['class' => 'form-control']) }}
                </div>

                {{--TODO: country line? international addresses? optimize for US addresses?--}}

                {{--TODO: Drop-down with a list of Current Clients and a New Client option to create ones not in the list (simply create name,
                any other fields to be created at the manager's convenience depending on number of fields)--}}
                {{--<div class='form-group'>--}}
                {{--{{ Form::label('client', 'Client') }}--}}
                {{--{{ Form::text('client', null, ['placeholder' => 'Company Name', 'class' => 'form-control']) }}--}}
                {{--</div>--}}

                {{--TODO: Drop-down with a list of Areas/Grouped Addresses and an option to create ones not in the list--}}
                {{--<div class='form-group'>--}}
                {{--{{ Form::label('address_group', 'Address Group') }}--}}
                {{--{{ Form::select('address_group', ['UC' => 'University of Canberra', 'Tuggeranong Shopping Centre']) }}--}}
                {{--{{ Form::text('address_group', null, ['placeholder' => 'eg University of Canberra', 'class' => 'form-control']) }}--}}
                {{--</div>--}}
            </div>
            {{ Form::close() }}

    </div>
@stop

