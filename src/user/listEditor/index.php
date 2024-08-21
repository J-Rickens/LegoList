<?php 

declare(strict_types = 1);
namespace Src\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;
use Src\User\ListEditor\ListEditorContrClass;

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
if (isset($_GET['list_id'])) {
	// check if ID is valid
	// check if user has access
	if ($editor->checkListId($_GET['list_id'])) {
		// set session variable and redirect to clean self
		$_SESSION['list_id'] = $_GET['list_id'];
		header('location: ' . $openerTp->getUrlReturn() . 'User/ListEditor');
	}
	else {
		// else else redirect to dash with error
		header('location: ' . $openerTp->getUrlReturn() . 'User/Dashboard/index.php?error=6');
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
			'pubPri'=> htmlspecialchars($_POST['pubPri'], ENT_QUOTES, 'UTF-8'),
			'list_id'=> htmlspecialchars($_POST['list_id'], ENT_QUOTES, 'UTF-8')
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
			'legoId'=> htmlspecialchars($_POST['legoID'], ENT_QUOTES, 'UTF-8'),
			'list_id'=> htmlspecialchars($_POST['list_id'], ENT_QUOTES, 'UTF-8')
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
			'legoId'=> htmlspecialchars($_POST['legoID'], ENT_QUOTES, 'UTF-8'),
			'list_id'=> htmlspecialchars($_POST['list_id'], ENT_QUOTES, 'UTF-8')
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
if (isset($_SESSION['list_id'])) {
	// pull data
	$editor->getListData();
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


	<?php if (isset($_GET['legoId'])) {
		$editor->viewAddLegoToListForm($_GET['legoId']);
	}
	else {
		$editor->viewAddLegoToListForm();
	} ?>
	<br>


	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>