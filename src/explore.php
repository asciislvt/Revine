<?php

require_once __DIR__ . "../api/utils/DbConnection.php";

use Revine\DbConnection;

$db = DbConnection::getInstance()->getConnection();
$query = "SELECT * FROM videos ORDER BY upload_time DESC";

?>
<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <h1>Explore Page</h1>
    <p>This is where the explore page will be displayed.</p>
  </body>
</html>
