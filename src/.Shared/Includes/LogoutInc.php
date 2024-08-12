<?php 

declare(strict_types = 1);
namespace Src\Shared\Inc;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

session_start();
session_unset();
session_destroy();

// Send user back to home page
header("location: ../../");