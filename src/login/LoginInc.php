<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Login\LoginContrClass;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(1);

// Check if data was submit through post
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Grabbing data
	$usna = htmlspecialchars($_POST['usna'], ENT_QUOTES, 'UTF-8');
	$pwd = htmlspecialchars($_POST['pwd'], ENT_QUOTES, 'UTF-8');

	// Instantiate LoginContr Class
	$login = new LoginContrClass($usna, $pwd);

	// Running error handlers and user login
	$login->loginUser();

	// Send user to dashboard page
	header('location: ' . $openerTp->getUrlReturn() . 'User/Dashboard/');
}
else
{
	// Send user to home page
	header('location: ' . $openerTp->getUrlReturn());
}
