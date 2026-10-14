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

// ---------------------------------------------------------------
// Proses update data
// ---------------------------------------------------------------
$pesan = "";
$tipe  = "";   // "sukses" | "gagal" | "info"

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {

    $id     = (int) $_POST['id'];
    $topik  = trim($_POST['topik']  ?? '');
    $detail = trim($_POST['detail'] ?? '');
    $target = trim($_POST['target'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $oc     = trim($_POST['oc']     ?? '');

    if ($id <= 0 || $topik === '') {
        $pesan = "Data tidak lengkap. Pastikan topik sudah diisi.";
        $tipe  = "gagal";
    } else {
        // Prepared statement: aman dari SQL injection
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE todo SET topik=?, detail=?, target=?, status=?, oc=? WHERE id=?"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sssssi", $topik, $detail, $target, $status, $oc, $id);

            if (mysqli_stmt_execute($stmt)) {
                $pesan = "Data \"" . htmlspecialchars($topik, ENT_QUOTES, 'UTF-8') . "\" berhasil diperbarui.";
                $tipe  = "sukses";
            } else {
                $pesan = "Data gagal diperbarui. Silakan coba lagi.";
                $tipe  = "gagal";
            }
            mysqli_stmt_close($stmt);
        } else {
            $pesan = "Terjadi kesalahan pada database.";
            $tipe  = "gagal";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#16324f">
    <title>Things To Do</title>

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
            --err-bg:  #fdeaea;
            --err:     #b42323;
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
            padding: 24px 16px 96px;
        }
        .heading h1 {
            margin: 0;
            font-size: clamp(26px, 5vw, 36px);
            font-weight: 800;
            letter-spacing: -0.02em;
            color: var(--navy);
        }
        .heading p { margin: 4px 0 0; color: var(--muted); font-size: 15px; }

        /* Notifikasi hasil update */
        .notice {
            margin: 20px 0 0;
            padding: 14px 16px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 500;
            border-left: 5px solid;
        }
        .notice.sukses { background: var(--ok-bg);  color: var(--ok);  border-color: var(--ok); }
        .notice.gagal  { background: var(--err-bg); color: var(--err); border-color: var(--err); }

        .actions { display: flex; flex-wrap: wrap; gap: 10px; margin: 20px 0; }
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

        .card {
            background: var(--paper);
            border-radius: var(--radius);
            box-shadow: 0 1px 2px rgba(22,50,79,.06), 0 8px 24px rgba(22,50,79,.06);
            padding: 8px;
        }
        .konten { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        /* Tombol tambah melayang di layar kecil */
        .fab {
            position: fixed;
            right: 18px;
            bottom: calc(18px + env(safe-area-inset-bottom, 0px));
            display: none;
            box-shadow: 0 6px 18px rgba(22,50,79,.3);
            border-radius: 999px;
        }

        @media (max-width: 600px) {
            .actions .btn { flex: 1 1 100%; }
            .fab { display: inline-flex; }
        }

        .btn:focus-visible, .logout:focus-visible {
            outline: 3px solid var(--accent);
            outline-offset: 2px;
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
            <h1>Things To Do</h1>
            <p>Daftar pekerjaan yang perlu Anda selesaikan.</p>
        </div>

        <?php if ($pesan !== ""): ?>
            <div class="notice <?= $tipe ?>" role="status"><?= $pesan ?></div>
        <?php endif; ?>

        <div class="actions">
            <a class="btn btn-primary" href="tambahdata.php">+ Tambah data</a>
            <a class="btn btn-secondary" href="all.php">Semua record</a>
        </div>

        <section class="card">
            <div class="konten">
                <?php include 'lihat.php'; ?>
            </div>
        </section>
    </main>

    <!-- Tombol cepat untuk handphone -->
    <a class="btn btn-primary fab" href="tambahdata.php" title="Tambah data">+ Tambah data</a>

</body>
</html>
