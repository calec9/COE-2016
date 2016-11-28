<?php

// by country
if ( isset ( $_POST['country'] ) ) {

  echo $_POST['country'];
}

// by indicator
elseif ( isset ( $_POST['indicators'] ) ) {

  echo $_POST['indicator'];
}

// by trend
elseif ( isset ( $_POST['trend'] ) ) {

  echo $_POST['trend'];
}
?>
