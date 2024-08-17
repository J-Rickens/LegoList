<?php 

declare(strict_types = 1);
namespace Src\Shared\Tp;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

class HeaderTp
{
	function echoHeader($urlReturn, $urlTitle): void
	{
		 ?>
		<head>

			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="stylesheet" type="text/css" href=<?php echo $urlReturn . ".Shared/CSS/LegoMainStyles.css"; ?>>
			<title>LegoList <?php echo $urlTitle; ?></title>

		</head>

		<body>

			<header>
				<nav>
					<div>
						<a href=<?php echo $urlReturn; ?>>logo</a>
						<ul>
							<?php if (isset($_SESSION['uid'])) { ?>
								<li><a href=<?php echo $urlReturn . "User/Dashboard"; ?>><?php echo $_SESSION['name']; ?></a></li>
								<li><a href=<?php echo $urlReturn . "User/Add/Lego"; ?>>Add Lego</a></li>
								<li><a href=<?php echo $urlReturn . "User/Add/LegoList"; ?>>Add List</a></li>
								<li><a href=<?php echo $urlReturn . ".Shared/Includes/LogoutInc.php"; ?>>LOGOUT</a></li>
							<?php } else { ?>
								<li><a href=<?php echo $urlReturn . "Login"; ?>>login/register</a></li>
							<?php } ?>
						</ul>
					</div>
				</nav>
			</header>
		<?php 
	}
}