 <?php
/**
 * 数据源
 */
class DatasourceController extends Base_Contr
{

    public $needLogin = false;

    function regionAction()
    {
        $datasourceModel = new DatasourceModel();
        $region = $datasourceModel -> getRegion();
        $this->displayJson(Common_Error::ERROR_SUCCESS, $region);
    }

}