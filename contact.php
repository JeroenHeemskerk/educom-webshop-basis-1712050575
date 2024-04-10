<?php 

function getContactTitle() {
    return "Contact";
}

include_once('index.php');
define("GENDERS", array("mevr"=>"Mevr.", "dhr"=>"Dhr.", "dhr_mevr" => "Dhr. / Mevr.", "mevr_dhr" => "Mevr. / Dhr.", "unspecified" => "Zeg ik liever niet."));
define("COMM_PREFS", array("email" => "Email", "phone" => "Telefoon", "post" => "Post"));


function validateContact() {
    $valid = false;
    $errors = array("gender"=>"", "name"=>"", "msg"=>"", 
    "comm"=>"", "email"=>"", "phone"=>"", "post"=>"");

    $values = array("gender"=>"--", "name"=>"", "email"=>"", "phone"=>"", "street"=>"", "housenumber"=>"", "additive"=>"", "postalcode"=>"", "municip"=>"", "msg"=>"", "comm"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $values["gender"] = getPostVar("gender");
        $values["name"] =  getPostVar("name");
        $values["email"] =  getPostVar("email", FILTER_SANITIZE_EMAIL);
        $values["phone"] =  getPostVar("phone");
        $values["street"] =  getPostVar("street");
        $values["housenumber"] =  getPostVar("housenumber");
        $values["additive"] =  getPostVar("additive");
        $values["postalcode"] =  getPostVar("postalcode");
        $values["municip"] =  getPostVar("municip");
        $values["msg"] =  getPostVar("msg");

        if ($values["gender"] == "--") {
            $errors["gender"] = "Vul alsjeblieft je aanhefvoorkeur in of geef aan dat je dit liever niet laat weten.";
        }

        if (empty($values["name"])) {
            $errors["name"] = "Vul alsjeblieft je volledige naam in.";
        }
        else if (!preg_match('/[a-zA-Z]/', $values["name"])) {  
            $errors["name"] = "Vul alsjeblieft een naam in met minstens 1 letter.";
        }

        if (empty($values["msg"])) {
            $errors["msg"] = "Vul alsjeblieft een bericht in.";
        }
        
        if (empty(getPostVar("comm"))) {
            $errors["comm"] = "Vul alsjeblieft je communicatievoorkeur in.";
        }

        else {
            $values["comm"] = getPostVar("comm");
        }

        if ($values["comm"] == "Email" && !filter_var($values["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email"] = "Vul alsjeblieft een geldig emailadres in.";
        }

        if ($values["comm"] == "Telefoon" && empty($values["phone"])) {
            $errors["phone"] = "Vul alsjeblieft een telefoonnummer in. ";
        }
        else if (!empty($values["phone"]) && !ctype_digit($values["phone"])) {
            $errors["phone"] = "Vul alsjeblieft een telefoonnummer in met alleen cijfers.";
        }

        // TODO: make each exception its own error to pass to errors assoc. array
        function handle_post($comm, $street, $housenumber, $postalcode, $municip) {
            # To avoid calling empty twice per var.
            $street_flag = empty($street);
            $housenumber_flag = empty($housenumber);
            $postalcode_flag = empty($postalcode);
            $municip_flag = empty($municip);
    
            if ($comm != "Post" && $street_flag && $housenumber_flag && $postalcode_flag && $municip_flag) {
                return "";
            }
            if ($street_flag) {
                return "Vul alsjeblieft je straatnaam in.";
            }
            if ($housenumber_flag) {
                return "Vul alsjeblieft je huisnummer in.";
            }
            if ($postalcode_flag) {
                return "Vul alsjeblieft je postcode in.";
            }
            if (!preg_match('/^[0-9]{4}[A-Z]{2}$/', $postalcode)) {
                return "Vul alsjeblieft een geldige Nederlands postcode in.";
            }
            if ($municip_flag) {
                return "Vul alsjeblieft je gemeente in.";
            }
    
            return "";
        }


        $errors["post"] = handle_post($values["comm"], $values["street"], $values["housenumber"], $values["postalcode"], $values["municip"]);

        $valid = empty($errors["gender"]) && empty($errors["name"])  && empty($errors["msg"]) && empty($errors["comm"]) && empty($errors["email"]) && empty($errors["phone"]) && empty($errors["post"]);
    }

    return ['valid' => $valid, 'values' => $values, 'errors' => $errors];
}

function showContactStart() {
    echo "<h2>Het Contactformulier</h2>";
    echo '<form method="POST" action="'; echo htmlspecialchars($_SERVER['PHP_SELF']); echo '">
    <p>Neem contact op:</p>';
}

function showContactEnd() {
    echo '<input type="hidden" id="page" name="page" value="contact">';
    echo '<input type="submit" value="Verzenden"></form>';
}

function showContactField($fieldName, $label, $type, $vald_vals_errs, $placeholder=NULL, $options=NULL, $optional=false) {
    $values = $vald_vals_errs["values"];
    $errors = $vald_vals_errs["errors"];

    echo '<div>';
    echo '<label for="' . $fieldName . '">' . $label . ': </label>';

    switch ($type) {
        case "text":
        case "tel":
        case "number":
            echo '<input type="' . $type . '" id="' . $fieldName . '" name="' . $fieldName . '" value="' . $values[$fieldName] . '" placeholder="' . $placeholder . '">';
            echo '<span class="error">'; if (!empty($errors[$fieldName])) {echo " * " . $errors[$fieldName];} echo '</span>';
            break;


        case "textarea":
            echo '<span class="error"> * ' . $errors[$fieldName] . '<br></span>';
            echo '<' . $type . ' id="' . $fieldName . '" name="' . $fieldName . '" ';
            foreach($options as $key => $option) {
                // bit hacky, used for cols and rows
                echo $key . '="' . $option . '" ';
            }
            echo 'placeholder="' . $placeholder . '">';
            echo $values[$fieldName] . '</' . $type . '>.';
            break;

        case "radio":
            echo '<span class="error"> * '  . $errors[$fieldName] . '<br></span>';
            foreach($options as $key => $option) {
                echo '<input type="' . $type . '"';
                echo 'id="' . $key . '" name="' . $fieldName . '" value="' . $option . '" ';
                if (isset($values[$fieldName]) && $values[$fieldName] == $option) { echo "checked";}
                echo '><label for="' . $key . '">' . $option . '</label><br>';
            }
            break;

        case "select":
            echo '<' . $type . ' id="' . $fieldName . '" name="' . $fieldName . '" value="' . $values[$fieldName] . '">';
            foreach($options as $key => $option) {
                echo '<option value="' . $option . '"';
                if ($values[$fieldName] == $option) {echo "selected";}
                echo '>' . $option . '</option>';
            }
            echo '</select>';
            echo '<span class="error"> * ' . $errors[$fieldName] . '</span>';
            break;
    }
    echo '</div>';
}


function showContactContent ($vald_vals_errs) {
    $values = $vald_vals_errs["values"];
    $errors = $vald_vals_errs["errors"];


    showContactStart();
    showContactField('gender', 'Aanhef', 'select', $vald_vals_errs, NULL, GENDERS);
    showContactField('name', 'Voor- en achternaam', 'text', $vald_vals_errs, "Marie Jansen");
    showContactField('email', "Email", "text", $vald_vals_errs, "voorbeeld@mail.com");
    showContactField('phone', "Telefoonnummer", "tel", $vald_vals_errs, "0612345678");
    showContactField('street', 'Straatnaam', 'text', $vald_vals_errs, "Lindeweg");
    showContactField("housenumber", "Huisnummer", "number", $vald_vals_errs, "1");
    showContactField("additive", "Toevoeging", "text", $vald_vals_errs, "A");
    showContactField("postalcode", "Postcode", "text", $vald_vals_errs, "1234AB");
    showContactField("municip", "Gemeente", "text", $vald_vals_errs, "Utrecht");
    showContactField('comm', 'Communicatievoorkeur, via', 'radio', $vald_vals_errs, NULL, COMM_PREFS);
    showContactField('msg', "Uw bericht", "textarea", $vald_vals_errs, "Schrijf hier uw bericht...", ["rows" => 10, "cols" => 60]);
    showContactEnd();
} 
        
function showContactThanks($vald_vals_errs) {
    $values = $vald_vals_errs["values"];

    echo '<p>Bedankt, ' . $values["name"] . ', voor je reactie:</p>
    <div>Aanhef: ' . $values["gender"] . '</div>
    <div>Naam: ' . $values["name"] . '</div>';
    if (!empty($values["phone"])) { 
        echo '<div>Tel: ' . $values["phone"] . '</div>';
    } 
    if (!empty($values["email"])) { 
        echo '<div>Email:  '. $values["email"] . '</div>';
    } 
    
    // At this point, either all are filled in, or none. So only one check required.
    if (!empty($values["street"])) { 
        echo '<div>Adres: ' . $values["street"] . ' ' . $values["housenumber"] . $values["additive"] . '</div>
        <div>Woonplaats: ' . $values["postalcode"] . ', ' . $values["municip"] . '</div>
        <div>Communicatievoorkeur: ' . $values["comm"] . '</div>';
    }
}