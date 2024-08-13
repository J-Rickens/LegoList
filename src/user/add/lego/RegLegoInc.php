<?php 

declare(strict_types = 1);
namespace Src\User\Add\Lego;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\User\Add\Lego\LegoContrClass;
use Src\Shared\Exceptions\InvalidInputException;
use Src\Shared\Exceptions\StmtFailedException;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(3);

// Check if data was submit through post
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	// Grabbing data
	$legoVal = array(
		'legoID'=> htmlspecialchars($_POST['legoID'], ENT_QUOTES, 'UTF-8'),
		'pieceCount'=> htmlspecialchars($_POST['pieceCount'], ENT_QUOTES, 'UTF-8'),
		'legoName'=> htmlspecialchars($_POST['legoName'], ENT_QUOTES, 'UTF-8'),
		'collection'=> htmlspecialchars($_POST['collection'], ENT_QUOTES, 'UTF-8'),
		'cost'=> htmlspecialchars($_POST['cost'], ENT_QUOTES, 'UTF-8')
	);
	$uid = htmlspecialchars($_POST['uid'], ENT_QUOTES, 'UTF-8');

	// Instantiate RegisterContr Class
	$lego = new LegoContrClass($legoVal);

	// Running error handlers and user signup
	try {
		$lego->addLego();
	} catch (InvalidInputException | StmtFailedException $e) {
		header('location: ' . $openerTp->getUrlReturn() . 'User/Add/Lego/index.php?error=' . $e->getMessage());
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
