<!-- CHART -->
<style media="screen">
.highcharts-figure, .highcharts-data-table table {
  min-width: 360px;
  max-width: 800px;
  margin: 1em auto;
}

.highcharts-data-table table {
  font-family: Verdana, sans-serif;
  border-collapse: collapse;
  border: 1px solid #EBEBEB;
  margin: 10px auto;
  text-align: center;
  width: 100%;
  max-width: 500px;
}
.highcharts-data-table caption {
  padding: 1em 0;
  font-size: 1.2em;
  color: #555;
}
.highcharts-data-table th {
  font-weight: 600;
  padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
  padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
  background: #f8f8f8;
}
.highcharts-data-table tr:hover {
  background: #f1f7ff;
}

</style>

<script src="Highcharts-8.0.0/code/highcharts.js"></script>
<script src="Highcharts-8.0.0/code/highcharts-more.js"></script>
<script src="Highcharts-8.0.0/code/modules/exporting.js"></script>
<script src="Highcharts-8.0.0/code/modules/export-data.js"></script>
<script src="Highcharts-8.0.0/code/modules/accessibility.js"></script>

<?php
include 'menu.php';

include "koneksi.php";
$conn = koneksi();

$sql  = "SELECT * FROM mhs inner join detail_mhs on id_mhs = id_mhs_detail GROUP BY id_mhs";
$res  = mysqli_query($conn, $sql);
$data = array();
$n    = 0;
while ($val = mysqli_fetch_array($res)){
  $gender = "Perempuan";
  if($val["jenis_kelamin"] == "L"){
    $gender = "Laki-laki";
  }
  $data[$n][0] = $val['id_mhs'];
  $data[$n][1] = $val['npm_mhs'];
  $data[$n][2] = $val['nama_mhs'];
  $data[$n][3] = $gender;
  $data[$n][4] = $val['IPS1'];
  $data[$n][5] = $val['IPS2'];
  $data[$n][6] = $val['IPS3'];
  $data[$n][7] = $val['IPS4'];
  $data[$n][8] = $val['IPS5'];
  $data[$n][9] = $val['IPS6'];
  $data[$n][10] = $val['IPS7'];
  $data[$n][11] = $val['sks_lulus'];
  $data[$n][12] = $val['status_tamat'];
  $n++;
}
?>

