<?php

use myfanclub\helper\MyfcSecure;

use PHPUnit\Framework\TestCase;

final class MyfcSecureTest extends TestCase
{
    public function testSecure()
    {
        $password = "Test";

        $myfcSecure = new MyfcSecure();

        $secure = $myfcSecure->myfcEncrypt($password);

        $this->assertEquals($password, $myfcSecure->myfcDecrypt($secure));
    }
}
