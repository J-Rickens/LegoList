<?php 

declare(strict_types = 1);
namespace Src\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Exceptions\InvalidInputException;
use Src\User\ListEditor\ListEditorClass;
use Src\User\ListEditor\ListEditorViewClass;
use Src\User\Add\Lego\LegoClass;
use Src\User\Add\LegoList\LegoListContrClass;
use Src\Shared\Classes\LegoClass as LegoClassS;

class ListEditorContrClass extends LegoListContrClass {

	private $listEditorClass;
	private $listEditorViewClass;
	private $legoClass;
	private $legoClassS;

	private ?string $eMessage = null;
	private array $legoListVals = array(
		'listId'=>null,
		'listName'=>null,
		'isPublic'=>null,
		'uid'=>null,
		'dateCreated'=>null,
		'dateModified'=>null
	);
	private array $legoListLegos = array();

	public function __construct($legoClass = null, $legoClassS = null, $listEditorClass = null, $listEditorViewClass = null) {
		if (is_null($legoClass)) {
			$this->legoClass = new LegoClass();
		}
		else {
			$this->legoClass = $legoClass;
		}

		if (is_null($legoClassS)) {
			$this->legoClassS = new LegoClassS();
		}
		else {
			$this->legoClassS = $legoClassS;
		}

		if (is_null($listEditorClass)) {
			$this->listEditorClass = new ListEditorClass();
		}
		else {
			$this->listEditorClass = $listEditorClass;
		}

		if (is_null($listEditorViewClass)) {
			$this->listEditorViewClass = new ListEditorViewClass();
		}
		else {
			$this->listEditorViewClass = $listEditorViewClass;
		}
	}

	public function setLegoListVals(array $legoListVals): void
	{
		foreach ($legoListVals as $key => $value) {
			$this->legoListVals[$key] = $value;
		}
	}

	public function getLegoListVals(bool $getFull = true, array $valNames = array()): array
	{
		if ($getFull) {
			return $this->legoListVals;
		}
		else {
			$returnArray = array();
			foreach ($valNames as $key) {
				$returnArray[$key] = $this->legoListVals[$key];
			}
			return $returnArray;
		}
	}

	public function getLegoListLegos(): array
	{
		return $this->legoListLegos;
	}



	// initialization methods
	public function checkListId($listId): bool {
		$checkResults = $this->listEditorClass->checkLegoList($listId, $_SESSION['uid']);
		$this->eMessage = $checkResults[1];
		return $checkResults[0];
	}

	public function getEMessage(): ?string {
		return $this->eMessage;
	}

	public function getListData(): void {
		$listData = $this->listEditorClass->getLegoListData($_SESSION['listId']);

		$this->legoListVals['listId'] = $listData[0]['list_id'];
		$this->legoListVals['listName'] = $listData[0]['list_name'];
		$this->legoListVals['isPublic'] = $listData[0]['is_public'];
		$this->legoListVals['uid'] = $listData[0]['owner_id'];
		$this->legoListVals['dateCreated'] = $listData[0]['date_created'];
		$this->legoListVals['dateModified'] = $listData[0]['date_modified'];

		$this->legoListLegos = $this->listEditorClass->getLegoListLegos($_SESSION['listId']);
	}



	// post data manipulater methods
	public function updateLegoList(array $legoListVals): void {
		// run error checks
		// check if listId is valid and if current user has permision to edit
		if (!$this->checkListId($legoListVals['listId'])) {
			// echo 'Invalid ListId';
			throw new InvalidInputException('listid: ' . $this->getEMessage());
		}

		// create new array with reformated data
		$validLegoListVals = $this->parentChecks($legoListVals);
		$validLegoListVals['listId'] = $legoListVals['listId'];

		// update the list
		$this->listEditorClass->updateLegoListData($validLegoListVals);
	}

	public function addLegoToLegoList(array $addLegoVals): void {
		// run error checks
		// check if listId is valid and if current user has permision to edit
		if (!$this->checkListId($addLegoVals['listId'])) {
			// echo 'Invalid ListId';
			throw new InvalidInputException('listid: ' . $this->getEMessage());
		}

		// check if lego exists
		if (!$this->legoClass->checkLegoExist($addLegoVals['legoId'])) {
			// echo 'Lego does not Exists';
			throw new InvalidInputException('legonotexists');
		}

		// check if lego is not in list
		if ($this->listEditorClass->checkLegoInLegoList($addLegoVals['listId'], $addLegoVals['legoId'])) {
			// echo 'Lego already in list';
			throw new InvalidInputException('legoinlist');
		}

		$this->listEditorClass->setLegoToList($addLegoVals);
	}

	public function removeLegoFromLegoList(array $removeLegoVals): void {
		// run error checks
		// check if listId is valid and if current user has permision to edit
		if (!$this->checkListId($removeLegoVals['listId'])) {
			// echo 'Invalid ListId';
			throw new InvalidInputException('listid: ' . $this->getEMessage());
		}

		// check if lego exists
		if (!$this->legoClass->checkLegoExist($removeLegoVals['legoId'])) {
			// echo 'Lego does not Exists';
			throw new InvalidInputException('legonotexists');
		}

		// check if lego is in list
		if (!$this->listEditorClass->checkLegoInLegoList($removeLegoVals['listId'], $removeLegoVals['legoId'])) {
			// echo 'Lego not in list';
			throw new InvalidInputException('legonotinlist');
		}

		$this->listEditorClass->deleteLegoFromList($removeLegoVals);
	}

	// check methods
	protected function parentChecks(array $legoListVals): array
	{
		// use existing checks in LegoListContrClass to validate remaining data
		parent::setLegoListVals(array(
			'listName'=>$legoListVals['listName'],
			'isPublic'=>$legoListVals['isPublic'],
			'uid'=>$legoListVals['uid']
		));
		parent::legoListErrorChecks();

		return parent::getLegoListVals();
	}



	// view methods
	public function viewListDataForm(): void {
		$this->listEditorViewClass->echoListDataForm($this->legoListVals);
	}

	public function viewAddLegoToListForm(): void {
		$this->listEditorViewClass->echoAddLegoToListForm();
	}

	public function viewLegosInList(): void {
		$this->listEditorViewClass->echoLegosInList($this->legoListLegos);
	}

	public function viewLegoDB(): void {
		$legos = $this->legoClassS->getLegos();
		$this->listEditorViewClass->echoLegoDB($legos);
	}
}