<?php

/**
 * Class DB_Haodu_ItemType
 */
class DB_Haodu_ItemType extends DB_Haodu
{
	protected $tbl_name = "xdf_item_type";
	protected $pri_key = "type_id";

	public function __construct()
	{
		parent::__construct();
	}
}
