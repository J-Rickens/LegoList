<?php 

declare(strict_types = 1);
namespace Src\Shared\Tp;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

class FooterTp
{
	public function echoFooter(): void
	{
		 ?>
			<footer>
				<div>Copyright 2024 jrickens LegoList</div>
			</footer>

		</body>
		<?php 
	}
}