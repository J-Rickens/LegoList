<?php 

declare(strict_types = 1);
namespace Src\Dashboard;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(2);
$urlTitle = 'Dashboard';

// Redurect user if not loged in
if ($openerTp->startSession()) {
	header('location: '. $openerTp->getUrlReturn() .'Login');
}

 ?>


 <!DOCTYPE html>
 <html>

 	<?php $headerTp = new HeaderTp();
	$headerTp->echoHeader($openerTp->getUrlReturn(), $urlTitle) ?>

	<!--start--><p>Dashboard</p>

	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>