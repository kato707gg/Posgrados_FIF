<?php
  session_start();
  session_unset();
  session_destroy();
  print('<META HTTP-EQUIV="REFRESH" CONTENT="1;URL=../Index/index.html"> </head>');
?>