<?php 

	$urlLvl = 3;
	$urlTitle = "AddList";
	include('../../../.shared/.templates/opener.tp.php');

	// Redurect user if not loged in
	if (!isset($_SESSION['uid'])) {
		header("location: ". $urlReturn ."login");
	}

	// import regex patterns
	include($urlReturn . ".shared/.regex/legoList.regex.php");

 ?>


 <!DOCTYPE html>
 <html>

 	<?php include($urlReturn . '.shared/.templates/header.tp.php'); ?>

	<section>
		<div>
			<h4>Create List</h4>
			<p>Create a New List of Lego Sets Here!</p>
			<form action="<?php echo "regLegoList.inc.php"; ?>" method="post">
				<?php // inputs for required feilds: List Name, public, uid ?>
				<input type="text" name="listName" placeholder="List Name"
					pattern="<?php echo LegoListRegex::NAME; ?>" title="<?php echo LegoListRegex::NAMEDESCR; ?>"
					required>
				<input type="radio" name="pubPri" id="public" value="public" checked>
				<label for="public">Public</label>
				<input type="radio" name="pubPri" id="private" value="private">
				<label for="private">Private</label>
				<input type="hidden" name="uid"
					value="<?php echo $_SESSION['uid']; ?>">

				<?php // inputs for non-required feilds: None ?>
				<br>
				<button type="submit" name="submit">CREATE LIST</button>
			</form>
		</div>
	</section>

	<?php include($urlReturn . '.shared/.templates/footer.tp.php'); ?>

 </html>