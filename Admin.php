<?php
session_start();

/* CEK LOGIN */
if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

/* SESSION PENYIMPANAN DATA */
if(!isset($_SESSION['tangkapan'])){
    $_SESSION['tangkapan'] = [];
}

/* INISIALISASI PESAN */
$error = "";
$success = "";

/* CEK FORM SUBMIT - SIMPAN TANGKAPAN */
if(isset($_POST['simpan'])){

    $kapal  = trim($_POST['kapal']);
    $ikan   = trim($_POST['ikan']);
    $berat  = trim($_POST['berat']);
    $lokasi = trim($_POST['lokasi']);

    /* VALIDASI */
    if(empty($kapal) || empty($ikan) || empty($berat) || empty($lokasi)){
        $error = "Semua field harus diisi!";
    }
    elseif(!is_numeric($berat) || $berat <= 0){
        $error = "Berat harus angka lebih dari 0!";
    }
    else{
        $_SESSION['tangkapan'][] = [
            "kapal" => htmlspecialchars($kapal),
            "ikan" => htmlspecialchars($ikan),
            "berat" => htmlspecialchars($berat),
            "lokasi" => htmlspecialchars($lokasi),
            "waktu" => date("H:i:s")
        ];
        $success = "Data tangkapan berhasil disimpan!";
        $_POST = [];
    }
}

