<?php
include "../config/db.php";

$today = date("Y-m-d");

mysqli_query($conn,
"DELETE FROM placements WHERE expiry_date < '$today'"
);
?>
