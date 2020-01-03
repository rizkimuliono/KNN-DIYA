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

        if($value[11] == 1){
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

    <div class="col-sm-1">
      <label>Custom<input type="checkbox" name="custom" class="form-control" value="YA"></label>
    </div>
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

    <div id="custom_box">
      <div class="col-sm-1">
        <label>IPS1 <input type="number" name="IPS1" class="form-control" value="<?php if(isset($_POST['IPS1'])){echo $_POST['IPS1'];} ?>"></label>
      </div>
      <div class="col-sm-1">
        <label>IPS2 <input type="number" name="IPS2" class="form-control"></label>
      </div>
      <div class="col-sm-1">
        <label>IPS3 <input type="number" name="IPS3" class="form-control"></label>
      </div>
      <div class="col-sm-1">
        <label>IPS4 <input type="number" name="IPS4" class="form-control"></label>
      </div>
      <div class="col-sm-1">
        <label>IPS5 <input type="number" name="IPS5" class="form-control"></label>
      </div>
      <div class="col-sm-1">
        <label>IPS6 <input type="number" name="IPS6" class="form-control"></label>
      </div>
      <div class="col-sm-1">
        <label>IPS7 <input type="number" name="IPS7" class="form-control"></label>
      </div>
      <div class="col-sm-2">
        <label>SKS LULUS <input type="number" name="sks" class="form-control"></label>
      </div>
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

    $acid     = $_POST['acid'];
    $strength = $_POST['strength'];
    $K        = $_POST['K'];

    mysqli_query($conn,"TRUNCATE TABLE RangkingSementara");
    mysqli_query($conn,"CREATE TABLE RangkingSementara(
      Rangking int AUTO_INCREMENT primary key,
      Name varchar(50),
      Acid int(5),
      Strength int(5),
      Class varchar(10),
      Distance Decimal(10,4));
      ");

      foreach ($data as $key => $value) {
        $Name = $value[0];
        $Acid = $value[1];
        $Strength = $value[2];
        $Class = $value[3];
        $Distance = sqrt(pow($value[1]-$acid, 2) + pow($value[2] - $strength, 2));

        mysqli_query($conn,"INSERT INTO RangkingSementara (Name, Acid, Strength, Class, Distance)
        VALUES ('$Name','$Acid','$Strength','$Class','$Distance')");
      }
      ?>
      <hr>
      <h2>Hasil Prediksi : K = <?php echo $K; ?></h2>
      <table class="table table-bordered table-striped table-sm">
        <thead>
          <tr>
            <th>Rank</th>
            <th>Name</th>
            <th>Acid</th>
            <th>Strength</th>
            <th>Class</th>
            <th>Distance</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT * FROM RangkingSementara ORDER BY Distance";
          $res  = mysqli_query($conn, $sql);
          $n=1;
          while ($val = mysqli_fetch_array($res)){
            $color = '';
            if($n <= $K){ $color = "info";}
            if ($val['Class'] == 'Good') {
              $badge = "badge alert-success";
            }else {
              $badge = "badge alert-danger";
            }
            ?>
            <tr class="<?php echo $color; ?>">
              <td><?php echo $n++;?></td>
              <td><?php echo $val['Name'];?></td>
              <td><?php echo $val['Acid'];?></td>
              <td><?php echo $val['Strength'];?></td>
              <td><span class="<?php echo $badge ?>"><?php echo $val['Class'];?></span></td>
              <td><?php echo $val['Distance'];?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php
      $sql_r = "SELECT * FROM RangkingSementara ORDER BY Distance ASC LIMIT $K";
      $res_r  = mysqli_query($conn, $sql_r);
      $n=1;
      while ($valr = mysqli_fetch_array($res_r)){
        $r[] = $valr['Class'];
      }
      // print_r($r);
      $toString = implode(' ', $r);
      $Good = array('Good');
      $Bad = array('Bad');

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

      if($K=1) {
        $hasil = $r[0];
      }
      if($K=2){
        $hasil = $r[0];
      }else{
        if ($Good > $bad) {
          $hasil = 'Good';
        }else{
          $hasil = 'Bad';
        }
      }

      if($hasil == 'Good') {
        $badge = "badge alert-success";
      }else {
        $badge = "badge alert-danger";
      }
      ?>
      <div class="alert alert-success">Kesimpulan Hasil Prediksi :
        <table class="table table-bordered table-striped">
          <tr>
            <th>Acid</th>
            <th>Strength</th>
            <th>Kesimpulan</th>
          </tr>
          <tr class="active">
            <td><b><?php echo $acid ?></b></td>
            <td><b><?php echo $strength ?></b></td>
            <td><b class="<?php echo $badge ?>"><?php echo $hasil; ?></b></td>
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
  $('.select2').select2();
  $("html, body").animate({ scrollTop: $(document).height() }, 1500);
});
</script>
