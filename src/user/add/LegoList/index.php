<?php 
 
declare(strict_types = 1);
namespace Src\User\Add\LegoList;
require __DIR__ . '\\..\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;
use Src\Shared\Regex\LegoListRegex;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(3);
$urlTitle = 'AddList';

// Redurect user if not loged in
if ($openerTp->startSession()) {
	header('location: '. $openerTp->getUrlReturn() .'Login');
}

 ?>


 <!DOCTYPE html>
 <html>

 	<?php $headerTp = new HeaderTp();
	$headerTp->echoHeader($openerTp->getUrlReturn(), $urlTitle) ?>

	<section>
		<div>
			<h4>Create List</h4>
			<p>Create a New List of Lego Sets Here!</p>
			<form action="<?php echo 'RegLegoListInc.php'; ?>" method="post">
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

	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>