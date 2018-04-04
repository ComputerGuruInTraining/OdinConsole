<script>

    var term = "monthly";

    const monthlyAmount1 = "<?php echo Config::get('constants.AMOUNT_M1');?>";
    const monthlyAmount2 = "<?php echo Config::get('constants.AMOUNT_M2');?>";
    const monthlyAmount3 = "<?php echo Config::get('constants.AMOUNT_M3');?>";
    const yearlyAmount1 = "<?php echo Config::get('constants.AMOUNT_Y1');?>";
    const yearlyAmount2 = "<?php echo Config::get('constants.AMOUNT_Y2');?>";
    const yearlyAmount3 = "<?php echo Config::get('constants.AMOUNT_Y3');?>";

    window.onload = function (){
        defaultPage();

        var selected = "<?php echo $selected?>";
        var chosenTerm = "<?php echo $chosenTerm?>";
        var current = "<?php echo $current?>";

        //check to see if the selected variable has a value, if so, open checkout widget
        // else it will be a blank string if initialised as null on controller ie routed via = '/subscription/upgrade'
        //Usage: '/upgrade/subscription/{plan}/{term}' route passes through the plan and term
        // and opens the checkout widget for user (naving from pricing model and via login page this page opens)
        if(selected !== ""){
            //2 scenarios
            //current plan in which case swap
            //no plan, in which case createSubscription (no trial) or createTrialSubscription

            if(current !== "") {
                //swap plans
                swapBtn(selected);

            }else {
                //create subscription option 1: with trial $0
//                todo: test this
                /*  var inTrial = "<?php echo $inTrial?>";
                 if(inTrial !== ""){
                 if(inTrial === true){
                 stripeConfig(selected, chosenTerm,0);


                 }

                 }else{
                 //create subscription option 2: no trial $Costs
                 stripeConfig(selected, chosenTerm);
                 }
                 }*/
                stripeConfig(selected, chosenTerm);
            }
        }

        //display current plan, if on a plan
        if(current !== ""){
            displayCurrent(current);
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

    function displayCurrent(current){

        if(current === 'plan1') {
            //change box style
            var currentPlan = document.getElementById('plan1-box');
            currentPlan.style.backgroundColor = '#909090';
            currentPlan.style.borderColor = '#333';

            //change text on btn
            var currentBtn = document.getElementById('plan1-btn');
            currentBtn.innerHTML = 'Current Plan';

            //disable current plan btn
            currentBtn.setAttribute('disabled', 'disabled');
        }else if(current === 'plan2') {
            //change box style
            var currentPlan = document.getElementById('plan2-box');
            currentPlan.style.backgroundColor = '#909090';
            currentPlan.style.borderColor = '#333';

            //change text on btn
            var currentBtn = document.getElementById('plan2-btn');
            currentBtn.innerHTML = 'Current Plan';

            //disable current plan btn
            currentBtn.setAttribute('disabled', 'disabled');
        }else if(current === 'plan3') {
            //change box style
            var currentPlan = document.getElementById('plan3-box');
            currentPlan.style.backgroundColor = '#909090';
            currentPlan.style.borderColor = '#333';

            //change text on btn
            var currentBtn = document.getElementById('plan3-btn');
            currentBtn.innerHTML = 'Current Plan';

            //disable current plan btn
            currentBtn.setAttribute('disabled', 'disabled');
        }else if(current === 'plan4') {
            //change box style
            var currentPlan = document.getElementById('plan4-box');
            currentPlan.style.backgroundColor = '#909090';
            currentPlan.style.borderColor = '#333';

            //change text on btn
            var currentBtn = document.getElementById('plan4-btn');
            currentBtn.innerHTML = 'Contact to Update';

            //disable current plan btn
            currentBtn.setAttribute('disabled', 'disabled');
        }
    }

    //verify the user is the primary contact before displaying the credit card widget
    //returns true if session user is the primary contact
    //or false if not the primary contact of not logged in
    function verifyPrimaryContact(){

        var sessionId = "<?php echo session('id');?>";

        var primaryContact = "<?php echo session('primaryContact');?>";

        //if there is a session, ie this page has not been accessed via a public route
        if(sessionId !== ""){
            return sessionId === primaryContact;
//                return true;
//
//            }else{
//                //route users to same page with an error subscription/upgrade
//                return false;
//            }
        }
        return false;//extra measure to ensure if user not logged in cannot process payment

    }

    //get the period via toggle swtich and pass that to updateDisplay
    function retrieveTerm(){
        //use js to get the toggled term from the html element
        var isChecked = document.getElementById('inputTerm').checked;
        var the_value = isChecked ? 1 : 0;

        //update the value in term and update the amounts for passing through with the url
        if(Boolean(the_value) === true){
            //the slider is to the right and the period = Paid yearly
            term = "yearly";
            amount1 = yearlyAmount1;
            amount2 = yearlyAmount2;
            amount3 = yearlyAmount3;

        }else{
            term = "monthly";
            amount1 = monthlyAmount1;
            amount2 = monthlyAmount2;
            amount3 = monthlyAmount3;
        }

        //change the plan details to reflect the period
        updateDisplay(term);
    }

    //change the view to display the yearly or monthly amounts
    function updateDisplay(period){

        var plan1 = document.getElementById('plan1');
        var plan2 = document.getElementById('plan2');
        var plan3 = document.getElementById('plan3');

        var text1 = document.getElementById('text-1');
        var text2 = document.getElementById('text-2');
        var span = "<span class='tile-dollar'>$</span>";

        if(period === "yearly"){
            //change the amounts to reflect yearly amounts
            plan1.innerHTML = span + yearlyAmount1.toString();
            plan2.innerHTML = span + yearlyAmount2.toString();
            plan3.innerHTML = span + yearlyAmount3.toString();

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
                //term == 'yearly'
                return (yearlyAmount1*100)*12;
            }

        }else if(planNum === 'plan2'){

            if(term === 'monthly'){
                return monthlyAmount2*100;

            }else{
                //term == 'yearly'
                return (yearlyAmount2*100)*12;
            }
        }else if(planNum === 'plan3'){

            if(term === 'monthly'){
                return monthlyAmount3*100;

            }else{
                //term == 'yearly'
                return (yearlyAmount3*100)*12;
            }
        }
    }

    function userLoggedIn(){

        var public = "<?php echo $public;?>";

        return (public === "");//true if null was sent from controller, false if public = true sent from controller ie public routes

    }

    //create new subscription request, not in trial, accept payment now
    function submitBtn(planNum){

        //update the values in the hidden input values for passing through to the controller
        var plan = document.getElementById('plan');
        plan.value = planNum;

        var period = document.getElementById('period');
        period.value = term;

        //open the checkout widget for payment processing
        stripeConfig(planNum, term);

    }

    //create new subscription request, in trial, just collect credit card details now and pay $0
    function submitDetailsBtn(planNum){

        //update the values in the hidden input values for passing through to the controller
        var plan = document.getElementById('plan');
        plan.value = planNum;

        var period = document.getElementById('period');
        period.value = term;

        var trialEndsAt = "<?php echo $trialEndsAt;?>";

        if(trialEndsAt !== ""){
            var trial = document.getElementById('trialEndsAt');
            trial.value = trialEndsAt;
        }

        //open the checkout widget for payment processing
        stripeConfig(planNum, term, 0);

    }

    function swapBtn(planNum){

        //update the values in the hidden input values for passing through to the controller
        var plan = document.getElementById('plan');
        plan.value = planNum;

        var period = document.getElementById('period');
        period.value = term;

        //todo proper swap code


        alert("Swap Plan is a Work in Progress. Please watch this space.");

        //will be:
        //document.getElementById("stripeForm").submit();


    }


    function stripeConfig(planNum, term, amount){

        //check if user logged in first, else redirect them to login page with the plan and period sent with request
        var booleanLoggedIn = userLoggedIn();

        if(booleanLoggedIn === false){

            var urlPublic = "<?php echo url('/login/upgrade');?>";

            var domain = urlPublic + '/' + planNum + '/' + term;

            window.location.replace(domain);

        }else {

            //check if user is the primary contact first, else, redirect them to same page with an error msg
            var verifyUser = verifyPrimaryContact();

            if (verifyUser === false) {

                var urlContact = "<?php echo url('subscription/upgrade/nonprimary');?>";

                window.location.replace(urlContact);

            } else {

                var panelLabel = "";
                var desc = "";

                var currency = 'USD';//todo: make user toggle and variable

                var tokenRes = document.getElementById('stripeToken');
                var tokenEmail = document.getElementById('stripeEmail');

                var logo = "<?php echo asset("/bower_components/AdminLTE/dist/img/odinlogoSm.jpg")?>";
                var company = "<?php echo Config::get('constants.COMPANY_NAME');?>";
                var key = "<?php echo Config::get('services.stripe.key');;?>";
                var userEmail = "<?php echo $email?>";

                var token = function (res) {

                        console.log(res);

                        tokenRes.value = res.id;
                        tokenEmail.value = res.email;

                        document.getElementById("stripeForm").submit();
                };

                if (amount === undefined) {

                    amount = planAmount(planNum, term);
                    panelLabel = 'Pay';
                    desc = 'Create Subscription';

                    StripeCheckout.open({
                        key: key,
                        amount: amount,
                        name: company,
                        email: userEmail,
                        image: logo,
                        description: desc,
                        panelLabel: panelLabel,
                        currency: currency,
                        locale: 'auto',
                        zipCode: true,
                        token: token
                    });




                } else {

                    panelLabel = 'Pay $0';
                    desc = 'Subscribe Now, Pay Later';

                    StripeCheckout.open({
                        key: key,
                        name: company,
                        email: userEmail,
                        image: logo,
                        description: desc,
                        panelLabel: panelLabel,
                        currency: currency,
                        locale: 'auto',
                        zipCode: true,
                        token: token
                    });

                }
            }
        }
    }

</script>