<style media="screen">
.table {
  font-size: 12px !important;
}
</style>
<div class="container">
  <h3>K-NN AKURASI PREDIKSI DATA SAMPEL</h3>
  <table class="table table-bordered table-striped" id="data">
    <thead>
      <tr>
        <th>No</th>
        <th>NPM</th>
        <th>MAHASISWA</th>
        <th>JK</th>
        <th>IPS1</th>
        <th>IPS2</th>
        <th>IPS3</th>
        <th>IPS4</th>
        <th>IPS5</th>
        <th>IPS6</th>
        <th>IPS7</th>
        <th>SKS LULUS</th>
        <th>STATUS</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($data as $key => $value) {
        $sts = "Tidak Tepat";
        $tepatge = "badge alert-danger";

        if($value[12] == 'Y'){
          $sts = "Tepat";
          $tepatge = "badge alert-success";
        }
        ?>
        <tr>
          <td><?php echo $no++?></td>
          <td><?php echo $value[1]?></td>
          <td><?php echo $value[2]?></td>
          <td><?php echo $value[3]?></td>
          <td><?php echo $value[4]?></td>
          <td><?php echo $value[5]?></td>
          <td><?php echo $value[6]?></td>
          <td><?php echo $value[7]?></td>
          <td><?php echo $value[8]?></td>
          <td><?php echo $value[9]?></td>
          <td><?php echo $value[10]?></td>
          <td><?php echo $value[11]?></td>
          <td><b class="<?php echo $tepatge ?>"><?php echo $sts?></b></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <figure class="highcharts-figure"><div id="container_sample"></div></figure>

  <hr>
  <h4>Prediksi Mahasiswa:</h4>
  <form class="form-horizontal" method="post">
    <div class="col-md-6">
      <select class="form-control select2" name="id_mhs" id="id_mhs">
        <option value="" readonly >Pilih Mahasiswa</option>
        <?php
        $sql  = "SELECT * FROM mhs_test inner join detail_mhs_test on id_mhs = id_mhs_detail GROUP BY id_mhs";
        $hasil = mysqli_query($conn, $sql);
        while ($r = mysqli_fetch_array($hasil)) {
          $gender = "Perempuan";
          if($r["jenis_kelamin"] == "L"){
            $gender = "Laki-laki";
          }
          ?>
          <option value="<?=$r['id_mhs']?>">
            <?php echo $r['nama_mhs']?> |
            <?php echo $r['IPS1']?> |
            <?php echo $r['IPS2']?> |
            <?php echo $r['IPS3']?> |
            <?php echo $r['IPS4']?> |
            <?php echo $r['IPS5']?> |
            <?php echo $r['IPS6']?> |
            <?php echo $r['IPS7']?> |
            <?php echo $r['sks_lulus']?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="col-sm-2">
      <!-- <label>Nilai K</label> -->
      <select class="form-control" name="rumus" required>
        <option value="">-- Pilih Rumus --</option>
        <option value="E">Euclidian Distance</option>
        <option value="M">Manhattan Distance</option>
      </select>
    </div>
    <div class="col-sm-1">
      <!-- <label>Nilai K</label> -->
      <!-- <select class="form-control" name="K" required>
      <option value="">K</option>
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="5">5</option>
      <option value="10">10</option>
    </select> -->
    <input type="text" name="K" class="form-control" placeholder="nilai K">
  </div>
  <div class="col-sm-2">
    <button type="submit" name="hitung" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> HITUNG</button>
  </div>

</form>

<br/><br />

<?php
if(isset($_POST['hitung'])):

  $K = $_POST['K'];

  mysqli_query($conn,"DROP TABLE RangkingSementaraMhs");
  mysqli_query($conn,"CREATE TABLE RangkingSementaraMhsMulti(
    Rangking int AUTO_INCREMENT primary key,
    Nama varchar(200),
    IPS1 Decimal(10,2),
    IPS2 Decimal(10,2),
    IPS3 Decimal(10,2),
    IPS4 Decimal(10,2),
    IPS5 Decimal(10,2),
    IPS6 Decimal(10,2),
    IPS7 Decimal(10,2),
    Sks_lulus int(5),
    Status varchar(1),
    Distance Decimal(10,4));
    Step int(5));
    ");

    mysqli_query($conn,"CREATE TABLE HasilMhs(
      id int AUTO_INCREMENT primary key,
      Nama varchar(200),
      IPS1 Decimal(10,2),
      IPS2 Decimal(10,2),
      IPS3 Decimal(10,2),
      IPS4 Decimal(10,2),
      IPS5 Decimal(10,2),
      IPS6 Decimal(10,2),
      IPS7 Decimal(10,2),
      Sks_lulus int(5),
      Status varchar(1));
      ");

      function substr_count_array($haystack, $needle){
        $initial = 0;
        $bits_of_haystack = explode(' ', $haystack);
        foreach ($needle as $substring) {
          if(!in_array($substring, $bits_of_haystack))
          continue;
          $initial += substr_count($haystack, $substring);
        }
        return $initial;
      }


      $sql_mhs  = "SELECT * FROM mhs inner join detail_mhs on id_mhs = id_mhs_detail GROUP BY id_mhs";
      $rest = mysqli_query($conn, $sql_mhs);
      $jlh_test = mysqli_num_rows($rest);
      echo "JLH :".$jlh_test;
      $n = 0;
      while ($row = mysqli_fetch_array($rest)){
        $data_test[$n][0] = $val['id_mhs'];
        $data_test[$n][1] = $val['npm_mhs'];
        $data_test[$n][2] = $val['nama_mhs'];
        $data_test[$n][3] = $gender;
        $data_test[$n][4] = $val['IPS1'];
        $data_test[$n][5] = $val['IPS2'];
        $data_test[$n][6] = $val['IPS3'];
        $data_test[$n][7] = $val['IPS4'];
        $data_test[$n][8] = $val['IPS5'];
        $data_test[$n][9] = $val['IPS6'];
        $data_test[$n][10] = $val['IPS7'];
        $data_test[$n][11] = $val['sks_lulus'];
        $n++;
      }

      $tepat = 0;
      $tidak = 0;

      for ($i=0; $i < $jlh_test; $i++) {

        //Training Data
        foreach ($data as $key => $value) {
          $Name = $value[2];
          $IPS1 = $value[4];
          $IPS2 = $value[5];
          $IPS3 = $value[6];
          $IPS4 = $value[7];
          $IPS5 = $value[8];
          $IPS6 = $value[9];
          $IPS7 = $value[10];
          $SKS  = $value[11];
          $sts  = $value[12];

          //variabel
          $test_nama = $data_test[$i][2];
          $test_IPS1 = $data_test[$i][4];
          $test_IPS2 = $data_test[$i][5];
          $test_IPS3 = $data_test[$i][6];
          $test_IPS4 = $data_test[$i][7];
          $test_IPS5 = $data_test[$i][8];
          $test_IPS6 = $data_test[$i][9];
          $test_IPS7 = $data_test[$i][10];
          $test_SKS  = $data_test[$i][11];

          if ($_POST['rumus'] == 'E') {
            //Euclidian Distance
            $Distance = sqrt(
              pow($IPS1 - $test_IPS1, 2) + pow($IPS2 - $test_IPS2, 2) +
              pow($IPS3 - $test_IPS3, 2) + pow($IPS4 - $test_IPS4, 2) +
              pow($IPS5 - $test_IPS5, 2) + pow($IPS6 - $test_IPS6, 2) +
              pow($IPS7 - $test_IPS7, 2)
              + pow($SKS - $test_SKS, 2)
            );
            $rumus = "Euclidian Distance";
          }else {
            //Manhattan Distance
            $Distance = (
              $IPS1 - $test_IPS1 +
              $IPS2 - $test_IPS2 +
              $IPS3 - $test_IPS3 +
              $IPS4 - $test_IPS4 +
              $IPS5 - $test_IPS5 +
              $IPS6 - $test_IPS6 +
              $IPS7 - $test_IPS7
              + $SKS - $test_SKS
            );
            $rumus = "Manhattan Distance";
          }

          $Name = $conn->real_escape_string($Name);
          mysqli_query($conn,"INSERT INTO RangkingSementaraMhsMulti (Nama, IPS1, IPS2, IPS3, IPS4, IPS5, IPS6, IPS7, Sks_lulus, Status, Distance, Step)
          VALUES ('$Name','$IPS1','$IPS2','$IPS3','$IPS4','$IPS5','$IPS6','$IPS7','$SKS','$sts','$Distance','$i')") or die(mysqli_error($conn));
        } //akhir foreach

        $sql_ = "SELECT * FROM RangkingSementaraMhsMulti WHERE Step = '$i' ORDER BY Distance ASC LIMIT $K";
        $res_  = mysqli_query($conn, $sql_);
        //isi nilai STATUS
        while ($val_ = mysqli_fetch_array($res_)){
          if ($val_['Status'] == 'Y') {
            $r[] = $tepat ++;
          }
          if ($val_['Status'] == 'T') {
            $r[] = $tidak ++;
          }
          $r[] = $val_['Status'];
        }

        //isi nilai hasil berdasarkan nilai K
        if($K == 1) {
          $hasil = $r[0];
        }else if($K == 2){
          $hasil = $r[0];
        }else{
          if ($Tepat > $Tidak) {
            $hasil = 'Y';
          }else{
            $hasil = 'T';
          }
        }

        //INSERT HASIL ==========
        $test_nama = $data_test[$i][2];
        $test_IPS1 = $data_test[$i][4];
        $test_IPS2 = $data_test[$i][5];
        $test_IPS3 = $data_test[$i][6];
        $test_IPS4 = $data_test[$i][7];
        $test_IPS5 = $data_test[$i][8];
        $test_IPS6 = $data_test[$i][9];
        $test_IPS7 = $data_test[$i][10];
        $test_SKS  = $data_test[$i][11];
        $hasil_final = $hasil;

        mysqli_query($conn,"INSERT INTO HasilMhs (Nama, IPS1, IPS2, IPS3, IPS4, IPS5, IPS6, IPS7, Sks_lulus, Status)
        VALUES ('$test_nama, '$test_IPS1', '$test_IPS2', '$test_IPS3', '$test_IPS4', '$test_IPS5', '$test_IPS6', '$test_IPS7', '$test_SKS', '$hasil_final')");

        // $dataHasil['acid'] = $Acid_final;
        // $dataHasil['strength'] = $Strength_final;
        // $dataHasil['class'] = $Class_final;

        // echo "<pre>";
        // print_r($dataHasil);
        // echo "</pre>";
        // $r[] = "";
        unset($r);
        $r = array();

      } //akhir for
      ?>
      <hr>
      <h2>Hasil Prediksi : K = <?php echo $K; ?> | <small>dengan Rumus : <i style="color:blue;"><?=$rumus?></i></small></h2>
      <table class="table table-bordered table-striped table-sm" id="data2">
        <thead>
          <tr>
            <th>Rank</th>
            <th>Nama</th>
            <th>IPS1</th>
            <th>IPS2</th>
            <th>IPS3</th>
            <th>IPS4</th>
            <th>IPS5</th>
            <th>IPS6</th>
            <th>IPS7</th>
            <th>SKS</th>
            <th>Status</th>
            <th>Distance</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM HasilMhs ORDER BY id";
          $res  = mysqli_query($conn, $sql);
          $n = 1;
          while ($val = mysqli_fetch_array($res)){
            $color = '';
            if($n <= $K){ $color = "info"; }
            $sts   = "Tidak Tepat";
            $tepatge = "badge alert-danger";
            if($val['Status'] == 'Y'){
              $sts   = "Tepat";
              $tepatge = "badge alert-success";
            }
            ?>
            <tr class="<?php echo $color; ?>">
              <td><?php echo $n++;?></td>
              <td><?php echo $val['Nama'];?></td>
              <td><?php echo $val['IPS1'];?></td>
              <td><?php echo $val['IPS2'];?></td>
              <td><?php echo $val['IPS3'];?></td>
              <td><?php echo $val['IPS4'];?></td>
              <td><?php echo $val['IPS5'];?></td>
              <td><?php echo $val['IPS6'];?></td>
              <td><?php echo $val['IPS7'];?></td>
              <td><?php echo $val['Sks_lulus'];?></td>
              <td><b class="badge <?php echo $tepatge ?>"><?php echo $sts ?></b></td>
              <td><?php echo $val['Distance'];?></td>
            </tr>
            <?php
          }
          ?>
        </tbody>
      </table>

      <figure class="highcharts-figure"><div id="container"></div></figure>

    <?php endif; ?>
  </div>

</body>
</html>

<?php include "vendor.php"; ?>
<script type="text/javascript">
$(document).ready(function(){
  $('#custom_box').hide();
  $('#data').DataTable();
  $('#data2').DataTable();
  $('.select2').select2();
  $("html, body").animate({ scrollTop: $(document).height() }, 1500);

  //CHART SAMPLE
  Highcharts.chart('container_sample', {
    chart: {
      type: 'scatter',
      zoomType: 'xy'
    },
    title: {
      text: 'SEBARAN DATA SAMPEL'
    },
    subtitle: {
      text: 'Data Mahasiswa Fakultas Teknik Stambuk 2015'
    },
    xAxis: {
      title: {
        enabled: true,
        text: 'Posisi'
      },
      startOnTick: true,
      endOnTick: true,
      showLastLabel: true
    },
    yAxis: {
      title: {
        text: 'Distance'
      }
    },
    legend: {
      layout: 'vertical',
      align: 'left',
      verticalAlign: 'bottom',
      x: 100,
      y: 70,
      floating: true,
      backgroundColor: Highcharts.defaultOptions.chart.backgroundColor,
      borderWidth: 1
    },
    plotOptions: {
      scatter: {
        marker: {
          radius: 5,
          states: {
            hover: {
              enabled: true,
              lineColor: 'rgb(100,100,100)'
            }
          }
        },
        states: {
          hover: {
            marker: {
              enabled: false
            }
          }
        },
        tooltip: {
          headerFormat: '<b>{series.name}</b><br>',
          pointFormat: '{point.x}, {point.y}'
        }
      }
    },
    series: [{
      name: 'TEPAT WAKTU',
      color: 'rgba(0, 230, 64, 0.9)',
      data: [
        <?php
        $sql1 = "SELECT * FROM mhs inner join detail_mhs on id_mhs = id_mhs_detail WHERE status_tamat = 'Y' GROUP BY id_mhs";
        $res1  = mysqli_query($conn, $sql1);
        while ($val1 = mysqli_fetch_array($res1)){
          $sum1 = $val1['IPS1'] + $val1['IPS2'] + $val1['IPS3'] + $val1['IPS4'] + $val1['IPS5'] + $val1['IPS6'] + $val1['IPS7'] ;
          $sum1 = $sum1;
          $data_Y2[] = '['.$sum1.']';
        }
        echo implode(', ',$data_Y2);
        ?>
      ]
    },
    {
      name: 'TIDAK TEPAT WAKTU',
      color: 'rgba(225, 0, 0, 0.9)',
      data: [
        <?php
        $sql = "SELECT * FROM mhs inner join detail_mhs on id_mhs = id_mhs_detail WHERE status_tamat = 'T' GROUP BY id_mhs";
        $res  = mysqli_query($conn, $sql);
        while ($val = mysqli_fetch_array($res)){
          $sum = $val['IPS1'] + $val['IPS2'] + $val['IPS3'] + $val['IPS4'] + $val['IPS5'] + $val['IPS6'] + $val['IPS7'];
          $sum = $sum;
          $data_T2[] = '['.$sum.']';
        }
        echo implode(', ',$data_T2);
        ?>
      ]
    }]
  });


  //CHART DISTANCE
  Highcharts.chart('container', {
    chart: {
      type: 'scatter',
      zoomType: 'xy'
    },
    title: {
      text: 'Posisi Distance / jarak Ketetanggaan dari 200 Data Sampel Mahasiswa'
    },
    subtitle: {
      text: 'Data Mahasiswa Fakultas Teknik Stambuk 2015'
    },
    xAxis: {
      title: {
        enabled: true,
        text: 'Posisi'
      },
      startOnTick: true,
      endOnTick: true,
      showLastLabel: true
    },
    yAxis: {
      title: {
        text: 'Distance'
      }
    },
    legend: {
      layout: 'vertical',
      align: 'left',
      verticalAlign: 'bottom',
      x: 100,
      y: 70,
      floating: true,
      backgroundColor: Highcharts.defaultOptions.chart.backgroundColor,
      borderWidth: 1
    },
    plotOptions: {
      scatter: {
        marker: {
          radius: 5,
          states: {
            hover: {
              enabled: true,
              lineColor: 'rgb(100,100,100)'
            }
          }
        },
        states: {
          hover: {
            marker: {
              enabled: false
            }
          }
        },
        tooltip: {
          headerFormat: '<b>{series.name}</b><br>',
          pointFormat: '{point.x}, {point.y}'
        }
      }
    },
    series: [{
      name: 'TEPAT WAKTU',
      color: 'rgba(0, 230, 64, 0.9)',
      data: [
        <?php
        $sql = "SELECT * FROM RangkingSementaraMhs WHERE Status  = 'Y' ORDER BY Distance ASC";
        $res  = mysqli_query($conn, $sql);
        while ($val = mysqli_fetch_array($res)){
          $data_Y[] = '['.$val['Distance'].']';
        }
        echo implode(', ',$data_Y);
        ?>
        <?php
        // $sql = "SELECT * FROM mhs inner join detail_mhs on id_mhs = id_mhs_detail WHERE status_tamat = 'Y' GROUP BY id_mhs";
        // $res  = mysqli_query($conn, $sql);
        // while ($val = mysqli_fetch_array($res)){
        //   $sum = $val['IPS1'] + $val['IPS2'] + $val['IPS3'] + $val['IPS4'] + $val['IPS5'] + $val['IPS6'] + $val['IPS7'] + $val['sks_lulus'];
        //   $sum = $sum / 8;
        //   $data_Y[] = '['.$sum.']';
        // }
        // echo implode(', ',$data_Y);
        ?>
      ]
    },
    {
      name: 'TIDAK TEPAT WAKTU',
      color: 'rgba(225, 0, 0, 0.9)',
      data: [
        <?php
        //$data_T = array();
        $sql = "SELECT * FROM RangkingSementaraMhs WHERE Status  = 'T' ORDER BY Distance ASC";
        $res  = mysqli_query($conn, $sql);
        while ($val = mysqli_fetch_array($res)){
          $data_T[] = '['.$val['Distance'].']';
        }
        echo implode(', ',$data_T);

        ?>
        <?php
        // $sql = "SELECT * FROM mhs inner join detail_mhs on id_mhs = id_mhs_detail WHERE status_tamat = 'T' GROUP BY id_mhs";
        // $res  = mysqli_query($conn, $sql);
        // while ($val = mysqli_fetch_array($res)){
        //   $sum = $val['IPS1'] + $val['IPS2'] + $val['IPS3'] + $val['IPS4'] + $val['IPS5'] + $val['IPS6'] + $val['IPS7'] + $val['sks_lulus'];
        //   $sum = $sum / 8;
        //   $data_T[] = '['.$sum.']';
        // }
        // echo implode(', ',$data_T);
        ?>
      ]
    }]
  });



});
</script>
