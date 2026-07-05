<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-6">
        <?= form_open('buy', 'class="row g-3"') ?>

<?= form_hidden('username', session()->get('username')) ?>

<div class="col-12">
    <?= form_label('Nama', 'nama', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'nama',
        'id'       => 'nama',
        'class'    => 'form-control',
        'value'    => session()->get('username'),
        'readonly' => true]) ?>
</div>
<div class="col-12">
    <?= form_label('Alamat', 'alamat', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'  => 'alamat',
        'id'    => 'alamat',
        'class' => 'form-control']) ?>
</div> 
<div class="col-12"> 
    <?= form_label('Kelurahan', 'kelurahan', ['class' => 'form-label']) ?>
    <?= form_dropdown('kelurahan', [], '', ['id' => 'kelurahan', 'class' => 'form-control']) ?>
</div>

<div class="col-12"> 
    <?= form_label('Layanan', 'layanan', ['class' => 'form-label']) ?> 
    <?= form_dropdown('layanan', [], '', ['id' => 'layanan', 'class' => 'form-control']) ?>
</div>

<div class="col-12">
    <?= form_label('Kode Voucher', 'voucher_code', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'  => 'voucher_code',
        'id'    => 'voucher_code',
        'class' => 'form-control',
        'placeholder' => 'Contoh: FLASH10']) ?>
    <small class="text-muted">Tersedia: FLASH10, FLASH15, MEMBER20</small>
</div>

<div class="col-12">
    <?= form_label('Ongkir', 'ongkir', ['class' => 'form-label']) ?>
    <?= form_input([
        'name'     => 'ongkir',
        'id'       => 'ongkir',
        'class'    => 'form-control',
        'readonly' => true]) ?>
</div>

<div class="col-12">
    <?= form_submit(
        'submit',
        'Buat Pesanan',
        ['class' => 'btn btn-primary']) ?>
</div>

<?= form_close() ?> 
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Ringkasan Pesanan</h5>
                <table class="table">
                  <thead>
                      <tr>
                          <th scope="col">Nama</th>
                          <th scope="col">Harga</th>
                          <th scope="col">Jumlah</th>
                          <th scope="col">Sub Total</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php 
                      if (!empty($items)) :
                          foreach ($items as $index => $item) :
                      ?>
                              <tr>
                                  <td><?= $item['name'] ?></td>
                                  <td><?= number_to_currency($item['price'], 'IDR') ?></td>
                                  <td><?= $item['qty'] ?></td>
                                  <td><?= number_to_currency($item['price'] * $item['qty'], 'IDR') ?></td>
                              </tr>
                      <?php
                          endforeach;
                      endif;
                      ?>
                      <tr>
                          <td colspan="2"></td>
                          <td>Subtotal</td>
                          <td><?= number_to_currency($total, 'IDR') ?></td>
                      </tr>
                      <tr id="row-diskon" style="display:none;">
                          <td colspan="2"></td>
                          <td class="text-danger">Diskon Voucher</td>
                          <td id="diskon_voucher" class="text-danger">-</td>
                      </tr>
                      <tr>
                          <td colspan="2"></td>
                          <td>PPN (11%)</td>
                          <td id="ppn_value">-</td>
                      </tr>
                      <tr>
                          <td colspan="2"></td>
                          <td>Biaya Admin</td>
                          <td id="admin_value">-</td>
                      </tr>
                      <tr>
                          <td colspan="2"></td>
                          <td class="text-success">Subtotal (+PPN+Admin-Voucher)</td>
                          <td id="subtotal_after" class="text-success fw-bold">-</td>
                      </tr>
                      <tr>
                          <td colspan="2"></td>
                          <td class="fw-bold">Grand Total (incl. Ongkir)</td>
                          <td id="total" class="fw-bold">-</td>
                      </tr>
                  </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
$(document).ready(function() {
    let ongkir = 0;
    let subtotal = <?= $total ?>;
    hitungTotal();

    function hitungBiayaAdmin(total) {
        if (total <= 20000000) return total * 0.006;
        if (total <= 40000000) return total * 0.008;
        return total * 0.01;
    }

    function getVoucherPersen(kode) {
        const vouchers = { 'FLASH10': 0.10, 'FLASH15': 0.15, 'MEMBER20': 0.20 };
        kode = (kode || '').toUpperCase().trim();
        return vouchers[kode] || 0;
    }

    function hitungDiskonVoucher(total, kode) {
        const persen = getVoucherPersen(kode);
        return total * persen;
    }

    function hitungTotal() {
        let kode = $("#voucher_code").val();
        let persen = getVoucherPersen(kode);
        let diskon = hitungDiskonVoucher(subtotal, kode);
        let ppn = subtotal * 0.11;
        let admin = hitungBiayaAdmin(subtotal);

        let subtotalAfter = subtotal - diskon + ppn + admin;
        let grandTotal = subtotalAfter + ongkir;

        if (diskon > 0) {
            $("#row-diskon").show();
            let persenText = Math.round(persen * 100);
            $("#diskon_voucher").html(`-IDR ${diskon.toLocaleString('id-ID')} <br><small>(${persenText}%)</small>`);
        } else {
            $("#row-diskon").hide();
        }

        $("#ppn_value").text(`IDR ${ppn.toLocaleString('id-ID')}`);
        $("#admin_value").text(`IDR ${admin.toLocaleString('id-ID')}`);
        $("#subtotal_after").text(`IDR ${subtotalAfter.toLocaleString('id-ID')}`);
        $("#ongkir").val(ongkir);
        $("#total").text(`IDR ${grandTotal.toLocaleString('id-ID')}`);
    }

	$('#kelurahan').select2({
	    placeholder: 'Cari daerah tujuan',
	    minimumInputLength: 3, 
        ajax: {
        url: '<?= site_url('ajax/destinations') ?>',
        dataType: 'json',
        delay: 300,
        data: function(params) {
            return {
                q: params.term
            };
        },
        processResults: function(data) {
            return data;
        },
        cache: true
    }
	});

    $("#kelurahan").on('change', function () {
    let id_kelurahan = $(this).val();

    $("#layanan").empty();
    ongkir = 0;
    hitungTotal(); 

    $.ajax({
    url: "<?= site_url('ajax/costs') ?>", 
    dataType: "json",
    data: {
        destination: id_kelurahan
    },
    success: function (data) { 
        data.forEach(function (item) {
            $("#layanan").append(
                $('<option>', {
                    value: item.cost,
                    text: `${item.description} (${item.service}) : estimasi ${item.etd}`
                })
            );
        });
    }
});
});

$("#layanan").on('change', function() {
    ongkir = parseInt($(this).val());
    hitungTotal();
}); 

$("#voucher_code").on('input', function() {
    hitungTotal();
});
});
</script>
<?= $this->endSection() ?>