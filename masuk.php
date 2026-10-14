<?php
// session_start harus dipanggil sebelum ada output HTML apa pun
session_start();

// Cek apakah sudah login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: index.php?pesan=belum_login");
    exit;
}

include 'config.php';

$nama    = htmlspecialchars($_SESSION['user'], ENT_QUOTES, 'UTF-8');
$inisial = strtoupper(mb_substr($nama, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#16324f">
    <title>My Things To Do</title>

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
            padding-bottom: env(safe-area-inset-bottom, 0);
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
        .user {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }
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
        .heading p {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 15px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 20px 0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 46px;            /* nyaman disentuh di layar sentuh */
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

        /* Area output dari lihat.php: tabel bisa digeser ke samping di layar kecil */
        .konten {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .konten table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            min-width: 480px;
        }
        .konten th {
            text-align: left;
            background: var(--mist);
            color: var(--navy);
            font-weight: 600;
            padding: 12px;
            white-space: nowrap;
        }
        .konten th:first-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .konten th:last-child  { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
        .konten td {
            padding: 12px;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }
        .konten tr:last-child td { border-bottom: 0; }
        .konten a { color: var(--navy-2); font-weight: 600; }
        .konten img { max-width: 100%; height: auto; border-radius: 8px; }

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
            .wrap { padding-top: 20px; }
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
            <h1>My Things To Do</h1>
            <p>Daftar pekerjaan yang perlu Anda selesaikan.</p>
        </div>

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
