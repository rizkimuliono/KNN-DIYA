<?php require_once 'koneksi.php'; ?>

<!DOCTYPE html>
<html>
<head>
  <title>Metode KNN Dengan PHP - rizkimuliono.id</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>

  <!-- Full Width Column -->
  <div class="container" style="margin-top: 50px">

    <!-- Main content -->
    <div id="simpan"></div>

    <div class="col-md-12">
      <form class="form-horizontal" method="POST">
        <hr>
        <h3>Prediksi Ketepatan Kelulusan Mahasiswa Dengan Metode k-NN</h3>
        <hr>
        <div class="form-group">
          <label class="col-sm-6 control-label">Pilih Mahasiswa :</label>
          <div class="col-sm-10">
            <select class="form-control select2" name="id_mhs" id="id_mhs" required>
              <option value="" readonly >Pilih Mahasiswa</option>
              <?php
              $conn = koneksi();
              $sql  = "SELECT * FROM mhs_test inner join detail_mhs_test on mhs_test.id_mhs = detail_mhs_test.id_mhs_detail GROUP BY mhs_test.id_mhs";
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
                  <?php //echo $r['IPS8']?> |
                  <?php //echo $r['IPS9']?> |
                  <?php //echo $r['IPS10']?> |
                  <?php echo $r['sks_lulus']?>
                </option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Tetangga Terdekat :</label>
          <div class="col-sm-4">
            <select class="form-control select2" id="nilaik" name="nilaik" required>
              <option value="" readonly >Pilih</option>
              <option>5</option>
              <option>9</option>
              <option>13</option>
              <option>15</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6" style="margin-top: 20px;margin-bottom: 30px">
          <!-- <button type="submit" id="hitung" name="hitung" class="btn btn-primary">HITUNG</button> -->
          <div id="hitung" class="btn btn-primary">HITUNG</div>
        </div>
      </form>

      <div class="load"></div>
      <!-- Munculkan hasil -->
      <?php
      // if(isset($_POST["hitung"])){
      //   include_once('proses.php');
      // }
      ?>

    </div> <!-- /.col 7 -->
  </div> <!-- /.container -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

  <script>
  $(function () {
    $('.select2').select2()
  })

  $("#hitung").click(function () {
    var id = $('#id_mhs').val();
    var nilaik = $('#nilaik').val();
    $.ajax ({
      type: "POST",
      url: "proses.php",
      data: 'id_mhs=' + id + '&nilaik=' + nilaik,
      success: function (respons, data) {
        console.log(nilaik+status);
        $('.load').html(respons);
        $('html, body').animate({
          scrollTop : 0},1500);
          return false;
        },
        beforeSend:function () {
          $('.load').html('Loading...');
        }
      })
    })

    $("#button").click(function () {
      var id = $('#id_mhs').text();
      var nilaik = $('#nilaik').text();
      $.ajax ({
        type: "POST",
        url: "simpan.php",
        data: 'id_mhs=' + id + '&nilaik=' + nilaik +'&hasil_prediksi='+status,
        success: function (respons, data) {
          console.log(nilaik+status);
          $('#simpan').html(respons);
          $('html, body').animate({
            scrollTop : 0},1500);
            return false;
          }
        })
      })
      </script>

    </body>
    </html>
