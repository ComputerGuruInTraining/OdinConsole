<script>
    //js functions file (generic) which can be included as necessary

    //verify the user is the primary contact before displaying the credit card widget
    //returns true if session user is the primary contact
    //or false if not the primary contact of not logged in
    function verifyUserPrimaryContact(){

        var sessionId = "<?php echo session('id');?>";

        var primaryContact = "<?php echo session('primaryContact');?>";

        //if there is a session, ie this page has not been accessed via a public route
        if(sessionId !== ""){
            return sessionId === primaryContact;
        }
        return false;//extra measure to ensure user is logged in

    }

    function checkUserIsManager(){
        var userRole = "<?php echo session('role');?>";

        //if there is a session, ie this page has not been accessed via a public route
        if(userRole !== ""){
            return userRole === "Manager";
        }
        return false;
    }

</script>