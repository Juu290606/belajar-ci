<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Data Tables</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Profile</h5>

            <h6 class="fw-bold mb-3">Profil Pengguna</h6>

            <ul class="list-unstyled">
                <li><strong>Username:</strong> <?= $username ?></li>
                <li><strong>Role:</strong> <?= $role ?></li>
                <li><strong>Email:</strong> <?= $email ?></li>
                <li><strong>Waktu Login:</strong> <?= $login_at ?></li>
                <li><strong>Status Login:</strong> <?= $is_login ? 'Sudah Login' : 'Belum Login' ?></li>
            </ul>

        </div>
    </div>
</section>

<?= $this->endSection() ?>