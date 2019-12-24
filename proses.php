<?php
$mhs = $_POST['id_mhs'];
$nilaik = $_POST['nilaik'];
$conn   = koneksi();

mysqli_query($conn,"CREATE TEMPORARY TABLE RangkingSementara(
  Rangking int AUTO_INCREMENT primary key,
  Nama varchar(200),
  IPS1 int,
  IPS2 int,
  IPS3 int,
  IPS4 int,
  IPS5 int,
  IPS6 int,
  IPS7 int,
  IPS8 int,
  IPS9 int,
  IPS10 int,
  sks_lulus int,
  Jarak Decimal(10,4),
  Status char(1));
  ");

  mysqli_query($conn,"CREATE TEMPORARY TABLE Kesimpulan(
    Nomor int AUTO_INCREMENT primary key,
    Nama varchar(200),
    IPS1 int,
    IPS2 int,
    IPS3 int,
    IPS4 int,
    IPS5 int,
    IPS6 int,
    IPS7 int,
    IPS8 int,
    IPS9 int,
    IPS10 int,
    sks_lulus int,
    Jarak Decimal(10,4),
    Status int(1));
    ");

    // Ambil nomor urut terbesar
    $nomor    = mysqli_query($conn,"SELECT max(nomor_urut) as max FROM detail_mhs WHERE id_mhs='$mhs';");
    $nomormax = mysqli_fetch_array($nomor);
    $maksimum = $nomormax['max'];

    // Data uji mhs
    $sql_testing = "SELECT * FROM mhs INNER JOIN detail_mhs ON mhs.id_mhs = detail_mhs.id_mhs WHERE mhs.id_mhs='$mhs' AND detail_mhs.nomor_urut='$maksimum'";
    $testing  = mysqli_query($conn,$sql_testing);
    $datates  = mysqli_fetch_array($testing);
    $gender   = $datates['jenis_kelamin'];

    // Data latih mhs
    $sql_train = "SELECT * FROM mhs INNER JOIN detail_mhs ON mhs.id_mhs = detail_mhs.id_mhs WHERE mhs.jenis_data='0' AND mhs.jenis_kelamin='$gender'";
    $training = mysqli_query($conn,$sql_train);
    while ($datatrain = mysqli_fetch_array($training)) {
      // Variable data latih
      $IPS1       = $datatrain['IPS1'];
      $IPS2       = $datatrain['IPS2'];
      $IPS3       = $datatrain['IPS3'];
      $IPS4       = $datatrain['IPS4'];
      $IPS5       = $datatrain['IPS5'];
      $IPS6       = $datatrain['IPS6'];
      $IPS7       = $datatrain['IPS7'];
      $IPS8       = $datatrain['IPS8'];
      $IPS9       = $datatrain['IPS9'];
      $IPS10      = $datatrain['IPS10'];
      $sks_lulus  = $datatrain['sks_lulus'];
      $Nama       = $datatrain['nama_mhs'];
      $Status     = $datatrain['status_tamat'];

      // Variabledata uji
      $IPS1_tes       = $datates['IPS1'];
      $IPS2_tes       = $datates['IPS2'];
      $IPS3_tes       = $datates['IPS3'];
      $IPS4_tes       = $datates['IPS4'];
      $IPS5_tes       = $datates['IPS5'];
      $IPS6_tes       = $datates['IPS6'];
      $IPS7_tes       = $datates['IPS7'];
      $IPS8_tes       = $datates['IPS8'];
      $IPS9_tes       = $datates['IPS9'];
      $IPS10_tes      = $datates['IPS10'];
      $sks_lulus_tes  = $datates['sks_lulus'];

      // Hitung jarak setiap sampel
      $distance = sqrt(
        pow(($IPS1_tes - $IPS1), 2) +
        pow(($IPS2_tes - $IPS2), 2) +
        pow(($IPS3_tes - $IPS3), 2) +
        pow(($IPS4_tes - $IPS4), 2) +
        pow(($IPS5_tes - $IPS5), 2) +
        pow(($IPS6_tes - $IPS6), 2) +
        pow(($IPS7_tes - $IPS7), 2) +
        pow(($IPS8_tes - $IPS8), 2) +
        pow(($IPS9_tes - $IPS9), 2) +
        pow(($IPS10_tes - $IPS10), 2) +
        pow(($sks_lulus_tes - $sks_lulus), 2));

        // Simpan hasil hitung jarak
        $sql_rank = "INSERT INTO RangkingSementara (
          Nama,
          IPS1,
          IPS2,
          IPS3,
          IPS4,
          IPS5,
          IPS6,
          IPS7,
          IPS8,
          IPS9,
          IPS10,
          sks_lulus,
          Jarak,
          Status)
          VALUES (
            '$Nama',
            '$IPS1',
            '$IPS2',
            '$IPS3',
            '$IPS4',
            '$IPS5',
            '$IPS6',
            '$IPS7',
            '$IPS8',
            '$IPS9',
            '$IPS10',
            '$sks_lulus',
            '$distance',
            '$Status'
          )";
          mysqli_query($conn,$sql_rank);
        }

        // Urutkan jarak dari yang terkecil
        $rangking = mysqli_query($conn,"SELECT * FROM RangkingSementara ORDER BY Jarak ASC LIMIT $nilaik;");
        while ($datas = mysqli_fetch_array($rangking)) {
          $Nama_rank      = $datas['Nama'];
          $IPS1_rank      = $datas['IPS1'];
          $IPS2_rank      = $datas['IPS2'];
          $IPS3_rank      = $datas['IPS3'];
          $IPS4_rank      = $datas['IPS4'];
          $IPS5_rank      = $datas['IPS5'];
          $IPS6_rank      = $datas['IPS6'];
          $IPS7_rank      = $datas['IPS7'];
          $IPS8_rank      = $datas['IPS8'];
          $IPS9_rank      = $datas['IPS9'];
          $IPS10_rank     = $datas['IPS10'];
          $sks_lulus_rank = $datas['sks_lulus'];
          $Jarak_rank     = $datas['Jarak'];
          $Status         = $datas['Status'];

          // Simpan kesimpulan
          $sql_kesim = "INSERT INTO Kesimpulan (
            Nama,
            IPS1,
            IPS2,
            IPS3,
            IPS4,
            IPS5,
            IPS6,
            IPS7,
            IPS8,
            IPS9,
            IPS10,
            sks_lulus,
            Jarak,
            Status
          )
          VALUES (
            '$Nama_rank',
            '$IPS1_rank',
            '$IPS2_rank',
            '$IPS3_rank',
            '$IPS4_rank',
            '$IPS5_rank',
            '$IPS6_rank',
            '$IPS7_rank',
            '$IPS8_rank',
            '$IPS9_rank',
            '$IPS10_rank',
            '$sks_lulus_rank',
            '$Jarak_rank',
            '$Status')";
            mysqli_query($conn, $sql_kesim);
          }

          ?>
          <div class="table-responsive">
            <h3>Kesimpulan Hasil Prediksi</h3>
            <table class="table table-bordered">
              <thead class='thead-light'>
                <tr>
                  <th style="width: 250px">Nama Mahasiswa</th>
                  <th>IPS1</th>
                  <th>IPS2</th>
                  <th>IPS3</th>
                  <th>IPS4</th>
                  <th>IPS5</th>
                  <th>IPS6</th>
                  <th>IPS7</th>
                  <th>IPS8</th>
                  <th>IPS9</th>
                  <th>IPS10</th>
                  <th>SKS Lulus</th>
                  <th>Jenis Kelamin</th>
                  <th>Jarak Terdekat</th>
                  <th>Kesimpulan</th>
                </tr>
              </thead>

              <tbody>
                <?php
                // Mengambil kategori terbanyak
                $sql_kesimpulan = "SELECT Status, count(Status) as jumlah FROM Kesimpulan GROUP BY Status ORDER BY jumlah DESC"
                $kesimpulan = mysqli_query($conn, $sql_kesimpulan);
                $data = mysqli_fetch_array($kesimpulan);
                $status = "";
                $warna  = "";
                if($data['Status'] == 1) {$status = "Tepat Waktu";$warna = "badge-success";}
                if($data['Status'] == 2) {$status = "Terlambat";$warna = "badge-danger";}
                ?>
                <tr>
                  <td><?=$datates["nama_mhs"]?></td>
                  <td><?=$datates["IPS1"]?></td>
                  <td><?=$datates["IPS2"]?></td>
                  <td><?=$datates["IPS3"]?></td>
                  <td><?=$datates["IPS4"]?></td>
                  <td><?=$datates["IPS5"]?></td>
                  <td><?=$datates["IPS6"]?></td>
                  <td><?=$datates["IPS7"]?></td>
                  <td><?=$datates["IPS8"]?></td>
                  <td><?=$datates["IPS9"]?></td>
                  <td><?=$datates["IPS10"]?></td>
                  <td><?=$datates["sks_lulus"]?></td>
                  <td><?php
                  $jk = "Laki-laki";
                  if($datates["jenis_kelamin"] == "P"){
                    $jk = "Perempuan";
                  } echo $jk;
                  ?>
                </td>
                <td><span id="nilaik"><?=$nilaik?></span></td>
                <td>
                  <span class="badge <?=$warna?>" style="font-size: 12px;"><?=$status?></span>
                  <span class="invisible" id="id_mhs"><?=$datates["id_mhs"]?></span>
                  <span class="invisible" id="status"><?=$data["Status"]?></span>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="col-md-12" style="text-align:center;margin-top: 20px;margin-bottom: 30px">
            <button type="submit" id="button" name="simpan" class="btn btn-primary">SIMPAN</button>
          </div>
        </div> <!-- responsive -->

        <h3 class="box-title">Jarak Terdekat (k = <?=$nilaik?>)</h3>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead class='thead-light'>
              <tr>
                <th style="width: 50px">Rangking</th>
                <th>Nama Mahasiswa</th>
                <th>IPS1</th>
                <th>IPS2</th>
                <th>IPS3</th>
                <th>IPS4</th>
                <th>IPS5</th>
                <th>IPS6</th>
                <th>IPS7</th>
                <th>IPS8</th>
                <th>IPS9</th>
                <th>IPS10</th>
                <th>SKS Lulus</th>
                <th>Jenis Kelamin</th>
                <th>Jarak Terdekat</th>
                <th>Kesimpulan</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Mengambil kategori terbanyak
              $jarak = mysqli_query($conn,"SELECT * FROM Kesimpulan");
              while ($data = mysqli_fetch_array($jarak)) {
                $status = "";
                $warna  = "";
                if($data['Status'] == 1) {$status = "Tepat Waktu";$warna = "badge-success";}
                if($data['Status'] == 2) {$status = "Terlambat";$warna = "badge-danger";}
                ?>
                <tr>
                  <td><?=$data["Nomor"]?></td>
                  <td><?=$data["nama_mhs"]?></td>
                  <td><?=$data["IPS1"]?></td>
                  <td><?=$data["IPS2"]?></td>
                  <td><?=$data["IPS3"]?></td>
                  <td><?=$data["IPS4"]?></td>
                  <td><?=$data["IPS5"]?></td>
                  <td><?=$data["IPS6"]?></td>
                  <td><?=$data["IPS7"]?></td>
                  <td><?=$data["IPS8"]?></td>
                  <td><?=$data["IPS9"]?></td>
                  <td><?=$data["IPS10"]?></td>
                  <td><?=$data["sks_lulus"]?></td>
                  <td><?php
                  $jk = "Laki-laki";
                  if($datates["jenis_kelamin"] == "P"){
                    $jk = "Perempuan";
                  } echo $jk;
                  ?>
                </td>
                <td><span id="nilaik"><?=$nilaik?></span></td>
                <td><span class="badge <?=$warna?>" style="font-size: 12px;"><?=$status?></span></td>
                <td><?=$data["Jarak"]?></td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div> <!-- responsive -->
