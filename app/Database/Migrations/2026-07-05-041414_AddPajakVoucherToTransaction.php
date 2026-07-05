<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPajakVoucherToTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'ppn' => [
                'type' => 'DOUBLE',
                'null' => true,
                'after' => 'total_harga',
            ],
            'biaya_admin' => [
                'type' => 'DOUBLE',
                'null' => true,
                'after' => 'ppn',
            ],
            'voucher_code' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'biaya_admin',
            ],
            'diskon_voucher' => [
                'type' => 'DOUBLE',
                'null' => true,
                'after' => 'voucher_code',
            ],
        ];

        $this->forge->addColumn('transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['ppn', 'biaya_admin', 'voucher_code', 'diskon_voucher']);
    }
}