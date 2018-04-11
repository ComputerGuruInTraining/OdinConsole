<div class="col-md-12 box-layout">

    @if (count( $errors ) > 0)
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger margin-top">{{ $error }}</div>
        @endforeach
    @endif

    @if(isset($confirm))
        <div class="white-font alert alert-odin-success margin-top">{{$confirm}}</div>
    @endif

    @if(!isset($current))
        @if($inTrial === true)
            <div class="alert alert-odin-info margin-top">
                Free trial period ends on: {{$trialEndsAt}}<br>
                Subscribe Now, Pay Later.<br>
                Subscribing to a plan will incur no upfront charges today.
                You will be billed when your trial period ends.
            </div>
        @elseif($inTrial === false)
            <div class="alert alert-danger margin-top">
                Trial period ended! <br />
                Kindly subscribe to a plan to continue using the application suite.
            </div>
        @endif

        @if(isset($subTermGrace))
            <div class="alert alert-danger margin-top">
                Subscription cancelled! <br />
                Kindly subscribe to a plan to continue using the application suite beyond the grace period.<br />
                Resume Subscription Now, Pay Later.<br />
                You will be billed when your grace period ends.
            </div>
        @elseif(isset($subTermCancel))
            <div class="alert alert-danger margin-top">
                Subscription cancelled! <br />
                Kindly subscribe to a plan to continue using the application suite.
            </div>
        @endif

    @else
        @if(isset($subscriptionTrial))
            <div class="white-font alert alert-odin-info margin-top">
                Subscription Active.<br>
                Your nominated credit card will be charged once the trial periods ends on: {{$subscriptionTrial}}
            </div>
        @endif
    @endif

    {{--Modal that displays when a user opts to swap a plan via public view pricing model, then logs in, and modal displays to confirm the swap--}}
    <div id="myModal" class="modal-odin">
        <div class="modal-odin-content">
            <div class="modal-odin-header">
                <span class="close-odin">&times;</span>
                <h3>Upgrade Subscription</h3>
            </div>
            <div class="modal-odin-body">
                @if(isset($numUsers))
                    <p>Current Subscription - Billing Cycle: {{ucfirst($subscriptionTerm)}}; Number of Users: {{$numUsers}}</p>
                @endif
                @if(isset($newNumUsers))
                    <p>New Subscription&nbsp&nbsp&nbsp&nbsp&nbsp- Billing Cycle: {{ucfirst($chosenTerm)}}; Number of Users: {{$newNumUsers}}</p>
                @endif
                <p class="padding-bottom-10">Kindly confirm you would like to change your subscription?</p>
                <button type="button" class="btn btn-border" onclick="submitSwap()">Confirm</button>
                <button type="button" class="btn btn-border" onclick="cancelSwap()">Cancel</button>
            </div>
        </div>
    </div>

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
        <input type="hidden" name="formPath" value="" id="formPath"/>
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
                @elseif(isset($inTrial))
                        @if($inTrial === true)
                            {{--on trial, user just submits credit card details--}}
                            <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                                    onclick="submitDetailsBtn('plan1');"
                                    style="color:white !important; background-color: #28C309 !important;
                                font-size: 16px !important;">
                                Get Started
                            </button>
                        @else
                            {{--not on trial, user submits payment--}}
                            <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                                    onclick="submitBtn('plan1');"
                                    style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                                Get Started
                            </button>
                        @endif
                @elseif(isset($subTermGrace))
                    {{--resume subscription, also swap if needs be by accessing $subPlanGrace in js function? payment? see docs--}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                            onclick="resumeBtn('plan1');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                @elseif(isset($subTermCancel))
                    {{--cancelled subscription, --}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                            onclick="submitBtn('plan1');"
                            style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                        Get Started
                    </button>
                @elseif(isset($public))
                    {{--public view, all btns should --}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan1-btn"
                            onclick="submitBtn('plan1');"
                            style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                        Get Started
                    </button>
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
                @elseif(isset($inTrial))
                    @if($inTrial === true)
                        {{--on trial, user just submits credit card details--}}
                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan2-btn"
                                onclick="submitDetailsBtn('plan2');"
                                style="color:white !important; background-color: #28C309 !important;
                                font-size: 16px !important;">
                            Get Started
                        </button>
                    @else
                        {{--not on trial, user submits payment--}}
                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan2-btn"
                                onclick="submitBtn('plan2');"
                                style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                            Get Started
                        </button>
                    @endif
                @elseif(isset($subTermGrace))
                    {{--resume subscription, also swap if needs be by accessing $subPlanGrace in js function? payment? see docs--}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan2-btn"
                            onclick="resumeBtn('plan2');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                @elseif(isset($subTermCancel))
                    {{--cancelled subscription, --}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan2-btn"
                            onclick="submitBtn('plan2');"
                            style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                        Get Started
                    </button>
                @elseif(isset($public))
                    {{--public view, all btns should --}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan2-btn"
                            onclick="submitBtn('plan2');"
                            style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                        Get Started
                    </button>
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
                @elseif(isset($inTrial))
                    @if($inTrial === true)
                        {{--on trial, user just submits credit card details--}}
                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                                onclick="submitDetailsBtn('plan3');"
                                style="color:white !important; background-color: #28C309 !important;
                                font-size: 16px !important;">
                            Get Started
                        </button>
                    @else
                        {{--not on trial, user submits payment--}}
                        <button type="button" class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                                onclick="submitBtn('plan3');"
                                style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                            Get Started
                        </button>
                    @endif
                @elseif(isset($subTermGrace))
                    {{--resume subscription, also swap if needs be by accessing $subPlanGrace in js function? payment? see docs--}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                            onclick="resumeBtn('plan3');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                @elseif(isset($subTermCancel))
                    {{--cancelled subscription, --}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                            onclick="submitBtn('plan3');"
                            style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                        Get Started
                    </button>
                @elseif(isset($public))
                    {{--public view, all btns should --}}
                    <button type="button" class="btn btn-success tile-btn pay-btn" id="plan3-btn"
                            onclick="submitBtn('plan3');"
                            style="color:white !important; background-color: #28C309 !important;
                                    font-size: 16px !important;">
                        Get Started
                    </button>
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


