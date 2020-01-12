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
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts-more.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

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
  <h3>DATA SAMPEL MAHASISWA TAHUN 2015</h3>
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
        $badge = "badge alert-danger";

        if($value[12] == 'Y'){
          $sts = "Tepat";
          $badge = "badge alert-success";
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
          <td><b class="<?php echo $badge ?>"><?php echo $sts?></b></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

  <figure class="highcharts-figure"><div id="container_sample"></div></figure>

  <br/>
  <br />
</div>

</body>
</html>

<?php include "vendor.php"; ?>
<script type="text/javascript">
$(document).ready(function(){
  $('#custom_box').hide();
  $('#data').DataTable();
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
      text: 'Data Mahasiswa Fakultas Teknik Stambuk 2016'
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
          $sum1 = $val1['IPS1'] + $val1['IPS2'] + $val1['IPS3'] + $val1['IPS4'] + $val1['IPS5'] + $val1['IPS6'] + $val1['IPS7'] + $val1['sks_lulus'];
          $sum1 = $sum1 / 8;
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
          $sum = $val['IPS1'] + $val['IPS2'] + $val['IPS3'] + $val['IPS4'] + $val['IPS5'] + $val['IPS6'] + $val['IPS7'] + $val['sks_lulus'];
          $sum = $sum / 8;
          $data_T2[] = '['.$sum.']';
        }
        echo implode(', ',$data_T2);
        ?>
      ]
    }]
  });


});
</script>
