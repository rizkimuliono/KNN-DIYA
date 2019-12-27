<!DOCTYPE html>
<html>
<head>
  <title>Metode KNN Dengan PHP - rizkimuliono.id</title>
  <style media="screen">
  #multiWrapper {
    width: 300px;
    margin: 25px 0 0 25px;
  }

  .def-cursor {
    cursor: default;
  }
  </style>
</head>
<body>

  <nav class="navbar alert-info">
    <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

          <div class="navbar-form navbar-left">
            <a href="index.php" class="btn btn-info">Home</a>
          </div>

          <div class="navbar-form navbar-left">
            <div class="dropdown">
              <div class="btn btn-primary dropdown-toggle" data-toggle="dropdown">PREDIKSI
                <span class="caret"></span></div>
                <ul class="dropdown-menu">
                  <li><a href="prediksi_mahasiswa.php">Prediksi Per Mahasiswa</a></li>
                  <li><a href="prediksi_prodi.php">Prediksi Per Prodi</a></li>
                </ul>
              </div>
            </div>

            <div class="navbar-form navbar-left">
              <div class="dropdown">
                <div class="btn btn-warning dropdown-toggle" data-toggle="dropdown">Master Data
                  <span class="caret"></span></div>
                  <ul class="dropdown-menu">
                    <li><a href="data_mhs_training.php">Data Mahasiswa (Training)</a></li>
                    <li><a href="data_mhs_testing.php">Data mahasiswa (Testing)</a></li>
                  </ul>
                </div>
              </div>

              <div class="navbar-form navbar-right">
                <a href="" class="btn btn-info">ABOUT</a>
              </div>

            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
