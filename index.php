<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CoE 2016</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Features-Blue.css">
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

$mysqli = new mysqli( 'localhost', 'root', 'golden1', 'coe' );

if ( $mysqli->connect_error ) {
  die ('Error connecting to database.' );
}


?>

<body>
    <div class="features-blue">
        <div class="container">
            <div class="intro">
                <h2 class="text-center">Council of Europe 2016 CMPF Dashboard</h2>
                <p class="text-center">Data review and query platform.</p>
            </div>
            <div class="row features">
              <!-- SELECTING COUNTRIES -->
                <div class="col-md-12 col-sm-6 item"><i class="glyphicon glyphicon-map-marker icon"></i>
                    <h3 class="name">Select Country</h3>
                    <p class="description">Select a country and leave both other fields empty to get a full country report.</p>
                    <form action="index.php" method="post">
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
                      <button type="submit">Submit</button>
                  </form>
                </div>
                <!-- SELECTING INDICATORS -->
                <div class="col-md-12 col-sm-6 item"><i class="glyphicon glyphicon-time icon"></i>
                    <h3 class="name">Select Indicator</h3>
                    <p class="description">Select a country and an indicator to see the countries performance with the selecter indicator.</p>
                    <form action="index.php" method="post">
                        <hr>
                        <select name="country_indic">
                          <?php
                          $query = $mysqli->query( 'select * from countries ORDER BY id' );
                          if ( $query->num_rows > 0 ) {
                            while ( $row = $query->fetch_assoc() ) {
                              echo '<option value="' . $row['id'] . '">' . $row['country'] . '</option>';
                            }
                          }
                          ?>
                        </select>
                        <select name="indicators" style="width: 250px">
                          <?php
                          $query = $mysqli->query( 'select * from indicators_2015 ORDER BY id' );
                          if ( $query->num_rows > 0 ) {
                            while ( $row = $query->fetch_assoc() ) {
                              echo '<option value="' . $row['id'] . '">' . $row['cat'] . '.' . $row['subcat'] . ' - ' . $row['indicator'] . '</option>';
                            }
                          }
                          ?>
                        </select>
                      <button type="submit">Submit</button>
                    </form>

                </div>
                <!-- SELECTING TRENDS -->
                <div class="col-md-12 col-sm-6 item"><i class="glyphicon glyphicon-list-alt icon"></i>
                    <h3 class="name">Select Trend</h3>
                    <p class="description">Select a country and a trend to see all indicators for which the countriy meets the minimum (or specific -- specify below) trend.</p>
                    <form action="index.php" method="post">
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
                      <button type="submit">Submit</button>
                  </form>
                </div>
            </div>

            <?php

            // by country
            if ( isset ( $_POST['country'] ) ) {

              $country_id = $_POST['country'];

              $query = $mysqli->query( "select country from countries where id='$country_id' limit 1");
              if ( $row = $query->fetch_assoc() ) {
                $country_name = $row['country'];
                echo '<div class="intro">';
                echo '<h2 class="text-center">' . $country_name . '</h2>';
                echo ' <p class="text-center">Country reports, trends and indicators. </p>';
                echo '</div>';
              }

              $query = $mysqli->query( "select * from indicators_2015 order by id" );
              echo '<table border="1"><tr>';
              while ( $row = $query->fetch_assoc() ) {
                echo '<td>' . $row['cat'] . '.' . $row['subcat'] . '</td>';
                echo '<td>' . $row['indicator'] . '</td>';
                echo '</tr>';
              }
              echo '</table><br>';
            }

            // by indicator asd
            if ( isset ( $_POST['indicators'] ) ) {

              $indicator_id = $_POST['indicators'];
              $country_id   = $_POST['country_indic'];

              $query = $mysqli->query( "select country from countries where id='$country_id' limit 1");
              if ( $row = $query->fetch_assoc() ) {
                $country_name = $row['country'];
              }
              $query->free();

              $query = $mysqli->query( "select * from indicators_2015 where id='$indicator_id' limit 1" );
              if ( $row = $query->fetch_assoc() ) {
                $cat       = $row['cat'];
                $subcat    = $row['subcat'];
                $indicator = $row['indicator'];

                echo '<div class="intro">';
                echo '<h2 class="text-center">Indicator ' . $cat . '.' . $subcat . ' for ' . $country_name . '</h2>';

                if ( $subcat == 0 ) {
                  $query = $mysqli->query( "select * from indicators_2015 where cat='$cat' order by id" );
                  if ( $query->num_rows > 0 ) {
                    echo '<table border="1"><tr>';
                    while ( $row = $query->fetch_assoc() )  {
                      echo '<td>' . $row['cat'] . '.' . $row['subcat'] . '</td>';
                      echo '<td>' . $row['indicator'] . '</td>';
                      $indicatorX = $row['id'];
                      $queryX = $mysqli->query( "select * from trends_2015 where country_id='$country_id' and indicator_id='$indicatorX' limit 1" );
                      if ( $queryX->num_rows > 0 ) {
                        while ( $rowX = $queryX->fetch_assoc() ) {
                          $trendNA = $rowX['trend'] != '99' ? : 'N/A'; // not working?
                          if ( $rowX['trend'] == '99' ) $trend_res = 'N/A';
                          else $trend_res = $rowX['trend'];
                          echo '<td>' . $trend_res . '</td>';
                        }
                      }
                      $queryX->free();
                      echo '</tr>';
                    }
                    $query->free();
                    echo '</table><br>';
                  }
                } else {
                  $query = $mysqli->query( "select * from trends_2015 where country_id='$country_id' and indicator_id='$indicator_id'" );
                  if ( $row = $query->fetch_assoc() ) {
                    $trendNA = $row['trend'] != '99' ? : 'N/A'; // not working?
                    if ( $row['trend'] == '99' ) $trend_res = 'N/A';
                    else $trend_res = $row['trend'];
                    echo ' <p class="text-center"><b>Situation</b>: ' . $row['situation'] . '
                           <br><b>Trend:</b> ' . $trend_res . ' </p>';
                  }
                  echo '</div>';
                }
              }
              $query->free();
            }

            // by trend
            else if ( isset ( $_POST['trend'] ) ) {

              echo $_POST['trend'];
            }
            ?>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
