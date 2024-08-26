<?php 

declare(strict_types = 1);
namespace Src\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Exceptions\StmtFailedException;
use Src\User\Add\Lego\LegoClass;
use Src\User\ListEditor\ListEditorClass;
use Src\User\ListEditor\ListEditorViewClass;

class ListEditorContrClass {

	private $listEditorClass;
	private $listEditorViewClass;
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

	public function __construct($legoClass = null, $listEditorClass = null, $listEditorViewClass = null) {
		if (is_null($legoClass)) {
			$this->legoClass = new LegoClass();
		}
		else {
			$this->legoClass = $legoClass;
		}

		if (is_null($listEditorClass)) {
			$this->listEditorClass = new ListEditorClass();
		}
		else {
			$this->listEditorClass = $listEditorClass;
		}

		if (is_null($listEditorClass)) {
			$this->listEditorViewClass = new ListEditorViewClass();
		}
		else {
			$this->listEditorViewClass = $listEditorViewClass;
		}
	}



	// initialization methods
	public function checkListId($listId): bool {
		$checkResults = $this->listEditorClass->checkLegoList($listId, $_SESSION['uid']);
		$this->eMessage = $checkResults[1];
		return $checkResults[0];
	}

	public function getEMessage(): string {
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

	}

	public function addLegoToLegoList(array $addLegoVals): void {
		$this->listEditorClass->setLegoToList($addLegoVals);
	}

	public function removeLegoFromLegoList(array $removeLegoVals): void {
		$this->listEditorClass->deleteLegoFromList($removeLegoVals);
	}

	// post data validation methods



	// view methods
	public function viewListDataForm(): void {
		$this->listEditorViewClass->echoListDataForm($this->legoListVals);
	}

	public function viewAddLegoToListForm($legoId = null): void {
		$this->listEditorViewClass->echoAddLegoToListForm($legoId);
	}

	public function viewLegosInList(): void {
		$this->listEditorViewClass->echoLegosInList($this->legoListLegos);
	}
}