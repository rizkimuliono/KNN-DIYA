<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <title>KNN SAMPLE</title>
  <style media="screen">
  .table {
    font-size: 12px !important;
  }
  </style>
</head>
<body>
  <?php
  $data = array(
    array('Type-1',7,7,'Bad'),
    array('Type-2',7,4,'Bad'),
    array('Type-3',3,4,'Good'),
    array('Type-4',1,4,'Good'),
    array('Type-5',5,5,'Bad'),
    array('Type-6',6,6,'Bad'),
    array('Type-7',1,2,'Good')
  );

  ?>
  <div class="container">
    <h2>K-NN</h2>
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Acid</th>
          <th>Strength</th>
          <th>Class</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data as $key => $value) {?>
          <tr>
            <td><?php echo $value[0]?></td>
            <td><?php echo $value[1]?></td>
            <td><?php echo $value[2]?></td>
            <td><?php echo $value[3]?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
    <hr>
    <h2>Prediksi :</h2>
    <form class="form-horizontal" method="post">
      <div class="col-sm-2">
        <label>Acid <input type="number" name="acid" class="form-control" value="<?php if(isset($_POST['acid'])){echo $_POST['acid'];} ?>"></label>
      </div>
      <div class="col-sm-2">
        <label>Strength <input type="number" name="strength" class="form-control" value="<?php if(isset($_POST['strength'])){echo $_POST['strength'];} ?>"></label>
      </div>
      <div class="col-sm-2">
        <label>Nilai K</label>
        <select class="form-control" name="K" required>
          <option value="">-Nilai K-</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="5">5</option>
          <option value="10">10</option>
        </select>
      </div>
      <div class="col-sm-2"><br />
        <button type="submit" name="hitung" class="btn btn-success">PROSES</button>
      </div>

    </form>

    <?php
    if(isset($_POST['hitung'])):
      $acid     = $_POST['acid'];
      $strength = $_POST['strength'];
      $K        = $_POST['K'];

      include "koneksi.php";
      $conn   = koneksi();
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
    $("html, body").animate({ scrollTop: $(document).height() }, 1500);
  });
  </script>
