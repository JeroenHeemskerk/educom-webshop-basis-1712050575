<?php 

function showLoginStart() {
    echo '<p class="content">Log hier in met je email en wachtwoord:</p>';
    echo '<form method=POST action='; echo htmlspecialchars($_SERVER['PHP_SELF']); echo '">';

}

function showLoginField($fieldName, $label, $type, $vald_vals_errs, $placeholder=NULL) {
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
            echo '<span class="error"> ' . $errors[$fieldName] . '</span>';
            break;
        }
    echo '</div>';
}

function validateLogin() {
    $valid = false;
    $errors = array("email"=>"", "pswd"=>"");

    $values = array("email"=>"", "pswd"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Is deze include nodig
        include_once('index.php');
        $values["email"] =  getPostVar("email", FILTER_SANITIZE_EMAIL);
        $values["pswd"] =  getPostVar("pswd");

        if (empty($values["email"])) {
            $errors["email"] = "Vul je alsjeblieft je emailadres in.";
        }

        if (empty($values["pswd"])) {
            $errors["pswd"] = "Vul je alsjeblieft je wachtwoord in.";
        }

        include_once('communication.php');
        if (!doesEmailExist($values["email"])) {
            $errors["email"] = "Er is geen account bekend op deze website met dit emailadres.";
        }

        else if (!authenticateUser($values["email"], $values["pswd"])) {
            $errors["pswd"] = "Wachtwoord onjuist.";
        }
    }


    return ["valid" => $valid, "values" => $values, "errors" => $errors];
}

function showLoginEnd() {
    echo '<input type="hidden" id="page" name="page" value="login">';
    echo '<input type="submit" value="Login">';
    echo '</form>';
}

function showLoginContent() {
    $vald_vals_errs = validateLogin();

    if (!$vald_vals_errs["valid"]) {
        showLoginStart();
        showLoginField('email', 'Email', 'text', $vald_vals_errs);
        showLoginField('pswd', "Wachtwoord", 'password', $vald_vals_errs);
        showLoginENd();
    }

    else {
        session_start();
        // etc
        // ga naar home
    }

}
?>