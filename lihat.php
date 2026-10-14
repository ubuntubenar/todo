<?php
// File ini di-include oleh ToDo.php (session sudah berjalan di sana)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pengaman: hanya untuk pengguna yang sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    echo "<p>Sesi berakhir. <a href='index.php?pesan=belum_login'>Silakan login kembali</a>.</p>";
    return;
}

// Koneksi ke MySQL
// Saran: pindahkan kredensial ini ke config.php agar tidak tersebar di banyak file
$koneksi = mysqli_connect("localhost", "benar", "Ben1612!", "pvt");

if (mysqli_connect_errno()) {
    echo "<p>Koneksi database gagal : " . htmlspecialchars(mysqli_connect_error()) . "</p>";
    return;
}
mysqli_set_charset($koneksi, "utf8mb4");

// Ambil data
$cari = mysqli_query($koneksi, "SELECT * FROM todo WHERE oc='o' ORDER BY id DESC");
$jumlah = $cari ? mysqli_num_rows($cari) : 0;
?>

<style>
    .todo-list { padding: 8px; }
    .todo-count {
        margin: 4px 4px 14px;
        font-size: 14px;
        color: var(--muted, #66788a);
    }
    .todo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 14px;
    }
    .todo-item {
        display: flex;
        flex-direction: column;
        border: 1px solid var(--line, #dde5ec);
        border-left: 5px solid var(--accent, #f2a900);
        border-radius: 12px;
        background: #fff;
        padding: 16px;
    }
    .todo-item h3 {
        margin: 0 0 8px;
        font-size: 17px;
        line-height: 1.3;
        color: var(--navy, #16324f);
        overflow-wrap: anywhere;
    }
    .todo-detail {
        flex: 1;
        margin: 0 0 16px;
        font-size: 14px;
        line-height: 1.6;
        color: var(--ink, #1b2733);
        overflow-wrap: anywhere;
    }
    .todo-actions {
        display: flex;
        gap: 8px;
        border-top: 1px solid var(--line, #dde5ec);
        padding-top: 12px;
    }
    .todo-actions a {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;             /* mudah disentuh di HP & tablet */
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: transform .15s, background .2s;
    }
    .todo-actions a:active { transform: scale(.97); }
    .todo-actions .edit {
        background: var(--navy, #16324f);
        color: #fff;
    }
    .todo-actions .edit:hover { background: var(--navy-2, #1f4a73); }
    .todo-actions .copy {
        background: var(--mist, #eef2f6);
        color: var(--navy, #16324f);
    }
    .todo-actions .copy:hover { background: var(--line, #dde5ec); }
    .todo-actions a:focus-visible {
        outline: 3px solid var(--accent, #f2a900);
        outline-offset: 2px;
    }
    .todo-empty {
        text-align: center;
        padding: 40px 16px;
        color: var(--muted, #66788a);
    }
    .todo-empty a { color: var(--navy-2, #1f4a73); font-weight: 600; }
</style>

<div class="todo-list">
<?php if ($jumlah === 0): ?>

    <div class="todo-empty">
        <p>Belum ada pekerjaan yang aktif.</p>
        <a href="tambahdata.php">Tambah data pertama</a>
    </div>

<?php else: ?>

    <p class="todo-count"><?= $jumlah ?> pekerjaan aktif</p>

    <div class="todo-grid">
    <?php while ($row = mysqli_fetch_assoc($cari)):
        $id     = (int) $row['id'];
        $topik  = htmlspecialchars($row['topik'],  ENT_QUOTES, 'UTF-8');
        $detail = nl2br(htmlspecialchars($row['detail'], ENT_QUOTES, 'UTF-8'));
    ?>
        <article class="todo-item">
            <h3><?= $topik ?></h3>
            <p class="todo-detail"><?= $detail ?></p>
            <div class="todo-actions">
                <a class="edit" href="edit.php?id=<?= $id ?>">Edit</a>
                <a class="copy" href="copy.php?id=<?= $id ?>">Copy</a>
            </div>
        </article>
    <?php endwhile; ?>
    </div>

<?php endif; ?>
</div>
