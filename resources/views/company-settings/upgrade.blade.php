{{--Usage: generic confirmation page using layouts.master_layout without app sidebar and header. Useful when not logged into app--}}
@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Upgrade Subscription
@stop

@section('custom-scripts')
    <script>

        var term = "monthly";

        const monthlyAmount1 = "<?php echo Config::get('constants.AMOUNT_M1');?>";
        const monthlyAmount2 = "<?php echo Config::get('constants.AMOUNT_M2');?>";
        const monthlyAmount3 = "<?php echo Config::get('constants.AMOUNT_M3');?>";
        const quarterlyAmount1 = "<?php echo Config::get('constants.AMOUNT_Q1');?>";
        const quarterlyAmount2 = "<?php echo Config::get('constants.AMOUNT_Q2');?>";
        const quarterlyAmount3 = "<?php echo Config::get('constants.AMOUNT_Q3');?>";

        window.onload = function (){
            defaultPage();

            var selected = "<?php echo $selected?>";
            var chosenTerm = "<?php echo $chosenTerm?>";

            if(selected !== undefined) {
                stripeConfig(selected, chosenTerm);
            }
        };

        function defaultPage(){

            var text1 = document.getElementById('text-1');
            var text2 = document.getElementById('text-2');

            //change the color of the text
            text2.style.color = "white";
            text1.style.color = "#333";

            retrieveTerm();

        }

        //get the period via toggle swtich and pass that to updateDisplay
        function retrieveTerm(){
            //use js to get the toggled term from the html element
            var isChecked = document.getElementById('inputTerm').checked;
            var the_value = isChecked ? 1 : 0;

            //update the value in term and update the amounts for passing through with the url
            if(Boolean(the_value) === true){
                //the slider is to the right and the period = Paid Quarterly
                term = "quarterly";
                amount1 = quarterlyAmount1;
                amount2 = quarterlyAmount2;
                amount3 = quarterlyAmount3;

            }else{
                term = "monthly";
                amount1 = monthlyAmount1;
                amount2 = monthlyAmount2;
                amount3 = monthlyAmount3;
            }

            //change the plan details to reflect the period
            updateDisplay(term);
        }

        //change the view to display the quarterly or monthly amounts
        function updateDisplay(period){

            var plan1 = document.getElementById('plan1');
            var plan2 = document.getElementById('plan2');
            var plan3 = document.getElementById('plan3');

            var text1 = document.getElementById('text-1');
            var text2 = document.getElementById('text-2');
            var span = "<span class='tile-dollar'>$</span>";

            if(period === "quarterly"){
                //change the amounts to reflect quarterly amounts
                plan1.innerHTML = span + quarterlyAmount1.toString();
                plan2.innerHTML = span + quarterlyAmount2.toString();
                plan3.innerHTML = span + quarterlyAmount3.toString();

                //change the color of the text
                text1.style.color = "white";
                text2.style.color = "#333";

                //display ribbon
                for(var i=0; i<3; i++) {
                    var ribbon = document.getElementsByClassName('ribbon')[i];
                    ribbon.style.display = "block";
                }

            }else{
                //default display

                //change the amounts to reflect monthly amounts
                plan1.innerHTML = span + monthlyAmount1.toString();
                plan2.innerHTML = span + monthlyAmount2.toString();
                plan3.innerHTML = span + monthlyAmount3.toString();

                //change the color of the text
                text2.style.color = "white";
                text1.style.color = "#333";

                //hide ribbon
                for(var h=0; h<3; h++) {
                    var ribbon = document.getElementsByClassName('ribbon')[h];
                    ribbon.style.display = "none";

                }
            }
        }

        function planAmount(planNum, term){

            if(planNum === 'plan1'){

                if(term === 'monthly'){
                    return monthlyAmount1*100;

                }else{
                    //term == 'quarterly'
                    return quarterlyAmount1*100;
                }

            }else if(planNum === 'plan2'){

                if(term === 'monthly'){
                    return monthlyAmount2*100;

                }else{
                    //term == 'quarterly'
                    return quarterlyAmount2*100;
                }
            }else if(planNum === 'plan3'){

                if(term === 'monthly'){
                    return monthlyAmount3*100;

                }else{
                    //term == 'quarterly'
                    return quarterlyAmount3*100;
                }
            }
        }

        function submitBtn(planNum){

            //update the values in the hidden input values for passing through to the controller
            var plan = document.getElementById('plan');
            plan.value = planNum;

            var period = document.getElementById('period');
            period.value = term;

            //open the checkout widget for payment processing
            stripeConfig(planNum, term);

        }

        function stripeConfig(planNum, term){

            var amount = planAmount(planNum, term);

            var tokenRes = document.getElementById('stripeToken');
            var tokenEmail = document.getElementById('stripeEmail');

            var logo = "<?php echo asset("/bower_components/AdminLTE/dist/img/odinlogoSm.jpg")?>";
            var company = "<?php echo Config::get('constants.COMPANY_NAME');?>";
            var key = "<?php echo Config::get('constants.STRIPE_TEST_KEY');?>";
            var userEmail = "<?php echo $email?>";

            var token = function(res){

                tokenRes.value = res.id;
                tokenEmail.value = res.email;

                document.getElementById("stripeForm").submit();
            };

            StripeCheckout.open({
                key:         key,
                amount:      amount,
                name:        company,
                email:       userEmail,
                image:       logo,
                description: 'Upgrade',
                panelLabel:  'Pay',
                currency: 'AUD',
                locale: 'auto',
                zipCode: 'true',
                token:       token
            });
        }

    </script>
