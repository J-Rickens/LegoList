<?php 

declare(strict_types = 1);
namespace Src;
require __DIR__ . '\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;
use Src\HomeContrClass;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->startSession();
$openerTp->setUrlReturn(0);
$urlTitle = 'Home';

$homeContr = new HomeContrClass();

 ?>


 <!DOCTYPE html>
 <html>

 	<?php $headerTp = new HeaderTp();
	$headerTp->echoHeader($openerTp->getUrlReturn(), $urlTitle) ?>

	<?php $homeContr->viewLegoDB(); ?>
	<br>

	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>