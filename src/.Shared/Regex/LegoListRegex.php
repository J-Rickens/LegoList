<?php 

declare(strict_types = 1);
namespace Src\Shared\Regex;
require __DIR__ . '\\..\\..\\..\\vendor\\autoload.php';

class LegoListRegex {
	public const NAME = "^[a-zA-Z0-9 -]{3,40}$";

	public const NAMEDESCR = "Must be 3-40 Letters!";
}
