<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href=<?php echo $urlReturn . ".shared/.css/legoMainStyles.css"; ?>>
	<title>LegoList <?php echo $urlTitle; ?></title>

</head>

<body>

	<header>
		<nav>
			<div>
				<a href=<?php echo $urlReturn; ?>>logo</a>
				<ul>
					<?php if (isset($_SESSION['uid'])) { ?>
						<li><a href=<?php echo $urlReturn . "user/dashboard"; ?>><?php echo $_SESSION['name']; ?></a></li>
						<li><a href=<?php echo $urlReturn . "user/add/lego"; ?>>Add Lego</a></li>
						<li><a href=<?php echo $urlReturn . "user/add/list"; ?>>Add List</a></li>
						<li><a href=<?php echo $urlReturn . ".shared/.includes/logout.inc.php"; ?>>LOGOUT</a></li>
					<?php } else { ?>
						<li><a href=<?php echo $urlReturn . "login"; ?>>login/register</a></li>
					<?php } ?>
				</ul>
			</div>
		</nav>
	</header>