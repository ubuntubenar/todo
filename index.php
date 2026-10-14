<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Catatan Harian</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --paper: #FBF7EE;
    --paper-line: #E4DCC6;
    --ink: #2B2A33;
    --ink-soft: #6E6A5E;
    --plum: #5B5A8C;
    --plum-dark: #46456e;
  }

  * { box-sizing: border-box; }

  body {
    margin: 0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--paper);
    background-image:
      repeating-linear-gradient(
        to bottom,
        transparent 0px,
        transparent 27px,
        var(--paper-line) 28px
      );
    font-family: 'Inter', sans-serif;
    color: var(--ink);
    padding: 24px;
  }

  .page {
    width: 100%;
    max-width: 380px;
    text-align: center;
  }

  .book-mark {
    display: inline-block;
    font-family: 'Inter', sans-serif;
    font-size: 12px;
    letter-spacing: 0.08em;
    color: var(--ink-soft);
    margin-bottom: 10px;
  }

  h2 {
    font-family: 'Fraunces', serif;
    font-weight: 600;
    font-size: 30px;
    margin: 0 0 28px;
    color: var(--ink);
  }

  h2 .dot {
    color: var(--plum);
  }

  form {
    background: #ffffff;
    border: 1px solid var(--paper-line);
    border-radius: 14px;
    padding: 32px 28px 28px;
    box-shadow: 0 18px 40px -20px rgba(43, 42, 51, 0.35);
    text-align: left;
  }

  .field {
    margin-bottom: 18px;
  }

  .field:last-of-type {
    margin-bottom: 26px;
  }

  label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: var(--ink-soft);
    margin-bottom: 6px;
  }

  input[type="text"],
  input[type="password"] {
    width: 100%;
    padding: 11px 13px;
    border: 1px solid var(--paper-line);
    border-radius: 8px;
    background: #FDFBF6;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: var(--ink);
    outline: none;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }

  input[type="text"]:focus,
  input[type="password"]:focus {
    border-color: var(--plum);
    box-shadow: 0 0 0 3px rgba(91, 90, 140, 0.15);
  }

  input[type="submit"] {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    background: var(--plum);
    color: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 600;
    letter-spacing: 0.01em;
    cursor: pointer;
    transition: background 0.15s ease;
  }

  input[type="submit"]:hover {
    background: var(--plum-dark);
  }

  input[type="submit"]:focus-visible,
  input[type="text"]:focus-visible,
  input[type="password"]:focus-visible {
    outline: 2px solid var(--plum);
    outline-offset: 2px;
  }

  .foot-note {
    margin-top: 18px;
    font-size: 12px;
    color: var(--ink-soft);
  }

  @media (prefers-reduced-motion: reduce) {
    input[type="submit"] { transition: none; }
  }
</style>
</head>
<body>
  <div class="page">
    <span class="book-mark">Catatan Kegiatan</span>
   

    <!-- cek pesan notifikasi -->

    <form method="post" action="cek_login.php">
      <div class="field">
        <label for="user">Username</label>
        <input id="user" name="user" type="text" placeholder="Masukkan username" required />
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Masukkan password" required />
      </div>
      <input type="submit" value="Masuk & Mulai Menulis" />
    </form>

    <p class="foot-note">Catatan kegiatan, kemarin, hari ini dan yang akan dilakukan besok.</p>
  </div>
</body>
</html>

