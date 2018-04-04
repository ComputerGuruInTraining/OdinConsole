<div class="col-md-12 box-layout">

    @if (count( $errors ) > 0)
        @foreach ($errors->all() as $error)
            <div class="alert error">{{ $error }}</div>
        @endforeach
    @endif

    @if(isset($confirm))
        <div class="white-font alert alert-success margin-top">{{$confirm}}</div>
    @endif

    @if(!isset($current))
        @if($inTrial === true)
            <div class="white-font alert alert-success margin-top">
                @if(isset($trialEndsAt))
                    Free trial period ends on: {{$trialEndsAt}}<br>
                @endif
                Subscribe Now, Pay Later.<br>
                Subscribing to a plan will incur no upfront charges today.
                You will be billed when your trial period ends.
            </div>
        @endif
    @else
        @if(isset($subscriptionTrial))
            <div class="white-font alert alert-success margin-top">
                Subscription Active.<br>
                Billing will commence when trial periods ends on: {{$subscriptionTrial}}
            </div>
        @endif
    @endif

    <div class="padding-tiles">
        <div class="tiles-heading alt-font">
            <p>Business Friendly Pricing</p>
            <p class="sm-font">No hidden fees</p>
        </div>
    </div>

    <div class="full-width">
        <!-- Rounded switch -->
        <label class="switch"><p class="text-2 alt-font" id="text-2">Paid Yearly</p>
            <input type="checkbox" id="inputTerm" name="term-slider" value="term" onchange="retrieveTerm()">
            <span class="slider round"><p class="text-1 alt-font" id="text-1">Paid Monthly</p></span>
        </label>
    </div>

    <script src="https://checkout.stripe.com/checkout.js"></script>

    <form style="display: none;" action="/subscription/payment" method="POST" id="stripeForm">
        {{ csrf_field() }}
        <input type="hidden" name="plan" value="plan" id="plan"/>
        <input type="hidden" name="period" value="period" id="period"/>
        <input type="hidden" name="trialEndsAt" value="" id="trialEndsAt"/>
        <input type="hidden" name="stripeToken" value="" id="stripeToken"/>
        <input type="hidden" name="stripeEmail" value="" id="stripeEmail"/>
    </form>


    <div class="padding-left-x-lg">
        <div class="box-tile" id="plan1-box">
            <div class="ribbon"><span>{{Config::get('constants.DISCOUNT1')}}% Off</span></div>
            <div class="tile-dollar-amount"><span class="tile-amount" id="plan1"><span
                            class="tile-dollar">$</span>29</span></div>
            <div><span>Per Month</span>
                <br/><br/>
                <span class="alt-font">Up To 5 Users</span>
            </div>
            <br/><br/>
            <div>
                @if(isset($current))
                    {{--swap plan, as currently on a plan, no payment now--}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                            onclick="swapBtn('plan1');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Change Plan
                    </button>
                @else
                    @if($inTrial === true)
                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                                onclick="submitDetailsBtn('plan1');"
                                style="color:white !important; background-color: #28C309 !important;
                            font-size: 16px !important;">
                            Get Started
                        </button>
                    @else

                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                                onclick="submitBtn('plan1');"
                                style="color:white !important; background-color: #28C309 !important;
                                font-size: 16px !important;">
                            Get Started
                        </button>
                    @endif
                @endif
            </div>
        </div>

        <div class="box-tile" id="plan2-box">
            <div class="ribbon"><span>{{Config::get('constants.DISCOUNT2')}}% Off</span></div>
            <div class="tile-dollar-amount"><span class="tile-amount" id="plan2"><span
                            class="tile-dollar">$</span>59</span></div>
            <div><span>Per Month</span>
                <br/><br/>
                <span class="alt-font">6-10 Users</span>
            </div>
            <br/><br/>
            <div>
                @if(isset($current))
                    {{--swap plan, as currently on a plan, no payment now--}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan2-btn"
                            onclick="swapBtn('plan2');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Change Plan
                    </button>
                @else
                    @if($inTrial === true)
                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan2-btn"
                                onclick="submitDetailsBtn('plan2');"
                                style="color:white !important; background-color: #28C309 !important;
                            font-size: 16px !important;">
                            Get Started
                        </button>
                    @else
                    <button class="btn btn-success tile-btn pay-btn" type="button" onclick="submitBtn('plan2');"
                                     id="plan2-btn" style="color:white !important; background-color: #28C309 !important;
                            font-size: 16px !important;">
                            Get Started
                        </button>
                    @endif
                @endif
            </div>
        </div>

        <div class="box-tile" id="plan3-box">
            <div class="ribbon"><span>{{Config::get('constants.DISCOUNT3')}}% Off</span></div>

            <div class="tile-dollar-amount"><span class="tile-amount" id="plan3"><span
                            class="tile-dollar">$</span>99</span></div>
            <div><span>Per Month</span>
                <br/><br/>
                <span class="alt-font">11-20 Users</span>
            </div>
            <br/><br/>
            <div>
                @if(isset($current))
                    {{--swap plan, as currently on a plan, no payment now--}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                            onclick="swapBtn('plan3');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Change Plan
                    </button>
                @else
                    @if($inTrial === true)
                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                                onclick="submitDetailsBtn('plan3');"
                                style="color:white !important; background-color: #28C309 !important;
                            font-size: 16px !important;">
                            Get Started
                        </button>
                    @else
                        <button class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                                type="button" onclick="submitBtn('plan3');"
                                style="color:white !important; background-color: #28C309 !important;
                                font-size: 16px !important;">
                            Get Started
                        </button>
                    @endif
                @endif
            </div>
        </div>

        <div class="box-tile" id="plan4-box">
            <div class="tile-dollar-amount" id="tile-text-amount">Contact Us</div>
            <div>
                <br/><br/>
                <span class="alt-font">21+ Users</span>
            </div>
            <br/><br/>
            <div>
                <button type="button" class="btn btn-success tile-btn" id="plan4-btn"
                        onclick="location.href='/support/users';"
                        style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                    Get In Touch
                </button>
            </div>
        </div>
    </div>

    <div class="explain-text explain-text-register explain-text-public">
        i) Prices quoted in USD
    </div>

    @if(isset($public))
        <div class="free-trial">
            <div class="separator-line"></div>

            <div class="free-trial-div">
                <p class="free-trial-line1">Not Sure This Is For You?</p>
                <p class="free-trial-line2">Take it for a spin…no charge, no commitment</p>

                <p><a href="/register/start-free-trial" class="free-trial-btn">START FREE TRIAL</a></p>
                <p class="free-trial-line4">No credit card required</p>
            </div>
        </div>
    @endif
</div>


