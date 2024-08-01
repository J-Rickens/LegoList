<?php 

class LoginRegex {
	public const NAME = "^[a-zA-Z]{3,20}$";
	//public const EMAIL = ""; Using built in functions
	public const USNA = "^[a-zA-Z0-9_]{3,20}$";
	public const PWD = "^[a-zA-Z0-9]{8,32}$";

	public const NAMEDESCR = "Must be 3-20 Letters! No Spaces!";
	//public const EMAILDESCR = ""; Using built in functions
	public const USNADESCR = "Must be 3-20 Letters, Numbers or '_'! No Spaces!";
	public const PWDDESCR = "^Must be 8-32 Letters or Numbers! No Spaces!";
}
