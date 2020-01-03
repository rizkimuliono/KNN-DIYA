<?php
include 'menu.php';

include "koneksi.php";
$conn = koneksi();

$sql  = "SELECT * FROM Acid_test";
$res  = mysqli_query($conn, $sql);
$data = array();
$n    = 0;
while ($val = mysqli_fetch_array($res)){
  $data[$n][0] = $val['ac_name'];
  $data[$n][1] = $val['ac_acid'];
  $data[$n][2] = $val['ac_strenght'];
  $data[$n][3] = $val['ac_class'];
  $n++;
}
?>

<style media="screen">
.table {
  font-size: 12px !important;
}
</style>

<div class="container">
  <h2>K-NN MULTI PREDICTION</h2>
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

    <div class="input_fields_wrap">
      <div class="row">
        <div class="col-sm-2">
          <label>Acid :<input type="number" name="acid[]" class="form-control"></label>
        </div>
        <div class="col-sm-2">
          <label>Strength :<input type="number" name="strength[]" class="form-control"></label>
        </div>
        <div class="col-sm-1"><br />
          <div class="add_field_button btn btn-sm btn-primary">+</div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-2">
        <label>Nilai K</label>
        <select class="form-control" name="K[]" required>
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
    </div>

  </form>
  <br />
  <?php
  if(isset($_POST['hitung'])) {

    $data_uji = $_POST;
    // print_r($data_uji);
    $jlh = count($data_uji['acid']);

    mysqli_query($conn, "TRUNCATE TABLE RangkingSementaraMulti");
    mysqli_query($conn, "TRUNCATE TABLE Hasil");

    mysqli_query($conn,"CREATE TABLE RangkingSementaraMulti(
      Rangking int AUTO_INCREMENT primary key,
      Name varchar(50),
      Acid int(5),
      Strength int(5),
      Class varchar(10),
      Distance Decimal(10,4),
      Step int(5));
      ");

      mysqli_query($conn,"CREATE TABLE Hasil(
        id int AUTO_INCREMENT primary key,
        Acid int(5),
        Strength int(5),
        Class varchar(10));
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

        $Bad = 0;
        $Good = 0;

        for ($i=0; $i < $jlh; $i++) {

          $K = $data_uji['K'][0]; //ambil nilai K dari POST['K']

          foreach ($data as $key => $value) {
            $Name     = $value[0];
            $Acid     = $value[1];
            $Strength = $value[2];
            $Class    = $value[3];
            $Distance = sqrt(pow($value[1]-$data_uji['acid'][$i], 2) + pow($value[2] - $data_uji['strength'][$i], 2));
            // echo "<br>";
            mysqli_query($conn,"INSERT INTO RangkingSementaraMulti (Name, Acid, Strength, Class, Distance, Step)
            VALUES ('$Name','$Acid','$Strength','$Class','$Distance','$i')");
          }

          // echo $sql_Good = "SELECT Class FROM RangkingSementaraMulti WHERE Step = '$i' AND Class = 'Good' ORDER BY Distance ASC LIMIT $K";
          // $res_Good  = mysqli_query($conn, $sql_Good);
          // $valGood = mysqli_num_rows($res_Good);
          // echo "<br>Good : ".$Good = $valGood;
          // //echo "<br>";
          //
          // $sql_Bad = "SELECT Class FROM RangkingSementaraMulti WHERE Step = '$i' AND Class = 'Bad' ORDER BY Distance ASC LIMIT $K";
          // $res_Bad  = mysqli_query($conn, $sql_Bad);
          // $valBad = mysqli_num_rows($res_Bad);
          // echo "<br>Bad : ".$Bad = $valBad;
          // echo "<br>";

          $sql_ = "SELECT * FROM RangkingSementaraMulti WHERE Step = '$i' ORDER BY Distance ASC LIMIT $K";
          $res_  = mysqli_query($conn, $sql_);
          while ($val_ = mysqli_fetch_array($res_)){
            if ($val_['Class'] == 'Bad') {
              $r[] = $Bad ++;
            }

            if ($val_['Class'] == 'Good') {
              $r[] = $Good ++;
            }

            $r[] = $val_['Class'];
          }
          // echo "<pre>";
          // print_r($r);
          // echo "</pre>";
          // echo $r[1];
          // echo "<br>";

          // $toString = implode(' ', $r);
          // $Good = array('Good');
          // $Bad = array('Bad');
          //
          // echo 'Good : '.$Good[$i] = substr_count_array($toString, $Good);
          // echo 'Bad : '.$Bad  = substr_count_array($toString, $Bad);

          if($K==1) {
            $hasil = $r[0];
          }else if($K==2){
            $hasil = $r[0];
          }else{

            if ($Good > $Bad) {
              $hasil = 'Good';
            }else{
              $hasil = 'Bad';
            }

          }

          //INSERT HASIL ==========
          $Acid_final     = $data_uji['acid'][$i];
          $Strength_final = $data_uji['strength'][$i];
          $Class_final    = $hasil;

          mysqli_query($conn,"INSERT INTO Hasil (Acid, Strength, Class)
          VALUES ('$Acid_final','$Strength_final','$Class_final')");

          $dataHasil['acid'] = $Acid_final;
          $dataHasil['strength'] = $Strength_final;
          $dataHasil['class'] = $Class_final;

          // echo "<pre>";
          // print_r($dataHasil);
          // echo "</pre>";
          // $r[] = "";
          unset($r);
          $r = array();
        }
        ?>
        <hr>
        <h2>Hasil Prediksi : K = <?php echo $_POST['K'][0]; ?></h2>

        <div class="alert alert-success">Kesimpulan Hasil Prediksi :
          <table class="table table-bordered table-striped">
            <tr>
              <th>Acid</th>
              <th>Strength</th>
              <th>Kesimpulan</th>
            </tr>
            <?php
            $sql_hasil = "SELECT * FROM Hasil ORDER BY id ASC";
            $res_hasil  = mysqli_query($conn, $sql_hasil);
            $n=1;
            while ($rowhasil = mysqli_fetch_array($res_hasil)){

              if($rowhasil['Class'] == 'Good') {
                $badge = "badge alert-success";
              }else {
                $badge = "badge alert-danger";
              }
              ?>
              <tr class="active">
                <td><b><?php echo $rowhasil['Acid'] ?></b></td>
                <td><b><?php echo $rowhasil['Strength'] ?></b></td>
                <td><b class="<?php echo $badge ?>"><?php echo $rowhasil['Class']; ?></b></td>
              </tr>
            <?php } ?>
          </table>
        </div>

      <?php } ?>

    </div>

  </body>
  </html>

  <?php include "vendor.php"; ?>
  <script type="text/javascript">
  $(document).ready(function(){
    var wrapper     = $(".input_fields_wrap");
    var add_button  = $(".add_field_button");

    var x = 1;
    $(add_button).click(function(e){
      e.preventDefault();
      x++;
      $(wrapper).append('<div class="row"><div class="col-sm-2"><label>Acid :<input type="number" name="acid[]" class="form-control"></label></div><div class="col-sm-2"><label>Strength :<input type="number" name="strength[]" class="form-control"></label></div><br><a href="#" class="remove_field btn btn-sm btn-danger">X</a></div>');

    });

    $(wrapper).on("click",".remove_field", function(e){
      e.preventDefault(); $(this).parent('div').remove(); x--;
    })

    $("html, body").animate({ scrollTop: $(document).height() }, 1500);
  });
  </script>
