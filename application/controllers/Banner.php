<?php
/**
 * Created by PhpStorm.
 * User: Kris
 * Date: 2015/11/5
 * Time: 15:43
 */


class BannerController extends Base_Contr
{

    function bannerEditAction()
    {
    }

    function formAction(){

    }
    function getImageHtmlAction(){
        $imageUrl = $this->get('image');
        $htmlStr =
<<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <style type="text/css">
    * {
        margin: 0;
        padding: 0;
    }
    .bg {
        position: relative;
    }
    .bg-img {
        width: 100%;
    }
    </style>
    <body>
    	<div class="bg">
    		<img src="{$imageUrl}" class="bg-img"/>
    	</div>

    </body>
</html>
HTML;
        echo $htmlStr;exit;
    }
}