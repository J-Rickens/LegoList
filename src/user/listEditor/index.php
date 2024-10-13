<?php 

declare(strict_types = 1);
namespace Src\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;
use Src\User\ListEditor\ListEditorContrClass;
use Src\Shared\Exceptions\StmtFailedException;
use Src\Shared\Exceptions\InvalidInputException;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(2);
$urlTitle = 'Editor';

// Redurect user if not loged in
if ($openerTp->startSession()) {
	header('location: '. $openerTp->getUrlReturn() .'Login');
}

$editor = new ListEditorContrClass();

// check if list ID in _GET
if (isset($_GET['listid'])) {
	// check if ID is valid
	// check if user has access
	try {
		if ($editor->checkListId($_GET['listid'])) {
			// set session variable and redirect to clean self
			$_SESSION['listId'] = $_GET['listid'];
			header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor');
		}
		else {
			// else else redirect to dash with error
			header('location: ' . $openerTp->getUrlReturn() . 'User/Dashboard/index.php?error=' . $editor->getEMessage());
		}
	} catch (StmtFailedException $e) {
		header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor/index.php?error=' . $e->getMessage());
		exit();
	}
}
// else na

// check if post request
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// check post type
	if (htmlspecialchars($_POST['postType'], ENT_QUOTES, 'UTF-8') == 'listData') {
		// Grabbing data
		$legoListVals = array(
			'listName'=> htmlspecialchars($_POST['listName'], ENT_QUOTES, 'UTF-8'),
			'isPublic'=> htmlspecialchars($_POST['isPublic'], ENT_QUOTES, 'UTF-8'),
			'uid'=> htmlspecialchars($_POST['uid'], ENT_QUOTES, 'UTF-8'),
			'listId'=> htmlspecialchars($_POST['listId'], ENT_QUOTES, 'UTF-8')
		);

		// Running error handlers and updating list
		try {
			$editor->updateLegoList($legoListVals);
		} catch (InvalidInputException | StmtFailedException $e) {
			header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor/index.php?error=' . $e->getMessage());
			exit();
		}

		// Send user back with clean page
		header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor');
	}
	elseif (htmlspecialchars($_POST['postType'], ENT_QUOTES, 'UTF-8') == 'addLego') {
		// Grabbing data
		$addLegoVals = array(
			'legoId'=> htmlspecialchars($_POST['legoId'], ENT_QUOTES, 'UTF-8'),
			'listId'=> htmlspecialchars($_POST['listId'], ENT_QUOTES, 'UTF-8')
		);

		// Running error handlers and updating list
		try {
			$editor->addLegoToLegoList($addLegoVals);
		} catch (InvalidInputException | StmtFailedException $e) {
			header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor/index.php?error=' . $e->getMessage());
			exit();
		}

		// Send user back with clean page
		header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor');
	}
	elseif (htmlspecialchars($_POST['postType'], ENT_QUOTES, 'UTF-8') == 'removeLego') {
		// Grabbing data
		$removeLegoVals = array(
			'legoId'=> htmlspecialchars($_POST['legoId'], ENT_QUOTES, 'UTF-8'),
			'listId'=> htmlspecialchars($_POST['listId'], ENT_QUOTES, 'UTF-8')
		);

		// Running error handlers and updating list
		try {
			$editor->removeLegoFromLegoList($removeLegoVals);
		} catch (InvalidInputException | StmtFailedException $e) {
			header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor/index.php?error=' . $e->getMessage());
			exit();
		}

		// Send user back with clean page
		header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor');
	}
	else {
		// else redirect back with error
		header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor/index.php?error=invalidposttype');
	}
}
// else na

// check if session variable is set
if (isset($_SESSION['listId'])) {
	// pull data
	try {
		$editor->getListData();
	} catch (StmtFailedException $e) {
		header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor/index.php?error=' . $e->getMessage());
		exit();
	}
}
else {
	// else redirect to dash with error
	header('location: ' . $openerTp->getUrlReturn() . 'User/Dashboard/index.php?error=nolistset');
}

 ?>


 <!DOCTYPE html>
 <html>

 	<?php $headerTp = new HeaderTp();
	$headerTp->echoHeader($openerTp->getUrlReturn(), $urlTitle) ?>


	<?php $editor->viewListDataForm(); ?>
	<br>


	<?php $editor->viewLegosInList(); ?>
	<br>


	<?php $editor->viewAddLegoToListForm(); ?>
	<br>

	<?php $editor->viewLegoDB(); ?>
	<br>


	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>