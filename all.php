<?php
// session_start harus dipanggil sebelum ada output HTML apa pun
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: index.php?pesan=belum_login");
    exit;
}

// Koneksi database (menyediakan variabel $koneksi)
include 'config.php';
mysqli_set_charset($koneksi, "utf8mb4");

$nama    = htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8');
$inisial = strtoupper(mb_substr($nama, 0, 1));

// Ambil semua data
$cari   = mysqli_query($koneksi, "SELECT * FROM todo ORDER BY id DESC");
$jumlah = $cari ? mysqli_num_rows($cari) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#16324f">
    <title>Semua Record - My Things To Do</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --navy:    #16324f;
            --navy-2:  #1f4a73;
            --mist:    #eef2f6;
            --paper:   #ffffff;
            --ink:     #1b2733;
            --muted:   #66788a;
            --line:    #dde5ec;
            --accent:  #f2a900;
            --accent-d:#d99500;
            --ok-bg:   #e6f5ec;
            --ok:      #1d7a44;
            --off-bg:  #eceff2;
            --off:     #5b6773;
            --danger:  #b42323;
            --danger-bg:#fdeaea;
            --radius:  14px;
        }

        * { box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; }

        body {
            margin: 0;
            font-family: "Plus Jakarta Sans", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            background: var(--mist);
            color: var(--ink);
            line-height: 1.55;
        }

        /* ---------- Bilah atas ---------- */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 10;
            background: var(--navy);
            color: #fff;
            padding: calc(12px + env(safe-area-inset-top, 0px)) 16px 12px;
        }
        .topbar-inner {
            max-width: 960px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .user { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .avatar {
            flex: 0 0 auto;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            color: var(--navy);
            font-weight: 800;
            display: grid;
            place-items: center;
        }
        .user-text { min-width: 0; line-height: 1.25; }
        .user-text small { display: block; color: #b9cbdd; font-size: 12px; }
        .user-text strong {
            display: block;
            font-size: 15px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .logout {
            flex: 0 0 auto;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 9px 16px;
            border: 1px solid rgba(255,255,255,.35);
            border-radius: 999px;
            transition: background .2s;
        }
        .logout:hover { background: rgba(255,255,255,.12); }

        /* ---------- Konten ---------- */
        .wrap {
            max-width: 960px;
            margin: 0 auto;
            padding: 24px 16px calc(48px + env(safe-area-inset-bottom, 0px));
        }
        .heading h1 {
            margin: 0;
            font-size: clamp(26px, 5vw, 36px);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--navy);
        }
        .heading p { margin: 4px 0 0; color: var(--muted); font-size: 15px; }

        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 0 16px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 0 20px;
            border-radius: 12px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            border: 2px solid transparent;
            transition: transform .15s, background .2s;
        }
        .btn:active { transform: scale(.97); }
        .btn-primary   { background: var(--accent); color: var(--navy); }
        .btn-primary:hover { background: var(--accent-d); }
        .btn-secondary { background: var(--paper); color: var(--navy); border-color: var(--line); }
        .btn-secondary:hover { border-color: var(--navy-2); }

        /* Kolom cari */
        .search {
            width: 100%;
            min-height: 48px;
            padding: 12px 16px;
            margin-bottom: 8px;
            font-family: inherit;
            font-size: 16px;               /* 16px mencegah iOS zoom otomatis */
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 12px;
        }
        .search:focus {
            outline: none;
            border-color: var(--navy-2);
            box-shadow: 0 0 0 4px rgba(31,74,115,.15);
        }
        .count { margin: 0 4px 14px; font-size: 14px; color: var(--muted); }

        /* ---------- Kartu record ---------- */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
            gap: 14px;
        }
        .item {
            display: flex;
            flex-direction: column;
            background: var(--paper);
            border: 1px solid var(--line);
            border-left: 5px solid var(--accent);
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 2px rgba(22,50,79,.05);
        }
        .item.tutup { border-left-color: #b4bcc4; }
        .item h3 {
            margin: 0 0 6px;
            font-size: 17px;
            line-height: 1.3;
            color: var(--navy);
            overflow-wrap: anywhere;
        }
        .badge {
            align-self: flex-start;
            margin-bottom: 10px;
            padding: 3px 10px;
            border-radius: 999px;
            background: var(--ok-bg);
            color: var(--ok);
            font-size: 12px;
            font-weight: 600;
        }
        .badge.tutup { background: var(--off-bg); color: var(--off); }
        .detail {
            flex: 1;
            margin: 0 0 16px;
            font-size: 14px;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }
        .aksi {
            display: flex;
            gap: 8px;
            padding-top: 12px;
            border-top: 1px solid var(--line);
        }
        .aksi a {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;              /* mudah disentuh di HP & tablet */
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: transform .15s, background .2s;
        }
        .aksi a:active { transform: scale(.97); }
        .aksi .copy { background: var(--mist); color: var(--navy); }
        .aksi .copy:hover { background: var(--line); }
        .aksi .hapus { background: var(--danger-bg); color: var(--danger); }
        .aksi .hapus:hover { background: #f8d4d4; }

        .empty { text-align: center; padding: 40px 16px; color: var(--muted); }
        .empty a { color: var(--navy-2); font-weight: 600; }
        #tidakAda { display: none; }

        .btn:focus-visible, .logout:focus-visible, .aksi a:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
        }

        @media (max-width: 600px) {
            .actions .btn { flex: 1 1 100%; }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="topbar-inner">
            <div class="user">
                <div class="avatar" aria-hidden="true"><?= $inisial ?></div>
                <div class="user-text">
                    <small>Anda masuk sebagai</small>
                    <strong><?= $nama ?></strong>
                </div>
            </div>
            <a class="logout" href="logout.php">Keluar</a>
        </div>
    </header>

    <main class="wrap">
        <div class="heading">
            <h1>Semua record</h1>
            <p>Seluruh pekerjaan, baik yang masih open maupun sudah close.</p>
        </div>

        <div class="actions">
            <a class="btn btn-secondary" href="masuk.php">Halaman utama</a>
            <a class="btn btn-primary" href="tambahdata.php">+ Tambah data</a>
        </div>

        <?php if ($jumlah === 0): ?>

            <div class="empty">
                <p>Belum ada data.</p>
                <a href="tambahdata.php">Tambah data pertama</a>
            </div>

        <?php else: ?>

            <input class="search" id="cari" type="search"
                   placeholder="Cari topik atau detail..." autocomplete="off"
                   aria-label="Cari topik atau detail">
            <p class="count"><span id="jumlah"><?= $jumlah ?></span> record</p>

            <div class="grid" id="daftar">
            <?php while ($row = mysqli_fetch_assoc($cari)):
                $id     = (int) $row['id'];
                $topik  = htmlspecialchars($row['topik'],  ENT_QUOTES, 'UTF-8');
                $detail = nl2br(htmlspecialchars($row['detail'], ENT_QUOTES, 'UTF-8'));
                $tutup  = (isset($row['oc']) && $row['oc'] === 'c');
            ?>
                <article class="item <?= $tutup ? 'tutup' : '' ?>">
                    <h3><?= $topik ?></h3>
                    <span class="badge <?= $tutup ? 'tutup' : '' ?>"><?= $tutup ? 'Close' : 'Open' ?></span>
                    <p class="detail"><?= $detail ?></p>
                    <div class="aksi">
                        <a class="copy" href="copy.php?id=<?= $id ?>">Copy</a>
                        <a class="hapus" href="delete.php?id=<?= $id ?>"
                           onclick="return confirm('Hapus data ini? Tindakan ini tidak bisa dibatalkan.');">Delete</a>
                    </div>
                </article>
            <?php endwhile; ?>
            </div>

            <div class="empty" id="tidakAda">Tidak ada record yang cocok.</div>

        <?php endif; ?>
    </main>

    <script>
        // Pencarian langsung di halaman (tanpa reload)
        (function () {
            var input = document.getElementById('cari');
            if (!input) return;

            var kartu    = document.querySelectorAll('#daftar .item');
            var label    = document.getElementById('jumlah');
            var tidakAda = document.getElementById('tidakAda');

            input.addEventListener('input', function () {
                var kata = input.value.trim().toLowerCase();
                var tampil = 0;

                kartu.forEach(function (k) {
                    var cocok = k.textContent.toLowerCase().indexOf(kata) !== -1;
                    k.style.display = cocok ? '' : 'none';
                    if (cocok) tampil++;
                });

                label.textContent = tampil;
                tidakAda.style.display = tampil === 0 ? 'block' : 'none';
            });
        })();
    </script>

</body>
</html>
