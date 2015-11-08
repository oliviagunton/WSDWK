<?php
	/*
	
	login.php

	Controller for logging in.
	Associated views: login_form.php, /error_form.php/

	*/
    

    // configuration
    require("../includes/config.php"); 

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("login_form.php", ["title" => "Log In"]);
    }

?>