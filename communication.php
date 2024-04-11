<?php 

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
        echo $current_email;
        echo "-----" . "\n";
        $current_pswd = $current_credentials[2];

        if ($current_email == $email && $current_pswd == $pswd . PHP_EOL) {
            return true;
        }
    }
    fclose($users);
    return false;
}

function getUserByEmail($email) {
    $users = fopen("users.txt", "r") or die("Unable to open file!");

    while (!feof($users)) {
        $current_credentials = explode("|", fgets($users));
        $current_email = $current_credentials[0];
        if ($current_email == $email) {
            return $current_credentials[1];
        }
    }
    fclose($users);
    return false;
}
