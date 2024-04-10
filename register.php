<?php 

function getRegisterTitle() {
    return "Registration";
}

function showRegisterStart() {
    echo '<p class="content">Registreer je door het volgende formulier in te vullen:</p>';
    echo '<form method="POST" action="'; echo htmlspecialchars($_SERVER['PHP_SELF']); echo '">';
}

function showRegisterField($fieldName, $label, $type, $vald_vals_errs, $placeholder=NULL) {
        $values = $vald_vals_errs["values"];
        $errors = $vald_vals_errs["errors"];
    
        echo '<div>';
        echo '<label for="' . $fieldName . '">' . $label . ': </label>';
    
        switch ($type) {
            case "text":
            case "tel":
            case "number":
            case "password":
                echo '<input type="' . $type . '" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $values[$fieldName] . '" placeholder="' . $placeholder . '">';
                echo '<span class="error">' . $errors[$fieldName] . '</span>';
                break;
            }
        echo '</div>';
}

function showRegisterEnd() {
    echo '<input type="hidden" id="page" name="page" value="register">';
    echo '<input type="submit" value="Registreer">';
    echo '</form>';
}


function showRegisterContent($vald_vals_errs) {
    showRegisterStart();
    showRegisterField('username', 'Gebruikersnaam', 'text', $vald_vals_errs);
    showRegisterField('email', 'Email', 'text', $vald_vals_errs);
    showRegisterField('pswd', 'Wachtwoord', 'password', $vald_vals_errs);
    showRegisterField('pswd', 'Herhaal wachtwoord', 'password', $vald_vals_errs);


    showRegisterEnd();
}

function validateRegister() {
    $valid = false;
    $errors = array("username"=>"", "email"=>"", "pswd"=>"", 
    "pswd2"=>"", );

    $values = array("username"=>"", "email"=>"", "pswd"=>"", "pswd2"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $values["username"] = getPostVar("username");
        $values["email"] =  getPostVar("email", FILTER_SANITIZE_EMAIL);
        $values["pswd"] =  getPostVar("pswd");
        $values["pswd2"] =  getPostVar("pswd2");

        if (empty($values["username"])) {
            $errors["username"] = "Vul alsjeblieft een gebruikersnaam in.";
        }

        if (empty($values["email"])) {
            $errors["email"] = "Vul alsjeblieft je emailadres in.";
        }

        // TODO
        // if (doesEmailExist($values["email"])) {
        //     $errors["email"] = "Dit emailadres heeft al een account op deze website.";
        // }

        if (!filter_var($values["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Vul alsjeblieft een geldig emailadres in.";
        }

        if (empty($values["pswd"])) {
            $errors["pswd"] = "Vul een wachtwoord in ter registratie.";
        }

        if (empty($values["pswd2"])) {
            $errors["pswd2"] = "Herhaal je gekozen wachtwoord ter verificatie.";
        }

        if ($values["pswd"] != $values["pswd2"]) {
            $errors["pswd2"] = "Wachtwoorden komen niet overen";
        }
        
        // kan ik de $key weglaten als ik die niet gebruik in de loop?
        foreach($errors as $field => $err_msg) {
            if (!empty($err_msg)) {
                $valid = false;
                break;
            }
            $valid = true;
        }

    }

    return ['valid' => $valid, 'values' => $values, 'errors' => $errors];

}
// TODO: wat is "or" keyword
// $users = fopen("users.txt", "w") or die("Unable to open file!");

// fwrite($users, "[email]|[naam]|[wachtwoord]\n");
// fclose($users);

// // note the "a/append"
// $users = fopen("users.txt", "a") or die("Unable to open file!");
// fwrite($users, "coach@man-kind.nl" . PHP_EOL);
// fclose($users);

// $users = fopen('users.txt', 'r') or die("No can do!");

// while (!feof($users)) {
//     $current_user = fgets($users);
//     echo $current_user;
//     if ($current_user == "coach@man-kind.nl" . PHP_EOL) {
//         echo $current_user;
//     }
// }
?>