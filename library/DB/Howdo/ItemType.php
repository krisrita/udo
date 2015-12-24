<?php

class DB_Howdo_ItemType extends DB_Howdo
{
	protected $tbl_name = "hd_item_type";
	protected $pri_key = "type_id";

	public function __construct()
	{
		parent::__construct();
	}
}
