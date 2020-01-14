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
            <a href="index.php" class="btn btn-info"><i class="glyphicon glyphicon-home"></i>  Home</a>
          </div>

          <div class="navbar-form navbar-left">
            <div class="dropdown">
              <div class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i>  PREDIKSI
                <span class="caret"></span></div>
                <ul class="dropdown-menu">
                  <li><a href="mahasiswa-knn.php"><i class="glyphicon glyphicon-arrow-right"></i> Prediksi Per Mahasiswa</a></li>
                  <li><a href="mahasiswa-knn-custom.php"><i class="glyphicon glyphicon-arrow-right"></i> Prediksi Per Custom</a></li>
                  <li><a href="#"><i class="glyphicon glyphicon-arrow-right"></i> Prediksi Per Multi</a></li>
                  <li><hr /></li>
                    <li><a href="mahasiswa-knn-akurasi1.php"><i class="glyphicon glyphicon-arrow-right"></i> Akurasi Data Sampel</a></li>
                </ul>
              </div>
            </div>

            <div class="navbar-form navbar-left">
              <div class="dropdown">
                <div class="btn btn-success dropdown-toggle" data-toggle="dropdown">PREDIKSI KNN
                  <span class="caret"></span></div>
                  <ul class="dropdown-menu">
                    <li><a href='test-knn.php'>KNN Single</a></li>
                    <li><a href='test-knn-multi.php'>KNN Multi</a></li>
                  </ul>
                </div>
              </div>

            <div class="navbar-form navbar-left">
              <div class="dropdown">
                <div class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-hdd"></i> Master Data
                  <span class="caret"></span></div>
                  <ul class="dropdown-menu">
                    <li><a href="data_mhs_training.php"><i class="glyphicon glyphicon-hdd"></i> Data Mahasiswa (Training)</a></li>
                    <li><a href="data_mhs_testing.php"><i class="glyphicon glyphicon-hdd"></i> Data mahasiswa (Testing)</a></li>
                  </ul>
                </div>
              </div>

              <div class="navbar-form navbar-right">
                <a href="#!" class="btn btn-info"><i class="glyphicon glyphicon-info-sign"></i> ABOUT</a>
              </div>

            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
