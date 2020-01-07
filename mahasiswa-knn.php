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
  <h3>K-NN SINGLE PREDITION</h3>
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
  <hr>
  <h4>Prediksi Mahasiswa:</h4>
  <form class="form-horizontal" method="post">
    <div class="col-md-8">
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
    <div class="col-sm-1">
      <!-- <label>Nilai K</label> -->
      <select class="form-control" name="K" required>
        <option value="">K</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="5">5</option>
        <option value="10">10</option>
      </select>
    </div>
    <div class="col-sm-2">
      <button type="submit" name="hitung" class="btn btn-success">HITUNG</button>
    </div>

  </form>

  <?php
  if(isset($_POST['hitung'])):

    $id_mhs   = $_POST['id_mhs'];
    $K        = $_POST['K'];

    mysqli_query($conn,"DROP TABLE RangkingSementaraMhs");
    mysqli_query($conn,"CREATE TABLE RangkingSementaraMhs(
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
      ");

      $sql_mhs  = "SELECT * FROM mhs_test inner join detail_mhs_test on id_mhs = id_mhs_detail WHERE id_mhs = '$id_mhs' GROUP BY id_mhs";
      $rest = mysqli_query($conn, $sql_mhs);
      $row = mysqli_fetch_array($rest);
      $test_name = $row['nama_mhs'];
      $test_IPS1 = $row['IPS1'];
      $test_IPS2 = $row['IPS2'];
      $test_IPS3 = $row['IPS3'];
      $test_IPS4 = $row['IPS4'];
      $test_IPS5 = $row['IPS5'];
      $test_IPS6 = $row['IPS6'];
      $test_IPS7 = $row['IPS7'];
      $test_SKS  = $row['sks_lulus'];

      //Train Data
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
        $Distance = sqrt(
          pow($IPS1 - $test_IPS1, 2) + pow($IPS2 - $test_IPS2, 2) +
          pow($IPS3 - $test_IPS3, 2) + pow($IPS4 - $test_IPS4, 2) +
          pow($IPS5 - $test_IPS5, 2) + pow($IPS6 - $test_IPS6, 2) +
          pow($IPS7 - $test_IPS7, 2) + pow($SKS - $test_SKS, 2)
        );

        $Name = $conn->real_escape_string($Name);
        mysqli_query($conn,"INSERT INTO RangkingSementaraMhs (Nama, IPS1, IPS2, IPS3, IPS4, IPS5, IPS6, IPS7, Sks_lulus, Status, Distance)
        VALUES ('$Name','$IPS1','$IPS2','$IPS3','$IPS4','$IPS5','$IPS6','$IPS7','$SKS','$sts','$Distance')") or die(mysqli_error($conn));

      }
      ?>
      <hr>
      <h2>Hasil Prediksi : K = <?php echo $K; ?></h2>
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
          $sql = "SELECT * FROM RangkingSementaraMhs ORDER BY Distance";
          $res  = mysqli_query($conn, $sql);
          $n=1;
          while ($val = mysqli_fetch_array($res)){
            $color = '';
            if($n <= $K){ $color = "info"; }

            $sts = "Tidak Tepat";
            $badge = "badge alert-danger";

            if($val['Status'] == 'Y'){
              $sts = "Tepat";
              $badge = "badge alert-success";
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
              <td><b class="badge <?php echo $badge ?>"><?php echo $sts ?></b></td>
              <td><?php echo $val['Distance'];?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php
      $sql_r = "SELECT * FROM RangkingSementaraMhs ORDER BY Distance ASC LIMIT $K";
      $res_r  = mysqli_query($conn, $sql_r);
      $n=1;
      while ($valr = mysqli_fetch_array($res_r)){
        $r[] = $valr['Status'];
      }
      
      echo "<pre>";
      print_r($r);
      echo "</pre>";

      $toString = implode(' ', $r);
      $Good = array('Y');
      $Bad = array('T');

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

      $Good = substr_count_array($toString, $Good);
      $Bad  = substr_count_array($toString, $Bad);

      if($K==1) {
        $hasil = $r[0];
      }
      if($K==2){
        $hasil = $r[0];
      }else{
        if ($Good > $Bad) {
          $hasil = 'Y';
        }else{
          $hasil = 'T';
        }
      }

      if($hasil == 'Y') {
        $badge = "badge alert-success";
        $sts = "TEPAT";
      }else {
        $badge = "badge alert-danger";
        $sts = "TIDAK";
      }
      ?>
      <div class="alert alert-success">Kesimpulan Hasil Prediksi :
        <table class="table table-bordered table-striped">
          <tr>
            <th>NAMA</th>
            <th>IPS1</th>
            <th>IPS2</th>
            <th>IPS3</th>
            <th>IPS4</th>
            <th>IPS5</th>
            <th>IPS6</th>
            <th>IPS7</th>
            <th>SKS</th>
            <th>STATUS</th>
          </tr>
          <tr class="active">
            <td><b><?php echo $test_name ?></b></td>
            <td><b><?php echo $test_IPS1 ?></b></td>
            <td><b><?php echo $test_IPS2 ?></b></td>
            <td><b><?php echo $test_IPS3 ?></b></td>
            <td><b><?php echo $test_IPS4 ?></b></td>
            <td><b><?php echo $test_IPS5 ?></b></td>
            <td><b><?php echo $test_IPS6 ?></b></td>
            <td><b><?php echo $test_IPS7 ?></b></td>
            <td><b><?php echo $test_SKS ?></b></td>
            <td><b class="badge <?php echo $badge ?>"><?php echo $sts ?></b></td>
          </tr>
        </table>
      </div>
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
});
</script>
