<?php 

session_start();
session_unset();
session_destroy();

// Send user back to home page
header("location: ../../");