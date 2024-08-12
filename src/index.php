<?php 

declare(strict_types = 1);
namespace Src;
require __DIR__ . '\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->startSession();
$openerTp->setUrlReturn(0);
$urlTitle = 'Home';

 ?>


 <!DOCTYPE html>
 <html>

 	<?php $headerTp = new HeaderTp();
	$headerTp->echoHeader($openerTp->getUrlReturn(), $urlTitle) ?>

	<!--start-->

	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>