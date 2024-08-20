<?php 

declare(strict_types = 1);
namespace Src\User\Dashboard;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;
use Src\User\Dashboard\DashboardContrClass;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->setUrlReturn(2);
$urlTitle = 'Dashboard';

// Redurect user if not loged in
if ($openerTp->startSession()) {
	header('location: '. $openerTp->getUrlReturn() .'Login');
}

$dashContr = new DashboardContrClass();

 ?>


 <!DOCTYPE html>
 <html>

 	<?php $headerTp = new HeaderTp();
	$headerTp->echoHeader($openerTp->getUrlReturn(), $urlTitle) ?>

	<h1>Dashboard</h1>

	<?php $dashContr->createLegoListSec(); ?>

	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>