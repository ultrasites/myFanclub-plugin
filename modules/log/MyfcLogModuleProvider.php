<?php

function myfc_provide_log()
{
    $request = $_REQUEST;
    $router = myfanclub\modules\log\MyfcLogModuleRouter::getInstance();
    $router->route($request);
}
