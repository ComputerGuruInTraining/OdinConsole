{{--Usage: generic confirmation page using layouts.master_layout without app sidebar and header. Useful when not logged into app--}}
@extends('layouts.master_layout')
@extends('sidebar')

@section('title-item')
    Upgrade Subscription
@stop

@section('custom-scripts')
    <script>



        var term = "monthly";

        const monthlyAmount1 = 29;
        const monthlyAmount2 = 59;
        const monthlyAmount3 = 99;
        const quarterlyAmount1 = 19;
        const quarterlyAmount2 = 39;
        const quarterlyAmount3 = 69;

        //initialise value to be the monthly amount because the monthly period is selected by default
        var amount1 = monthlyAmount1;
        var amount2 = monthlyAmount2;
        var amount3 = monthlyAmount3;


        window.onload = function (){
            defaultPage()
        };

        function defaultPage(){

            var amount1 = document.getElementById('amount1');
            var amount2 = document.getElementById('amount2');
            var amount3 = document.getElementById('amount3');
            var text1 = document.getElementById('text-1');
            var text2 = document.getElementById('text-2');
            var span = "<span class='tile-dollar'>$</span>";
            //change the color of the text
            text2.style.color = "white";
            text1.style.color = "#333";

            retrieveTerm();

        }

        function retrieveTerm(){
            //use js to get the toggled term from the html element
            var isChecked = document.getElementById('inputTerm').checked;
            var the_value = isChecked ? 1 : 0;

            //update the value in term and update the amounts for passing through with the url

            if(the_value == true){
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

//            console.log(the_value, term);

            //change the plan details to reflect the period
            updateDisplay(term);
        }

        function updateDisplay(period){

            var amount1 = document.getElementById('amount1');
            var amount2 = document.getElementById('amount2');
            var amount3 = document.getElementById('amount3');
            var text1 = document.getElementById('text-1');
            var text2 = document.getElementById('text-2');
            var span = "<span class='tile-dollar'>$</span>";



            if(period == "quarterly"){
                //change the amounts to reflect quarterly amounts
                amount1.innerHTML = span + quarterlyAmount1.toString();
                amount2.innerHTML = span + quarterlyAmount2.toString();
                amount3.innerHTML = span + quarterlyAmount3.toString();

                //change the color of the text
                text1.style.color = "white";
                text2.style.color = "#333";

            }else{
                //default display

                //change the amounts to reflect monthly amounts
                amount1.innerHTML = span + monthlyAmount1.toString();
                amount2.innerHTML = span + monthlyAmount2.toString();
                amount3.innerHTML = span + monthlyAmount3.toString();

                //change the color of the text
                text2.style.color = "white";
                text1.style.color = "#333";
            }

        }

    </script>

@stop

@section('page-content')

    <div class="col-md-12 box-layout">

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

        <div class="padding-left-x-lg">
            <div class="box-tile">
                <div class="tile-dollar-amount"><span class="tile-amount" id="amount1"><span class="tile-dollar">$</span>29</span></div>
                <div><span>Per Month</span>
                    <br/><br/>
                    <span class="alt-font">Up To 5 Users</span>
                </div>
                <br/><br/>
                <div>
                    <button type="button" class="btn btn-success tile-btn"
                            onclick="location.href='/subscription/upgrade/'+ amount1 + '/' + term;"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                </div>
            </div>

            <div class="box-tile">
                <div class="tile-dollar-amount"><span class="tile-amount" id="amount2"><span class="tile-dollar">$</span>59</span></div>
                <div><span>Per Month</span>
                    <br/><br/>
                    <span class="alt-font">6-10 Users</span>
                </div>
                <br/><br/>
                <div>
                    <button type="button" class="btn btn-success tile-btn"
                            onclick="location.href='/subscription/upgrade/'+ amount2 + '/' + term;"
                            style="color:white !important; background-color: #28C309 !important;
                        font-size: 16px !important;">
                        Get Started
                    </button>
                </div>
            </div>

            <div class="box-tile ">
                <div class="tile-dollar-amount"><span class="tile-amount" id="amount3"><span class="tile-dollar">$</span>99</span></div>
                <div><span>Per Month</span>
                    <br/><br/>
                    <span class="alt-font">11-20 Users</span>
                </div>
                <br/><br/>
                <div>
                    <button type="button" class="btn btn-success tile-btn"
                            onclick="location.href='/subscription/upgrade/'+ amount3 + '/' + term;"
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