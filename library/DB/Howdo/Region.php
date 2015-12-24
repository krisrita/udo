<?php

class DB_Howdo_Region extends DB_Howdo
{
	protected $tbl_name = "hd_region";
	protected $pri_key = "region_id";

	public function __construct()
	{
		parent::__construct();
	}
}
