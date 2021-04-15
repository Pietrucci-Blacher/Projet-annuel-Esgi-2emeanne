<?php
/* Les données PUT arrivent du flux */
$putdata = fopen("php://input", "r");
if (isset($putdata)) {
  echo "JE SUIS LA";
}

fclose($putdata);

?>
