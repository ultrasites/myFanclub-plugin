<?php
use myfanclub\helper\MyfcFormat;

use PHPUnit\Framework\TestCase;

final class MyfcFormatTest extends TestCase
{
    public function testDateInEuropeanFormat()
    {
        $date = MyfcFormat::myfcFormatDateToEuropean('2018-01-02');
        $this->assertEquals('02.01.2018', $date);
    }

    public function testDateToMySQLDate()
    {
        $date = MyfcFormat::myfcFormatDateToMySQLDate('02.01.2018');
        $this->assertEquals('2018-01-02', $date);
    }

    public function testDateInYears()
    {
        $dateTimeMock = Mockery::mock('DateTime')->makePartial();

        $inputDate = new \DateTime("2016-01-01");
        $todayDate = new \DateTime("2019-01-01");

        $dateTimeMock->shouldReceive("__construct")->times(2)->andReturn($inputDate, $todayDate);

        $years = MyfcFormat::myfcDateInYears("2016-01-01");

        $this->assertEquals(3, $years);
    }

    /**
     * @dataProvider displayNameProvider
     */
    public function testSplitDisplayName($input, $expected)
    {
        $splittedName = MyfcFormat::myfcSplitDisplayName($input);

        $this->assertEquals($expected, $splittedName);
    }


    public function displayNameProvider()
    {
        return [
          ["Max Mustermann", [
              "forename"=>"Max",
              "lastname"=>"Mustermann"
          ]],
          ["Max Theo Mustermann", [
              "forename"=>"Max",
              "lastname"=>"Theo Mustermann"
          ]]
        ];
    }
}
