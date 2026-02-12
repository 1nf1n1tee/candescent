<?php
session_start();
session_destroy();
header("Location: ../index.php"); // redirect to landing page
exit;
