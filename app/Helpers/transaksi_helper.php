<?php

if (!function_exists('hitung_ppn')) {
    function hitung_ppn($total_harga)
    {
        return $total_harga * 0.11;
    }
}

if (!function_exists('hitung_biaya_admin')) {
    function hitung_biaya_admin($total_harga)
    {
        if ($total_harga <= 20000000) {
            $tarif = 0.006;
        } elseif ($total_harga <= 40000000) {
            $tarif = 0.008;
        } else {
            $tarif = 0.01;
        }

        return $total_harga * $tarif;
    }
}

if (!function_exists('hitung_diskon_voucher')) {
    function hitung_diskon_voucher($total_harga, $voucher_code)
    {
        $vouchers = [
            'FLASH10'   => 0.10,
            'FLASH15'   => 0.15,
            'MEMBER20'  => 0.20,
        ];

        $voucher_code = strtoupper(trim($voucher_code ?? ''));

        if (!isset($vouchers[$voucher_code])) {
            return 0;
        }

        return $total_harga * $vouchers[$voucher_code];
    }
}