<?php 
session_start();

// $_SESSION["login"] = true;
// $_SESSION["username"] = "frans";
// var_dump($_SESSION);

// function test() {
//     if ($_SESSION["login"]) {
//         echo $_SESSION["username"] . "is locked in" . PHP_EOL;
//     }
//     else {
//         echo "logging out";
//         session_unset();
//         session_destroy();
//     }
// }

// test();
// $_SESSION["login"] = false;
// test();
// var_dump($_SESSION);

function addAccount($credentials) {
    // TODO: wat is "or" keyword
    $users = fopen("users.txt", "a") or die("Unable to open file!");
    
    $registration = [$credentials["email"], $credentials["username"] , $credentials["pswd"]];
    fwrite($users, implode("|", $registration) . PHP_EOL);
    fclose($users);
}

function doesEmailExist($email) {
    // TODO: wat is "or" keyword
    $users = fopen("users.txt", "r") or die("Unable to open file!");

    while (!feof($users)) {
        $current_credentials = explode("|", fgets($users));
        $current_email = $current_credentials[0];
        if ($current_email == $email) {
            return true;
        }
    }
    fclose($users);
    return false;
}

function authenticateUser($email, $pswd) {
    $users = fopen('users.txt', "r") or die("Unable to open file!");

    while(!feof($users)) {
        $current_credentials = explode("|", fgets($users));
        $current_email = $current_credentials[0];
        $current_pswd = $current_credentials[2];

        if ($current_email == $email && $current_pswd == $pswd) {
            return true;
        }
    }
    fclose($users);
    return false;
}

?>