<?php 

declare(strict_types = 1);
namespace Src\User\ListEditor;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Regex\LegoRegex;
use Src\Shared\Regex\LegoListRegex;

class ListEditorViewClass {

	public function echoListDataForm($legoListVals): void {
		?>
		<section>
		<div>
			<h4>Edit List</h4>
			<p>Update Your List of Lego Sets Here!</p>
			<form action="<?php echo 'index.php'; ?>" method="post">
				<?php // inputs for required feilds: List Name, public, list_id ?>
				<input type="text" name="listName" placeholder="List Name"
					pattern="<?php echo LegoListRegex::NAME; ?>" title="<?php echo LegoListRegex::NAMEDESCR; ?>"
					value="<?php echo $legoListVals['listName']; ?>" required>
				<input type="radio" name="isPublic" id="public" value="public"
				<?php if ($legoListVals['isPublic']==1) {echo 'checked';}?>>
				<label for="public">Public</label>
				<input type="radio" name="isPublic" id="private" value="private"
				<?php if ($legoListVals['isPublic']==0) {echo 'checked';}?>>
				<label for="private">Private</label>
				<?php //echo 'Error: isPublic not defined'; ?>
				<input type="hidden" name="uid"
					value="<?php echo $_SESSION['uid']; ?>">
				<input type="hidden" name="listId"
					value="<?php echo $_SESSION['listId']; ?>">
				<input type="hidden" name="postType"
					value="listData">

				<?php // inputs for non-required feilds: None ?>
				<br>
				<button type="submit" name="submit">UPDATE LIST</button>
			</form>
		</div>
		</section>
		<?php
	}

	public function echoAddLegoToListForm($legoId): void {
		?>
		<section>
		<div>
			<h4>Add Lego to List</h4>
			<p>Update Your List of Lego Sets Here!</p>
			<form action="<?php echo 'index.php'; ?>" method="post">
				<?php // inputs for required feilds: lego_id, list_id ?>
				<input type="text" name="legoId" placeholder="Lego ID #" 
					pattern="<?php echo LegoRegex::LEGOID; ?>" title="<?php echo LegoRegex::LEGOIDDESCR; ?>"
					value="<?php echo $legoId; ?>" required>
				<input type="hidden" name="listId"
					value="<?php echo $_SESSION['listId']; ?>">
				<input type="hidden" name="postType"
					value="addLego">

				<?php // inputs for non-required feilds: None ?>
				<br>
				<button type="submit" name="submit">UPDATE LIST</button>
			</form>
		</div>
		</section>
		<?php
	}

	public function echoLegosInList(array $legoListLegos): void {
		?>
		<section>
		<div>
			<h4>Legos in List</h4>
			<?php if (empty($legoListLegos)): ?>
				<p>There are No Legos in this List!</p>
			<?php else: ?>
				<ul>
					<?php foreach ($legoListLegos as $lego): ?>
						<li><?php var_dump($lego); ?>
							<form action="<?php echo 'index.php'; ?>" method="post">
								<input type="hidden" name="legoId"
									value="<?php echo $lego['lego_id']; ?>">
								<input type="hidden" name="listId"
									value="<?php echo $_SESSION['listId']; ?>">
								<input type="hidden" name="postType"
									value="removeLego">
								<button type="submit" name="submit">REMOVE LEGO</button>
							</form>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
			
		</div>
		</section>
		<?php
	}
}