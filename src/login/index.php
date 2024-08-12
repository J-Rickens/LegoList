<?php 

declare(strict_types = 1);
namespace Src\Login;
require __DIR__ . '\\..\\..\\vendor\\autoload.php';

use Src\Shared\Tp\OpenerTp;
use Src\Shared\Tp\HeaderTp;
use Src\Shared\Tp\FooterTp;
use Src\Shared\Regex\LoginRegex;

global $openerTp;
$openerTp = new OpenerTp();
$openerTp->startSession();
$openerTp->setUrlReturn(1);
$urlTitle = 'Login/Register';

 ?>


 <!DOCTYPE html>
 <html>

 	<?php $headerTp = new HeaderTp();
	$headerTp->echoHeader($openerTp->getUrlReturn(), $urlTitle) ?>

	<section>
		<div>
			<h4>Login</h4>
			<p>Existing Builders Login Here!</p>
			<form action="<?php echo 'LoginInc.php'; ?>" method="post">
				<?php // inputs for required feilds: username, password ?>
				<input type="text" name="usna" placeholder="Username"
					required>
				<input type="password" name="pwd" placeholder="Password"
					required>
				<br>
				<button type="submit" name="submit">LOGIN</button>
			</form>
		</div>

		<div>
			<h4>Register</h4>
			<p>Don't have an account yet? Sign up here!</p>
			<form action="<?php echo 'RegisterInc.php'; ?>" method="post">
				<?php // inputs for required feilds: name, email, username, password ?>
				<input type="text" name="name" placeholder="Name"
					pattern="<?php echo LoginRegex::NAME; ?>" title="<?php echo LoginRegex::NAMEDESCR; ?>"
					required>
				<input type="email" name="email" placeholder="Email Address"
					required>
				<input type="text" name="usna" placeholder="Username"
					pattern="<?php echo LoginRegex::USNA; ?>" title="<?php echo LoginRegex::USNADESCR; ?>"
					required>
				<input type="password" name="pwd" placeholder="Password"
					pattern="<?php echo LoginRegex::PWD; ?>" title="<?php echo LoginRegex::PWDDESCR; ?>"
					required>
				<input type="password" name="pwd2" placeholder="Repeat Password"
					required>
				<br>
				<button type="submit" name="submit">SIGN UP</button>
			</form>
		</div>
	</section>

	<?php $footerTp = new FooterTp();
	$footerTp->echoFooter($openerTp->getUrlReturn()); ?>

 </html>