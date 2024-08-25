<?php 

declare(strict_types = 1);
namespace Src\User\Dashboard;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

class DashboardViewClass {

	public function echoLegoLists(array $legoLists): void {
		global $openerTp;

		?>
		<section>
			<h3>Your LegoLists:</h3>
			<ul>
				<?php foreach ($legoLists as $legoList): ?>
					<li><a href="<?php echo '../ListEditor/index.php?list_id=' . $legoList['list_id']; ?>">
						(<?php if ($legoList['public']==1) {
							echo 'Public';
						} else {
							echo 'Private';
						} ?>) <?php echo $legoList['list_name']; ?>
					</a></li>
				<?php endforeach; ?>
			</ul>
		</section>
		<?php
	}
}