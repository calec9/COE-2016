<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoE 2016</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Features-Blue.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu.css">
    <link rel="stylesheet" href="assets/css/Sidebar-Menu1.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<style>
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}

</style>
<?php

$mysqli = new mysqli( 'localhost', 'root', 'NewPassword', 'coe' );

if ( $mysqli->connect_error ) {
  die ('Error connecting to database.' );
}


?>

<body>
    <div class="features-blue">
        <div class="container">
            <div class="intro">
                <h2 class="text-center"><strong>Council of Europe 2016 CMPF Dashboard</strong> </h2>
                <p class="text-center">Data submitting and entry platform. </p>
            </div>
            <div class="row features">
                <div class="col-md-4 col-sm-6 item"><i class="glyphicon glyphicon-map-marker icon"></i>
                    <h3 class="name">Enter Country</h3>
                    <form action="record.php" method="post">
                        <hr>
                        <select name="country">
                          <?php
                          $query = $mysqli->query( 'select * from countries ORDER BY id' );
                          if ( $query->num_rows > 0 ) {
                            while ( $row = $query->fetch_assoc() ) {
                              echo '<option value="' . $row['id'] . '">' . $row['country'] . '</option>';
                            }
                          }
                          ?>
                        </select>
                </div>
                <div class="col-md-4 col-sm-6 item"><i class="glyphicon glyphicon-time icon"></i>
                    <h3 class="name">Enter Indicator</h3>
                        <hr>
                        <select name="indicator" style="width: 300px">
                          <?php
                          $query = $mysqli->query( 'select * from indicators_2016 ORDER BY id' );
                          if ( $query->num_rows > 0 ) {
                            while ( $row = $query->fetch_assoc() ) {
                              echo '<option value="' . $row['id'] . '">' . $row['cat'] . '.' . $row['subcat'] . ' - ' . $row['indicator'] . '</option>';
                            }
                          }
                          ?>
                        </select>
                </div>
                <div class="col-md-4 col-sm-6 item"><i class="glyphicon glyphicon-list-alt icon"></i>
                    <h3 class="name">Enter Trend</h3>
                        <hr>
                        <select name="trend">
                          <option value="0">Enabling - Improving</option>
                          <option value="1">Enabling - Stable</option>
                          <option value="2">Enabling - Deteriorating</option>
                          <option value="5">Disabling - Improving</option>
                          <option value="4">Disabling - Stable</option>
                          <option value="3">Disabling - Improving</option>
                        </select>
                </div>
                <div class="col-md-12 col-sm-6 item"><i class="glyphicon glyphicon-list-alt icon"></i>
                    <h3 class="name">Enter Situation</h3>
                        <hr>
                        <textarea class="form-control" name="situation"></textarea>
                        <button class="btn btn-default" type="submit">Submit</button>
                    </form>
                </div>
            </div>

            <?php

            if ( isset ( $_POST['situation'] ) ) {

              $country_id = $_POST['country'];
              $indic_id   = $_POST['indicator'];
              $situation  = $_POST['situation'];
              $trend      = $_POST['trend'];

              /* Sanitize and escape the POST variables here. */

              $query = $mysqli->query( "insert into trends_2016 (indicator_id, country_id, trend, situation) values ($indic_id, $country_id, $trend, '$situation')" );
              if ( $mysqli->query( $query ) === TRUE ) {
                echo 'Trend registered.';
              } else {
                echo 'Error.' . $mysqli->error;
              }
            }

            ?>

        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/Sidebar-Menu.js"></script>
</body>
</html>
