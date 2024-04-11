<?php 

function logout() {
    include_once('communication.php');
    doLogoutUser();
    header("Location: index.php?page=home");
}
