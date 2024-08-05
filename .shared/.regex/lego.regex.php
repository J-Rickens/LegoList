<?php 

class LegoRegex {
	public const LEGOID = "^\d{3,8}$";
	public const PEICES = "^\d{1,10}$";
	public const NAME = "^[a-zA-Z0-9 -]{3,40}$";
	public const COLLECTION = "^[a-zA-Z0-9 ]{3,20}$";
	public const COST = "^(\\$)?(\d{1,6}|\d{1,3},\d{3})(\.\d{2})?$";

	public const LEGOIDDESCR = "Must be 3-8 Numbers! No Spaces!";
	public const PEICESDESCR = "Must be 1-10 Numbers! No Spaces!";
	public const NAMEDESCR = "Must be 3-40 Letters!";
	public const COLLECTIONDESCR = "Must be 3-20 Letters, Numbers or Spaces!";
	public const COSTDESCR = "Must be 1-6 Digits! No Spaces! May Add Change (e.g. '10.99','$1000','$100,056.50',etc.).";
}
