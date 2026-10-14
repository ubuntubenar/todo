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

$nama    = htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8');
$inisial = strtoupper(mb_substr($nama, 0, 1));

// Ambil 5 data terbaru
$lihat  = mysqli_query($koneksi, "SELECT * FROM todo ORDER BY id DESC LIMIT 5");
$jumlah = $lihat ? mysqli_num_rows($lihat) : 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#16324f">
    <title>Tambah Catatan</title>

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
            max-width: 760px;
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
            max-width: 760px;
            margin: 0 auto;
            padding: 24px 16px calc(48px + env(safe-area-inset-bottom, 0px));
        }
        .back {
            display: inline-block;
            margin-bottom: 12px;
            color: var(--navy-2);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        .back:hover { text-decoration: underline; }

        .heading h1 {
            margin: 0;
            font-size: clamp(26px, 5vw, 34px);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--navy);
        }
        .heading p { margin: 4px 0 0; color: var(--muted); font-size: 15px; }

        .card {
            background: var(--paper);
            border-radius: var(--radius);
            box-shadow: 0 1px 2px rgba(22,50,79,.06), 0 8px 24px rgba(22,50,79,.06);
            padding: 20px;
            margin-top: 20px;
        }

        /* ---------- Form ---------- */
        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
            color: var(--navy);
        }
        .field .hint { font-weight: 400; color: var(--muted); }

        .field input,
        .field textarea,
        .field select {
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            font-family: inherit;
            font-size: 16px;               /* 16px mencegah iOS zoom otomatis saat mengetik */
            color: var(--ink);
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 10px;
            transition: border-color .2s, box-shadow .2s;
        }
        .field textarea { min-height: 160px; resize: vertical; line-height: 1.5; }
        .field input:focus,
        .field textarea:focus,
        .field select:focus {
            outline: none;
            border-color: var(--navy-2);
            box-shadow: 0 0 0 4px rgba(31,74,115,.15);
        }

        .row { display: grid; grid-template-columns: 1fr; gap: 0 16px; }
        @media (min-width: 600px) {
            .row { grid-template-columns: 1fr 1fr; }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 52px;
            padding: 0 24px;
            border: 0;
            border-radius: 12px;
            background: var(--accent);
            color: var(--navy);
            font-family: inherit;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: transform .15s, background .2s;
        }
        .btn:hover { background: var(--accent-d); }
        .btn:active { transform: scale(.98); }
        .btn:focus-visible, .logout:focus-visible, .back:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
        }

        /* ---------- Data terbaru ---------- */
        .recent h2 { margin: 0 0 4px; font-size: 18px; color: var(--navy); }
        .recent > p { margin: 0 0 14px; font-size: 14px; color: var(--muted); }

        .item {
            padding: 14px 0;
            border-top: 1px solid var(--line);
        }
        .item:first-of-type { border-top: 0; }
        .item h3 {
            margin: 0 0 4px;
            font-size: 16px;
            color: var(--navy);
            overflow-wrap: anywhere;
        }
        .item .detail {
            margin: 0 0 8px;
            font-size: 14px;
            overflow-wrap: anywhere;
            display: -webkit-box;
            -webkit-line-clamp: 3;         /* detail panjang dipotong 3 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .meta { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; font-size: 13px; color: var(--muted); }
        .badge {
            padding: 3px 10px;
            border-radius: 999px;
            background: var(--ok-bg);
            color: var(--ok);
            font-weight: 600;
        }
        .badge.tutup { background: var(--off-bg); color: var(--off); }
        .empty { text-align: center; color: var(--muted); padding: 16px 0; }
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
        <a class="back" href="masuk.php">&larr; Kembali ke daftar</a>

        <div class="heading">
            <h1>Tambah Catatan</h1>
            <p>Isi topik dan detail pekerjaan yang akan dikerjakan.</p>
        </div>

        <form class="card" method="post" action="aksi.php">

            <div class="field">
                <label for="topik">Topik</label>
                <input id="topik" name="topik" type="text" required autocomplete="off">
            </div>

            <div class="field">
                <label for="detail">Detail topik</label>
                <textarea id="detail" name="detail" placeholder="Detail topik yang akan dikerjakan"></textarea>
            </div>

            <div class="row">
                <div class="field">
                    <label for="target">Target selesai</label>
                    <input id="target" name="target" type="text" placeholder="mis. 31 Oktober 2026" autocomplete="off">
                </div>

                <div class="field">
                    <label for="status">Status</label>
                    <input id="status" name="status" type="text" placeholder="mis. Sedang dikerjakan" autocomplete="off">
                </div>
            </div>

            <div class="field">
                <label for="oc">Open / Close</label>
                <select id="oc" name="oc">
                    <option value="o" selected>Open (o)</option>
                    <option value="c">Close (c)</option>
                </select>
            </div>

            <button class="btn" type="submit">Simpan</button>
        </form>

        <section class="card recent">
            <h2>Data terbaru</h2>
            <p>5 data yang terakhir ditambahkan.</p>

            <?php if ($jumlah === 0): ?>
                <div class="empty">Belum ada data.</div>
            <?php else: ?>
                <?php while ($row = mysqli_fetch_assoc($lihat)):
                    $topik  = htmlspecialchars($row['topik'],  ENT_QUOTES, 'UTF-8');
                    $detail = htmlspecialchars($row['detail'], ENT_QUOTES, 'UTF-8');
                    $target = htmlspecialchars($row['target'], ENT_QUOTES, 'UTF-8');
                    $status = htmlspecialchars($row['status'], ENT_QUOTES, 'UTF-8');
                    $tutup  = (isset($row['oc']) && $row['oc'] === 'c');
                ?>
                    <article class="item">
                        <h3><?= $topik ?></h3>
                        <p class="detail"><?= $detail ?></p>
                        <div class="meta">
                            <span class="badge <?= $tutup ? 'tutup' : '' ?>"><?= $tutup ? 'Close' : 'Open' ?></span>
                            <?php if ($status !== ''): ?><span>Status: <?= $status ?></span><?php endif; ?>
                            <?php if ($target !== ''): ?><span>Target: <?= $target ?></span><?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            <?php endif; ?>
        </section>
    </main>

</body>
</html>
