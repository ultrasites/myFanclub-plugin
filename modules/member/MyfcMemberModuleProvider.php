<?php

function myfc_provide_member()
{
    $request = $_REQUEST;
    $router = myfanclub\modules\member\MyfcMemberModuleRouter::getInstance();
    $router->route($request);
}
