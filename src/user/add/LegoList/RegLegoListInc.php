<?php 

declare(strict_types = 1);
namespace Src\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\User\Add\LegoList\LegoListContrClass;
use Src\Shared\Exceptions\InvalidInputException;
use Src\Shared\Exceptions\StmtFailedException;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(3);

// Check if data was submit through post
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Grabbing data
	$legoListVals = array(
		'listName'=> htmlspecialchars($_POST['listName'], ENT_QUOTES, 'UTF-8'),
		'pubPri'=> htmlspecialchars($_POST['pubPri'], ENT_QUOTES, 'UTF-8'),
		'uid'=> htmlspecialchars($_POST['uid'], ENT_QUOTES, 'UTF-8')
	);

	// Instantiate ListContr Class
	$legoList = new legoListContrClass($legoListVals);

	// Running error handlers and create list
	try {
		$legoList->addLegoList();
	} catch (InvalidInputException | StmtFailedException $e) {
		header('location: ' . $openerTp->getUrlReturn() . 'User/Add/LegoList/index.php?error=' . $e->getMessage());
		exit();
	}

	// Send user to dashboard page
	header('location: ' . $openerTp->getUrlReturn() . 'User/Dashboard/');
}
else
{
	// Send user to home page
	header('location: ' . $openerTp->getUrlReturn());
}