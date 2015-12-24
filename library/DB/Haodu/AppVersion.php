<?php

class DB_Haodu_AppVersion extends DB_Haodu
{
    protected $tbl_name = "xdf_app_version";
	protected $pri_key = "id";

	public function __construct()
	{
		parent::__construct();
	}
}
