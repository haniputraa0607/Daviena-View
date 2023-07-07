<?php

return [
    'midtrans_cc'    => [
        'payment_gateway' => 'Midtrans',
        'payment_method'  => 'credit_card',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_creditcard.png',
        'text'            => 'Credit Card',
        'redirect'        => false
    ],
    'midtrans_bank_transfer'    => [
        'payment_gateway' => 'Midtrans',
        'payment_method'  => 'bank_transfer',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_banktransfer.png',
        'text'            => 'VA / Bank Transfer',
        'redirect'        => false
    ],
    'midtrans_gopay' => [
        'payment_gateway' => 'Midtrans',
        'payment_method'  => 'gopay',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_gopay.png',
        'text'            => 'GoPay',
        'redirect'        => true
    ],
    'midtrans_shopeepay'  => [
        'payment_gateway' => 'Midtrans',
        'payment_method'  => 'shopeepay',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_shopee_pay.png',
        'text'            => 'ShopeePay',
        'redirect'        => true
    ],
    'midtrans_gopay_qris' => [
        'payment_gateway' => 'Midtrans',
        'payment_method'  => 'gopay',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_gopay_qris.png',
        'text'            => 'GoPay Qris',
        'redirect'        => true
    ],
    'midtrans_qris'    => [
        'payment_gateway' => 'Midtrans',
        'payment_method'  => 'qris',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_qris.png',
        'text'            => 'Qris',
        'redirect'        => false
    ],
    'durianpay_cc'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'CARD',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_creditcard.png',
        'text'            => 'Credit Card',
        'redirect'        => true
    ],
    'durianpay_bank_transfer'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'VA',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_banktransfer.png',
        'text'            => 'VA / Bank Transfer',
        'redirect'        => false
    ],
    'durianpay_linkaja'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'EWALLET:LINKAJA',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_linkaja.png',
        'text'            => 'LinkAja',
        'redirect'        => true
    ],
    'durianpay_ovo'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'EWALLET:OVO',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_ovo_pay.png',
        'text'            => 'Ovo',
        'redirect'        => true
    ],
    'durianpay_gopay'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'EWALLET:GOPAY',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_gopay.png',
        'text'            => 'Gopay',
        'redirect'        => true
    ],
    'durianpay_dana'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'EWALLET:DANA',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_dana.png',
        'text'            => 'Dana',
        'redirect'        => true
    ],
    'durianpay_shopeepay'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'EWALLET:SHOPEEPAY',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_shopee_pay.png',
        'text'            => 'ShopeePay',
        'redirect'        => true
    ],
    'durianpay_dana_qris'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'QRIS:DANA',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_dana_qris.png',
        'text'            => 'Dana Qris',
        'redirect'        => false
    ],
    'durianpay_shopeepay_qris'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'QRIS:SHOPEEPAY',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_shopeepay_qris.png',
        'text'            => 'ShopeePay Qris',
        'redirect'        => false
    ],
    'durianpay_jeniuspay'    => [
        'payment_gateway' => 'DurianPay',
        'payment_method'  => 'ONLINE_BANKING:JENIUSPAY',
        'status'          => 1,
        'logo'            => env('STORAGE_URL_VIEW') . 'images/default_image/payment_method/ic_jeniuspay.png',
        'text'            => 'JeniusPay',
        'redirect'        => true
    ],
];
