@extends('layouts.master')

@section('title') Privacy Policy @stop

{{--@section('my-styles')--}}

{{--<style>--}}
{{--.top-text {--}}
{{--position: absolute;--}}
{{--right: 50px;--}}
{{--top: 50px;--}}
{{--}--}}

{{--#grey-color {--}}
{{--color: #555;--}}
{{--}--}}

{{--.row > .title-bg > .title-block {--}}
{{--background-color: #f5f5f5 !important;--}}
{{--height: 500px;--}}
{{--margin-top: 70px;--}}
{{--width: 100%;--}}
{{--}--}}

{{--.title-block {--}}

{{--padding-top: 200px;--}}
{{--text-align: center;--}}
{{--}--}}

{{--.title {--}}
{{--font-size: 5.4rem;--}}
{{--}--}}

{{--.title-heading {--}}
{{--text-align: center;--}}
{{--font-size: x-large;--}}
{{--padding-top: 50px;--}}

{{--}--}}

{{--.title-text {--}}
{{--text-align: center;--}}
{{--font-size: 22px;--}}

{{--}--}}

{{--.title-content {--}}
{{--height: 400px;--}}
{{--margin: 20px 150px;--}}
{{--}--}}
{{--</style>--}}

{{--@stop--}}

@section('content')

    <img src="{{ asset("/bower_components/AdminLTE/dist/img/ODIN-Logo.png") }}" alt="Odin Logo" height="60px"
         width="200px"
         style="position: absolute; left:30px; top:30px;"/>
    {{--<h5 class="top-text">odinlitemail@gmail.com</h5>--}}


    <div class="row">
        <div class="title-bg-app">
            <div class="title-block-app">
                <p class="title-app">Privacy Policy</pclass>
            </div>
        </div>
        <div class="title-content-app">
            <p>This privacy policy discloses the privacy practices for OdinLite Mobile App and Management Console.
                This privacy policy applies solely to information collected by this web site. It will notify you of the
                following:
            </p>

            <ul>

                <li>What personally identifiable information is collected from you through the web site, how it is used
                    and with whom it may be shared.</li>
                <li>What choices are available to you regarding the use of your data.</li>
                <li>The security procedures in place to protect the misuse of your information.</li>
                <li>How you can correct any inaccuracies in the information.</li>

            </ul>
            <p class="text-bold">Information Collection, Use, and Sharing</p>
            <p>
                We are the sole owners of the information collected on this site. We only have access to/collect
                information that you voluntarily give us
                via email or other direct contact from you. We will not sell or rent this information to anyone.
            </p>
            <p>
                We will use your information to respond to you, regarding the reason you contacted us. We will not share
                your information with any third party
                outside of our organization, other than as necessary to fulfill your request, e.g. to ship an order.
            </p>
            <p class="text-bold"> Your Access to and Control Over Information</p>
            <p>
                You may opt out of any future contacts from us at any time. You can do the following at any time by
                contacting us via email:
            </p>
            <ul>
                <li>See what data we have about you, if any.</li>
                <li>Change/correct any data we have about you.</li>
                <li>Have us delete any data we have about you.</li>
                <li>Express any concern you have about our use of your data.</li>
            </ul>

            <p class="text-bold"> Security </p>
            <p> We take precautions to protect your information. When you submit sensitive information via the website,
                your information is protected both online and offline.
            </p>

            <p> Wherever we collect sensitive information (such as credit card data), that information is encrypted and
                transmitted to us in a secure way.
                You can verify this by looking for a closed lock icon at the bottom of your web browser, or looking for
                “https” at the beginning of the
                address of the web page.
            </p>

            <p> While we use encryption to protect sensitive information transmitted online, we also protect your
                information offline. Only employees
                who need the information to perform a specific job (for example, billing or customer service) are
                granted access to personally identifiable information.
            </p>

            <p class="text-bold">Updates</p>
            <p> Our Privacy Policy may change from time to time and all updates will be posted on this page.</p>

            <p> If you feel that we are not abiding by this privacy policy, you should contact us immediately via email:
                <span class="text-bold">odinlitemail@gmail.com</span>
            </p>
        </div>
    </div>
@stop