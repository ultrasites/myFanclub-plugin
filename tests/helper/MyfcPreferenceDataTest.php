<?php

use myfanclub\helper\MyfcPreferenceData;

use PHPUnit\Framework\TestCase;

final class MyfcPreferenceDataTest extends TestCase
{
    /**
     * @dataProvider solidPaymentDataProvider
     */
    public function testIsSolidPayment($expected, $input)
    {
        $isSolid = MyfcPreferenceData::myfcIsSolidPayment(json_decode(json_encode($input)));
        $this->assertEquals($expected, $isSolid);
    }

    /**
     * @dataProvider paymentSpecialsDataProvider
     */
    public function testGetPaymentSpecials($expected, $input)
    {
        $paymentSpecials = MyfcPreferenceData::myfcGetPaymentSpecials(json_decode(json_encode($input)));

        if (is_array($paymentSpecials)) {
            $this->assertEquals($expected, count($paymentSpecials));
        } else {
            $this->assertEquals($expected, $paymentSpecials);
        }
    }

    /**
     * @dataProvider calculateAmountDataProvider
     */
    public function testCalculateAmount($expected, $preferencesValue, $special, $age)
    {
        $calculatedAmount = MyfcPreferenceData::myfcCalculateAmount(json_decode(json_encode($preferencesValue)), $special, $age);

        $this->assertEquals($expected, $calculatedAmount);
    }


    public function solidPaymentDataProvider()
    {
        return [
            [true, [
                [
                    "solid" => true
                ]
            ]],
            [false, [
                [
                    "solid" => false
                ]
            ]],
            [false, []]
        ];
    }

    public function calculateAmountDataProvider()
    {
        return [
            [
                "1,00",
                [
                    [
                            "solid" => true,
                            "amount" => "1,00"
                    ]
                ],
                false,
                20
            ],
            [
                "1,10",
                [
                    [
                        "amount" => "1,00",
                        "description" => "child2"
                    ],
                    [
                        "amount" => "1,10",
                        "description" => "child"
                    ]
                ],
                "child",
                ""
            ],
            [
                "1,00",
                [
                    [
                        "amount" => "15,00",
                        "age" => "20"
                    ],
                    [
                        "amount" => "0,10",
                        "age" => "3"
                    ],
                    [
                        "amount" => "1,00",
                        "age" => "15"
                    ]
                ],
                "",
                "10"
            ]

        ];
    }

    public function paymentSpecialsDataProvider()
    {
        return [
            ['', [
                [
                    "solid" => true
                ]
            ]],
            [2, [
                [
                    "description" => "Test"
                ],
                [
                    "description" => "Test"
                ]
            ]],
            [0, []]
        ];
    }
}
