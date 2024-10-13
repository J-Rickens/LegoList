<?php 

declare(strict_types = 1);
namespace Src;
require __DIR__ . '\\..\\vendor\\autoload.php';

class HomeViewClass {

	public function echoLegoDB(array $legos): void {
		?>
		<section>
		<div>
			<h4>Legos in Database</h4>
			<?php if (empty($legos)): ?>
				<p>There are No Legos in the Database!</p>
			<?php else: ?>
				<table>
					<tr>
						<td>Lego ID</td>
						<td>Name</td>
						<td>Collection</td>
						<td>Pieces</td>
						<td>Cost</td>
					</tr>
					<?php foreach ($legos as $lego): ?>
						<tr>
							<td><?php echo $lego['lego_id']; ?></td>
							<td><?php echo $lego['lego_name']; ?></td>
							<td><?php echo $lego['lego_collection']; ?></td>
							<td><?php echo $lego['piece_count']; ?></td>
							<td><?php echo $lego['lego_cost']; ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endif; ?>
		</div>
		</section>
		<?php
	}
}