@stop

@section('page-content')

    <div class="col-md-12 box-layout">
        @if(isset($confirm))
            <div class="white-font alert alert-success margin-top">{{$confirm}}</div>
        @endif

        <div class="padding-tiles">
            <div class="tiles-heading alt-font">
                <p>Business Friendly Pricing</p>
                <p class="sm-font">No hidden fees</p>
            </div>
        </div>

        <div class="full-width">
            <!-- Rounded switch -->
            <label class="switch"><p class="text-2 alt-font" id="text-2">Paid Quarterly</p>
                <input type="checkbox" id="inputTerm" name="term-slider" value="term" onchange="retrieveTerm()">
                <span class="slider round"><p class="text-1 alt-font" id="text-1">Paid Monthly</p></span>
            </label>
        </div>

        <script src="https://checkout.stripe.com/checkout.js"></script>

        <form style="display: none;" action="/subscription/payment" method="POST" id="stripeForm">
            {{ csrf_field() }}
            <input type="hidden" name="plan" value="plan" id="plan"/>
            <input type="hidden" name="period" value="period" id="period"/>
            <input type="hidden" name="stripeToken" value="" id="stripeToken"/>
            <input type="hidden" name="stripeEmail" value="" id="stripeEmail"/>
        </form>


        <div class="padding-left-x-lg">
            <div class="box-tile">
                <div class="ribbon"><span>{{Config::get('constants.DISCOUNT1')}}% Off</span></div>
                <div class="tile-dollar-amount"><span class="tile-amount" id="plan1"><span class="tile-dollar">$</span>29</span></div>
                <div><span>Per Month</span>
                    <br/><br/>
                    <span class="alt-font">Up To 5 Users</span>
                </div>
                <br/><br/>
                <div>
                    <button type="button" class="btn btn-success tile-btn pay-btn"
                            onclick="submitBtn('plan1');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                </div>
            </div>

            <div class="box-tile">
                <div class="ribbon"><span>{{Config::get('constants.DISCOUNT2')}}% Off</span></div>
                <div class="tile-dollar-amount"><span class="tile-amount" id="plan2"><span class="tile-dollar">$</span>59</span></div>
                <div><span>Per Month</span>
                    <br/><br/>
                    <span class="alt-font">6-10 Users</span>
                </div>
                <br/><br/>
                <div>
                    <button class="btn btn-success tile-btn pay-btn" type="button" onclick="submitBtn('plan2');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                </div>
            </div>

            <div class="box-tile ">
                <div class="ribbon"><span>{{Config::get('constants.DISCOUNT3')}}% Off</span></div>

                <div class="tile-dollar-amount"><span class="tile-amount" id="plan3"><span class="tile-dollar">$</span>99</span></div>
                <div><span>Per Month</span>
                    <br/><br/>
                    <span class="alt-font">11-20 Users</span>
                </div>
                <br/><br/>
                <div>
                    <button class="btn btn-success tile-btn pay-btn"
                            type="button" onclick="submitBtn('plan3');"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                </div>
            </div>

            <div class="box-tile ">
                <div class="tile-dollar-amount" id="tile-text-amount">Call Us</div>
                <div>
                    <br/><br/>
                    <span class="alt-font">21+ Users</span>
                </div>
                <br/><br/>
                <div>
                    <button type="button" class="btn btn-success tile-btn"
                            onclick="location.href='/support/users';"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get In Touch
                    </button>
                </div>
            </div>
        </div>

    </div>
@stop