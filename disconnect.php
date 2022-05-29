<?php

session_start();

unset($_SESSION['username'], $_SESSION['password']);

echo isset($_REQUEST['back'])?'<script>window.history.go(-1);</script>':'<script>window.location.replace(".")</script>';

?>