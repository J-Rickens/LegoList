<?php 

	$urlLvl = 3;
	$urlTitle = "AddLego";
	include('../../../.shared/.templates/opener.tp.php');

	// Redurect user if not loged in
	if (!isset($_SESSION['uid'])) {
		header("location: ". $urlReturn ."login");
	}

	// import regex patterns
	include($urlReturn . ".shared/.regex/lego.regex.php");

 ?>


 <!DOCTYPE html>
 <html>

 	<?php include($urlReturn . '.shared/.templates/header.tp.php'); ?>

	<section>
		<div>
			<h4>Register Lego</h4>
			<p>Be the First to Register a New Lego Set Here!</p>
			<form action="<?php echo "regLego.inc.php"; ?>" method="post">
				<?php // inputs for required feilds: legoID, pieceCount, uid ?>
				<input type="text" name="legoID" placeholder="Lego ID #" 
					pattern="<?php echo LegoRegex::LEGOID; ?>" title="<?php echo LegoRegex::LEGOIDDESCR; ?>"
					value="<?php if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['legoID'])) {
						echo htmlspecialchars($_GET['legoID'], ENT_QUOTES, 'UTF-8');
					} ?>" required>
				<input type="text" name="pieceCount" placeholder="Number of Peices"
					pattern="<?php echo LegoRegex::PEICES; ?>" title="<?php echo LegoRegex::PEICESDESCR; ?>"
					required>
				<input type="hidden" name="uid"
					value="<?php echo $_SESSION['uid']; ?>">

				<?php // inputs for non-required feilds: Name, Collection, Cost ?>
				<input type="text" name="legoName" placeholder="Lego Name"
					pattern="<?php echo LegoRegex::NAME; ?>" title="<?php echo LegoRegex::NAMEDESCR; ?>">
				<input type="text" name="collection" placeholder="Collection"
					pattern="<?php echo LegoRegex::COLLECTION; ?>" title="<?php echo LegoRegex::COLLECTIONDESCR; ?>">
				<input type="text" name="cost" placeholder="Cost in USD"
					pattern="<?php echo LegoRegex::COST; ?>" title="<?php echo LegoRegex::COSTDESCR; ?>">
				<br>
				<button type="submit" name="submit">REGISTER LEGO</button>
			</form>
		</div>
	</section>

	<?php include($urlReturn . '.shared/.templates/footer.tp.php'); ?>

 </html>