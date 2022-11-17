<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH . "third_party/MX/Loader.php";
require __DIR__ . "/MY_Trait.php";

class MY_Loader extends MX_Loader
{

	public function __construct()
	{
		parent::__construct();
	}

	use MY_Trait;

}
