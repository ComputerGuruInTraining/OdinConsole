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

        //check to see if the selected variable has a value,
        // else it will be a blank string if initialised as null on controller ie routed via = '/subscription/upgrade'
        //Usage: '/upgrade/subscription/{plan}/{term}' route passes through the plan and term
        // and opens the checkout widget for user
        if(selected != ""){

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