<?php 

declare(strict_types = 1);
namespace Src\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\User\Add\LegoList\LegoListContrClass;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(3);

// Check if data was submit through post
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Grabbing data
	$listName = htmlspecialchars($_POST['listName'], ENT_QUOTES, 'UTF-8');
	$pubPri = htmlspecialchars($_POST['pubPri'], ENT_QUOTES, 'UTF-8');
	$uid = htmlspecialchars($_POST['uid'], ENT_QUOTES, 'UTF-8');

	// Instantiate ListContr Class
	$legoList = new legoListContrClass($listName, $pubPri, $uid);

	// Running error handlers and create list
	$legoList->addLegoList();

	// Send user to dashboard page
	header('location: ' . $openerTp->getUrlReturn() . 'User/Dashboard/');
}
else
{
	// Send user to home page
	header('location: ' . $openerTp->getUrlReturn());
}