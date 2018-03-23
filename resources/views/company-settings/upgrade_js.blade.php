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

        //check to see if the selected variable has a value,
        // else it will be a blank string if initialised as null on controller ie routed via = '/subscription/upgrade'
        //Usage: '/upgrade/subscription/{plan}/{term}' route passes through the plan and term
        // and opens the checkout widget for user
        if(selected != ""){

            stripeConfig(selected, chosenTerm);
        }

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

    function submitBtn(planNum){

        //update the values in the hidden input values for passing through to the controller
        var plan = document.getElementById('plan');
        plan.value = planNum;

        var period = document.getElementById('period');
        period.value = term;

//        var theUrl = 'https://odinlitemgmttest.azurewebsites.net/error-page';
//
//        var xmlHttp = new XMLHttpRequest();
//        xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
//        xmlHttp.send( null );
//        return xmlHttp.responseText;

        alert("Upgrade Plan is a Work in Progress. Please watch this space.");

        //open the checkout widget for payment processing
//        stripeConfig(planNum, term);//fixme once complete, uncomment

    }

    function stripeConfig(planNum, term){

        var amount = planAmount(planNum, term);
        var currency = 'USD';//todo: make user toggle and variable

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
            currency: currency,
            locale: 'auto',
            zipCode: 'true',
            token:       token
        });
    }

</script>