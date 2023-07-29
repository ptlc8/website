<?php

session_start();

unset($_SESSION['username'], $_SESSION['password']);

if (isset($_REQUEST['back'])) { ?>
<script>window.history.go(-1);</script>
<?php } else { ?>
<script>window.location.replace(".")</script>
<?php } ?>