/* CEK HAPUS ROW */
if(isset($_GET['hapus'])){
    $index = (int)$_GET['hapus'];
    if(isset($_SESSION['tangkapan'][$index])){
        unset($_SESSION['tangkapan'][$index]);
        $_SESSION['tangkapan'] = array_values($_SESSION['tangkapan']); // reset index
        $success = "Data tangkapan berhasil dihapus!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Maritim Kupang - Admin Dashboard</title>
    <link rel="stylesheet" href="Admin.css" />
  </head>
  <body>

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
      <div class="sidebar-header">
        <h1>SMART MARITIM KUPANG</h1>
      </div>

      <nav class="nav-section">
        <p class="nav-title">Navigation</p>
        <ul class="nav-menu">
          <li class="nav-item"><a href="#dashboard"     class="nav-link active">Dashboard</a></li>
          <li class="nav-item"><a href="#tracking"      class="nav-link">Tracking Kapal</a></li>
          <li class="nav-item"><a href="#tangkapan"     class="nav-link">Arsip Tangkapan</a></li>
          <li class="nav-item"><a href="#parkir"        class="nav-link">Parking System</a></li>
          <li class="nav-item"><a href="#sensor"        class="nav-link">Sensor Air</a></li>
          <li class="nav-item"><a href="#monitoring"    class="nav-link">Live Monitoring</a></li>
          <li class="nav-item"><a href="#marine"        class="nav-link">Marine Weather</a></li>
          <li class="nav-item"><a href="#fishprice"     class="nav-link">Fish Price</a></li>
          <li class="nav-item"><a href="#limbah"        class="nav-link">Manajemen Limbah</a></li>
          <li class="nav-item"><a href="#berita"        class="nav-link">Berita Update</a></li>
          <li class="nav-item"><a href="#admin-setting" class="nav-link">Admin Management</a></li>
        </ul>
      </nav>

      <div class="user-info">
        <div class="user-avatar">A</div>
        <div>
          <div class="user-name">Admin</div>
          <div class="user-role">Administrator</div>
        </div>
      </div>

      <div style="padding: 16px 24px;">
        <button onclick="logout()" class="btn-primary" style="width:100%;">Logout</button>
      </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">

      <!-- ── DASHBOARD ── -->
      <div id="dashboard" class="page active">
        <div class="page-header">
          <h2>Dashboard</h2>
          <p>Ringkasan aktivitas pelabuhan dan status kapal secara real-time.</p>
        </div>

        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-card-title">Total Kapal</div>
            <div class="stat-card-value">23</div>
            <div class="stat-card-label">Kapal Aktif</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Cuaca</div>
            <div class="stat-card-value">28°C</div>
            <div class="stat-card-label">Cerah</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Kualitas Air</div>
            <div class="stat-card-value green">Baik</div>
            <div class="stat-card-label">Status Normal</div>
          </div>
        </div>

        <div class="content-grid">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Live Monitoring</h3>
            </div>
            <div class="card-body">
              <div class="map-container"></div>
            </div>
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Marine Weather</h3>
            </div>
            <div class="card-body">
              <div class="alerts-container">
                <div class="alert warning">
                  <div class="alert-title">Warning</div>
                  <div class="alert-message">KM Nusantara terlalu dekat karang</div>
                </div>
                <div class="alert info">
                  <div class="alert-title">Info</div>
                  <div class="alert-message">Cuaca stabil dan aman</div>
                </div>
                <div class="alert">
                  <div class="alert-title">Update Terakhir</div>
                  <div class="alert-message">2 menit yang lalu</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Kapal</h3>
          </div>
          <div class="card-body">
            <table>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Tipe</th>
                  <th>Kecepatan</th>
                  <th>Posisi</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><strong>KUP-001</strong></td>
                  <td>KM Harapan</td>
                  <td>⚓ Patrol</td>
                  <td>12 kn</td>
                  <td>-10.17, 123.58</td>
                  <td><span class="status-badge active">Aktif</span></td>
                </tr>
                <tr>
                  <td><strong>KUP-002</strong></td>
                  <td>KM Nusantara</td>
                  <td>⚓ Cargo</td>
                  <td>6 kn</td>
                  <td>-10.15, 123.62</td>
                  <td><span class="status-badge warning">Peringatan</span></td>
                </tr>
                <tr>
                  <td><strong>KUP-003</strong></td>
                  <td>KM Lautan</td>
                  <td>⚓ Ferry</td>
                  <td>0 kn</td>
                  <td>-10.16, 123.61</td>
                  <td><span class="status-badge idle">Berlabuh</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /dashboard -->

      <!-- ── TRACKING ── -->
      <div id="tracking" class="page">
        <div class="page-header">
          <h2>Tracking Kapal</h2>
          <p>Pelacakan posisi kapal secara real-time dengan GPS.</p>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Peta Tracking Real-time</h3>
          </div>
          <div class="card-body">
            <div class="map-container">
              <div class="map-icon"></div>
            </div>
          </div>
        </div>

        <div class="vessel-grid">
          <div class="vessel-card">
            <h3>KM Harapan</h3>
            <div class="vessel-info">
              <div>Posisi: -10.1729, 123.5831</div>
              <div>Kecepatan: 12 knot</div>
              <div>Heading: 045°</div>
              <div>Status: Aktif</div>
            </div>
          </div>
          <div class="vessel-card">
            <h3>KM Nusantara</h3>
            <div class="vessel-info">
              <div>Posisi: -10.1523, 123.6241</div>
              <div>Kecepatan: 6 knot</div>
              <div>Heading: 180°</div>
              <div>Status: Peringatan</div>
            </div>
          </div>
          <div class="vessel-card">
            <h3>KM Lautan</h3>
            <div class="vessel-info">
              <div>Posisi: -10.1612, 123.6108</div>
              <div>Kecepatan: 0 knot</div>
              <div>Heading: -</div>
              <div>Status: Berlabuh</div>
            </div>
          </div>
        </div>
      </div><!-- /tracking -->

      <!-- ── TANGKAPAN ── -->
      <div id="tangkapan" class="page">
        <div class="page-header">
          <h2>Arsip Tangkapan</h2>
          <p>Pencatatan hasil tangkapan ikan nelayan.</p>
        </div>

        <!-- 
          PERBAIKAN: 
          - Dua sistem (PHP POST + JS) digabung: form dikirim ke PHP via POST,
            lalu tabel di-render dari $_SESSION di bawah.
          - Ditambahkan name attribute pada setiap input agar $_POST bisa membacanya.
          - Ditambahkan hidden input "simpan" sebagai flag.
        -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Input Catatan Tangkapan</h3>
          </div>
          <div class="card-body">
            <?php if($error): ?>
<div style="background:#fee2e2;color:#b91c1c;padding:10px;border-radius:6px;margin-bottom:10px;">
<?= $error ?>
</div>
<?php endif; ?>

<?php if($success): ?>
<div style="background:#dcfce7;color:#166534;padding:10px;border-radius:6px;margin-bottom:10px;">
<?= $success ?>
</div>
<?php endif; ?>

<form method="POST" action="#tangkapan" style="max-width:600px;">
<input type="hidden" name="simpan" value="1">

<div class="form-group">
<label>Nama Kapal</label>
<input name="kapal" type="text" placeholder="Contoh: KM Harapan" required>
</div>

<div class="form-group">
<label>Jenis Ikan</label>
<select name="ikan">
<option>Tuna</option>
<option>Cakalang</option>
<option>Tongkol</option>
<option>Kembung</option>
<option>Baronang</option>
</select>
</div>

<div class="form-group">
<label>Berat Tangkapan (kg)</label>
<input name="berat" type="number" min="1" required>
</div>

<div class="form-group">
<label>Lokasi Tangkapan</label>
<input name="lokasi" type="text" placeholder="Koordinat atau lokasi" required>
</div>

<button type="submit" class="btn-primary">Simpan Catatan</button>
</form>
              <input type="hidden" name="simpan" value="1" />

              <div class="form-group">
                <label for="kapal">Nama Kapal</label>
                <input id="kapal" name="kapal" type="text"
                       placeholder="Contoh: KM Harapan" required />
              </div>

              <div class="form-group">
                <label for="ikan">Jenis Ikan</label>
                <select id="ikan" name="ikan">
                  <option>Tuna</option>
                  <option>Cakalang</option>
                  <option>Tongkol</option>
                  <option>Kembung</option>
                  <option>Baronang</option>
                </select>
              </div>

              <div class="form-group">
                <label for="berat">Berat Tangkapan (kg)</label>
                <input id="berat" name="berat" type="number"
                       placeholder="0" min="0" required />
              </div>

              <div class="form-group">
                <label for="lokasi">Lokasi Tangkapan</label>
                <input id="lokasi" name="lokasi" type="text"
                       placeholder="Koordinat atau nama lokasi" required />
              </div>

              <button type="submit" class="btn-primary">Simpan Catatan</button>
            </form>
          </div>
        </div>

        <!-- Tabel dari PHP SESSION -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Riwayat Tangkapan Hari Ini</h3>
          </div>
          <div class="card-body">
            <table>
              <thead>
                <tr>
                  <th>Waktu</th>
                  <th>Kapal</th>
                  <th>Jenis Ikan</th>
                  <th>Berat (kg)</th>
                  <th>Lokasi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="catch-table-body">
                <?php if (!empty($_SESSION['tangkapan'])): ?>
                  <?php foreach ($_SESSION['tangkapan'] as $i => $t): ?>
                    <tr id="row-<?= $i ?>">
                      <td><?= htmlspecialchars($t['waktu'])  ?></td>
                      <td><?= htmlspecialchars($t['kapal'])  ?></td>
                      <td><?= htmlspecialchars($t['ikan'])   ?></td>
                      <td><?= htmlspecialchars($t['berat'])  ?></td>
                      <td><?= htmlspecialchars($t['lokasi']) ?></td>
                      <td>
                        <!-- Hapus baris via JS (client-side saja) -->
                        <button class="btn-danger"
                                onclick="deleteRow('row-<?= $i ?>')">
                          Hapus
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr id="no-data-row">
                    <td colspan="6" style="text-align:center; color:#888;">
                      Belum ada data tangkapan hari ini.
                    </td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /tangkapan -->

      <!-- ── PARKIR ── -->
      <div id="parkir" class="page">
        <div class="page-header">
          <h2>Parking System</h2>
          <p>Manajemen tempat parkir kapal di pelabuhan.</p>
        </div>

        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-card-title">Total Slot</div>
            <div class="stat-card-value">25</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Terisi</div>
            <div class="stat-card-value red">18</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Tersedia</div>
            <div class="stat-card-value green">7</div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Layout Parkir Pelabuhan (Klik untuk toggle)</h3>
          </div>
          <div class="card-body">
            <div id="parking-grid" class="parking-grid"></div>
          </div>
        </div>
      </div><!-- /parkir -->

      <!-- ── SENSOR AIR ── -->
      <div id="sensor" class="page">
        <div class="page-header">
          <h2>Sensor Air</h2>
          <p>Monitoring kualitas air pelabuhan secara real-time.</p>
        </div>

        <div class="sensor-input">
          <input type="number" step="0.1" id="phInput"   placeholder="pH Air" />
          <input type="number" step="0.1" id="tempInput" placeholder="Suhu (°C)" />
          <input type="number" step="0.1" id="salInput"  placeholder="Salinitas (‰)" />
          <input type="number" step="0.1" id="oxyInput"  placeholder="Oksigen (mg/L)" />
          <button onclick="saveSensorData()">Simpan Data</button>
        </div>

        <div class="sensor-grid">
          <div class="sensor-card">
            <div class="stat-card-title">pH Air</div>
            <div class="sensor-value" id="phValue">7.2</div>
            <div class="stat-card-label">Normal</div>
          </div>
          <div class="sensor-card">
            <div class="stat-card-title">Suhu Air</div>
            <div class="sensor-value" id="tempValue">26°C</div>
            <div class="stat-card-label">Optimal</div>
          </div>
          <div class="sensor-card">
            <div class="stat-card-title">Salinitas</div>
            <div class="sensor-value" id="salValue">35‰</div>
            <div class="stat-card-label">Normal</div>
          </div>
          <div class="sensor-card">
            <div class="stat-card-title">Oksigen Terlarut</div>
            <div class="sensor-value" id="oxyValue">6.5</div>
            <div class="stat-card-label">mg/L</div>
          </div>
        </div>
      </div><!-- /sensor -->

      <!-- ── LIVE MONITORING ── -->
      <div id="monitoring" class="page">
        <div class="page-header">
          <h2>Live Monitoring</h2>
          <p>Pemantauan langsung aktivitas pelabuhan.</p>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">CCTV Pelabuhan</h3>
          </div>
          <div class="card-body">
            <div class="map-container-live" style="height:500px;"></div>
          </div>
        </div>
      </div><!-- /monitoring -->

      <!-- ── MARINE WEATHER ── -->
      <!-- PERBAIKAN: Typo "Wheater" → "Weather" -->
      <div id="marine" class="page">
        <div class="page-header">
          <h2>Marine Weather Management</h2>
          <p>Kelola informasi cuaca maritim dan kondisi laut. Data akan otomatis terlihat di User Dashboard.</p>
        </div>

        <div class="stats-grid">
          <div class="stat-card">
            <div class="stat-card-title">Kecepatan Angin</div>
            <div class="stat-card-value" id="stat-wind">15</div>
            <div class="stat-card-label">knot</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Tinggi Gelombang</div>
            <div class="stat-card-value" id="stat-wave">1.2</div>
            <div class="stat-card-label">meter</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Visibility</div>
            <div class="stat-card-value green" id="stat-visibility">Baik</div>
            <div class="stat-card-label">10+ km</div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Simpan Data Cuaca Maritim</h3>
          </div>
          <div class="card-body">
            <form onsubmit="simpanWeather(event)" style="max-width:800px;">
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div class="form-group">
                  <label>Kecepatan Angin (knot)</label>
                  <input id="weather-wind" type="number" step="0.1" placeholder="15" />
                </div>
                <div class="form-group">
                  <label>Tinggi Gelombang (meter)</label>
                  <input id="weather-wave" type="number" step="0.1" placeholder="1.2" />
                </div>
              </div>

              <div class="form-group">
                <label>Visibility</label>
                <select id="weather-visibility">
                  <option>Baik</option>
                  <option>Sedang</option>
                  <option>Buruk</option>
                </select>
              </div>

              <div class="form-group">
                <label>Kondisi Cuaca Saat Ini</label>
                <textarea id="weather-condition" rows="2"
                  placeholder="Cuaca cerah, aman untuk berlayar"></textarea>
              </div>

              <div class="form-group">
                <label>Peringatan Cuaca</label>
                <textarea id="weather-warning" rows="2"
                  placeholder="Potensi hujan ringan sore hari"></textarea>
              </div>

              <button type="submit" class="btn-primary">🌊 Simpan Data Cuaca</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Preview Data Cuaca (Yang Dilihat User)</h3>
          </div>
          <div class="card-body">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Kondisi Terkini</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Kondisi Cuaca</td>
                  <td id="preview-condition">Cuaca cerah, aman untuk berlayar</td>
                </tr>
                <tr>
                  <td>Peringatan</td>
                  <td id="preview-warning">Potensi hujan ringan sore hari</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- /marine -->

      <!-- ── FISH PRICE ── -->
      <div id="fishprice" class="page">
        <div class="page-header">
          <h2>Fish Price Management</h2>
          <p>Kelola harga ikan harian. Data akan otomatis terlihat di User Dashboard.</p>
        </div>

        <div class="stats-grid" id="fish-stats">
          <div class="stat-card">
            <div class="stat-card-title">Harga Tertinggi</div>
            <div class="stat-card-value green" id="highest-price">Rp 0</div>
            <div class="stat-card-label">per kg</div>
            <div class="stat-card-label" id="highest-fish-name">-</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Harga Terendah</div>
            <div class="stat-card-value" id="lowest-price">Rp 0</div>
            <div class="stat-card-label">per kg</div>
            <div class="stat-card-label" id="lowest-fish-name">-</div>
          </div>
          <div class="stat-card">
            <div class="stat-card-title">Rata-rata</div>
            <div class="stat-card-value" id="average-price">Rp 0</div>
            <div class="stat-card-label">per kg</div>
            <div class="form-group" style="margin-top:10px;">
              <label>Filter Jenis</label>
              <select id="fish-filter" onchange="updateAveragePrice()">
                <option value="">Semua Ikan</option>
              </select>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tambah Harga Ikan Baru</h3>
          </div>
          <div class="card-body">
            <form onsubmit="addFishPrice(event)" style="max-width:600px;">
              <div class="form-group">
                <label>Jenis Ikan</label>
                <input id="fish-name" placeholder="Contoh: Tuna" required />
              </div>
              <div class="form-group">
                <label>Harga / Kg (Rp)</label>
                <input id="fish-price" type="number" placeholder="45000" required />
              </div>
              <div class="form-group">
                <label>Kualitas</label>
                <select id="fish-quality">
                  <option>Grade A</option>
                  <option>Grade B</option>
                  <option>Grade C</option>
                </select>
              </div>
              <button class="btn-primary" type="submit">💾 Simpan Harga</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Harga Ikan Hari Ini</h3>
          </div>
          <div class="card-body">
            <table>
              <thead>
                <tr>
                  <th>Jenis Ikan</th>
                  <th>Harga (Rp/kg)</th>
                  <th>Kualitas</th>
                  <th>Status Pasar</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="fish-price-table"></tbody>
            </table>
          </div>
        </div>
      </div><!-- /fishprice -->

      <!-- ── LIMBAH ── -->
      <div id="limbah" class="page">
        <div class="page-header">
          <h2>Manajemen Limbah Laut</h2>
          <p>Pencatatan dan pemantauan limbah. Data akan otomatis terlihat di User Dashboard.</p>
        </div>

        <div class="stats-grid" id="waste-stats"></div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tambah Laporan Limbah</h3>
          </div>
          <div class="card-body">
            <form onsubmit="addWaste(event)" style="max-width:600px;">
              <div class="form-group">
                <label>Jenis Limbah</label>
                <input id="waste-type" placeholder="Contoh: Plastik, Minyak, Organik" required />
              </div>
              <div class="form-group">
                <label>Lokasi</label>
                <input id="waste-location" placeholder="Contoh: Dermaga A3" required />
              </div>
              <button class="btn-primary" type="submit">💾 Tambah Laporan</button>
              <button type="button" class="btn-primary"
                      onclick="laporanLimbah()"
                      style="margin-left:10px; background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);">
                📄 Lihat Laporan
              </button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Daftar Limbah yang Dilaporkan</h3>
          </div>
          <div class="card-body">
            <ul id="waste-list" style="list-style:none; padding:0;"></ul>
          </div>
        </div>
      </div><!-- /limbah -->

      <!-- ── BERITA UPDATE ── -->
      <!-- PERBAIKAN: Hapus inline style="display:none;" — biarkan JS yang mengatur visibilitas -->
      <div id="berita" class="page">
        <div class="page-header">
          <h2>Berita Update</h2>
          <p>Kelola berita dan pengumuman terkait pelabuhan.</p>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tambah Berita Baru</h3>
          </div>
          <div class="card-body">
            <form onsubmit="addNews(event)" style="max-width:600px;">
              <div class="form-group">
                <label>Judul Berita <span class="required">*</span></label>
                <input id="news-title" type="text" placeholder="Berita terbaru" required />
              </div>

              <div class="form-group">
                <label>Kategori <span class="required">*</span></label>
                <select id="news-category" required>
                  <option value="">Pilih Kategori</option>
                  <option value="Pengumuman">Pengumuman</option>
                  <option value="Kegiatan">Kegiatan</option>
                  <option value="Peringatan">Peringatan</option>
                  <option value="News">News</option>
                  <option value="Money">Money</option>
                  <option value="Article">Article</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
              </div>

              <div class="form-group">
                <label for="news-date">Tanggal Publikasi <span class="required">*</span></label>
                <input id="news-date" type="date" name="date" required />
              </div>

              <div class="form-group">
                <label for="news-url">Link Berita <span class="required">*</span></label>
                <input id="news-url" type="url"
                       placeholder="https://kompas.com/article/..." required />
                <div class="file-info">Copy-paste link artikel dari Kompas, Detik, Tribun, dll</div>
              </div>

              <div class="form-group">
                <label for="news-image-url">URL Gambar Berita <span class="required">*</span></label>
                <input id="news-image-url" type="url"
                       placeholder="https://example.com/image.jpg"
                       required onchange="previewImageFromUrl()" />
              </div>

              <!-- PERBAIKAN: Tambahkan alt attribute pada img -->
              <img id="previewImg" alt="Preview Gambar Berita"
                   style="max-width:100%; display:none; margin-top:10px; border-radius:8px;" />

              <div class="form-group">
                <label>Ringkasan Berita <span class="required">*</span></label>
                <textarea id="news-berita" required></textarea>
              </div>

              <div class="form-group">
                <label>Isi Berita (Opsional)</label>
                <textarea id="news-content"></textarea>
              </div>

              <button type="submit" class="btn-primary">Simpan Berita</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Berita yang Terupload</h3>
          </div>
          <div class="card-body">
            <table>
              <thead>
                <tr>
                  <th>Gambar</th>
                  <th>Judul</th>
                  <th>Kategori</th>
                  <th>Link</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="news-table-body"></tbody>
            </table>
          </div>
        </div>
      </div><!-- /berita -->

      <!-- ── ADMIN SETTINGS ── -->
      <!-- PERBAIKAN: Hapus inline style="display:none;" — biarkan JS yang mengatur visibilitas -->
      <div id="admin-setting" class="page">
        <div class="page-header">
          <h2>Pengaturan Admin</h2>
          <p>Kelola akun pengguna dan pengaturan sistem.</p>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Tambah Akun Baru</h3>
          </div>
          <div class="card-body">
            <form id="addAccountForm" onsubmit="addAccount(event)" style="max-width:600px;">
              <div class="form-group">
                <label>Username <span style="color:red;">*</span></label>
                <input id="new-username" type="text"
                       placeholder="Masukkan username" required />
              </div>

              <div class="form-group">
                <label>Password <span style="color:red;">*</span></label>
                <input id="new-password" type="password"
                       placeholder="Masukkan password" required />
              </div>

              <div class="form-group">
                <label>Role <span style="color:red;">*</span></label>
                <select id="new-role" required>
                  <option value="">Pilih Role</option>
                  <option value="Administrator">Administrator</option>
                  <option value="Operator">Operator</option>
                  <option value="Viewer">Viewer</option>
                </select>
              </div>

              <div class="form-group">
                <label>Email</label>
                <input id="new-email" type="email"
                       placeholder="email@example.com (opsional)" />
              </div>

              <div class="form-group">
                <label>Status <span style="color:red;">*</span></label>
                <select id="new-status" required>
                  <option value="Aktif">Aktif</option>
                  <option value="Nonaktif">Nonaktif</option>
                </select>
              </div>

              <button type="submit" class="btn-primary">➕ Tambah Akun</button>
            </form>
          </div>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Data Akun Terdaftar</h3>
          </div>
          <div class="card-body">
            <table class="data-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Tanggal Daftar</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="account-table"></tbody>
            </table>
          </div>
        </div>
      </div><!-- /admin-setting -->

    </main><!-- /main-content -->

    <script src="Admin.js"></script>
  </body>
</html>
