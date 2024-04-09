<?php 

function getContactTitle() {
    return "Contact";
}

function validateContact() {
    $valid = false;
    $errors = array("gender_error"=>"", "name_error"=>"", "msg_error"=>"", 
    "comm_error"=>"", "email_error"=>"", "phone_error"=>"", "post_error"=>"");

    $values = array("gender"=>"--", "name"=>"", "email"=>"", "phone"=>"", "street"=>"", "housenumber"=>"", "house_add"=>"", "postalcode"=>"", "municip"=>"", "msg"=>"", "comm"=>"");

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $values["gender"] = $_POST["gender"];
        $values["name"] = $_POST["fullname"];
        $values["email"] = $_POST["email"];
        $values["phone"] = $_POST["phonenumber"];
        $values["street"] = $_POST["street"];
        $values["housenumber"] = $_POST["housenumber"];
        $values["house_add"] = $_POST["addition"];
        $values["postalcode"] = $_POST["postalcode"];
        $values["municip"] = $_POST["municipality"];
        $values["msg"] = $_POST["message"];

        if ($values["gender"] == "--") {
            $errors["gender_error"] = "Vul alsjeblieft je aanhefvoorkeur in of geef aan dat je dit liever niet laat weten.";
        }

        if (empty($values["name"])) {
            $errors["name_error"] = "Vul alsjeblieft je volledige naam in.";
        }
        else if (!preg_match('/[a-zA-Z]/', $values["name"])) {  
            $errors["name_error"] = "Vul alsjeblieft een naam in met minstens 1 letter.";
        }

        if (empty($values["msg"])) {
            $errors["msg_error"] = "Vul alsjeblieft een bericht in.";
        }
        
        if (empty($_POST["communicationpref"])) {
            $errors["comm_error"] = "Vul alsjeblieft je communicatievoorkeur in.";
        }

        else {
            $values["comm"] = $_POST["communicationpref"];
        }

        if ($values["comm"] == "Email" && !filter_var($values["email"], FILTER_VALIDATE_EMAIL)) {
            $errors["email_error"] = "Vul alsjeblieft een geldig emailadres in.";
        }

        if ($values["comm"] == "Telefoon" && empty($values["phone"])) {
            $errors["phone_error"] = "Vul alsjeblieft een telefoonnummer in. ";
        }
        else if (!empty($values["phone"]) && !ctype_digit($values["phone"])) {
            $errors["phone_error"] = "Vul alsjeblieft een telefoonnummer in met alleen cijfers.";
        }

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


        $errors["post_error"] = handle_post($values["comm"], $values["street"], $values["housenumber"], $values["postalcode"], $values["municip"]);

        $valid = empty($errors["gender_error"]) && empty($errors["name_error"])  && empty($errors["msg_error"]) && empty($errors["comm_error"]) && empty($errors["email_error"]) && empty($errors["phone_error"]) && empty($errors["post_error"]);
    }

    return [$valid, $values, $errors];
}

function showContactContent ($values, $errors) {
    echo "<h2>Het Contactformulier</h2>";
    echo '<form method="POST" action="index.php">
        <p>Neem contact op:</p>
        <label for="gender">Aanhef: </label>
        <select id="gender" name="gender" value="'. $values["gender"] . ' ">
            <option value="--">--</option>
            <option value="Mevr."'; if ($values["gender"] == "Mevr.") {echo "selected";} echo '>Mevr.</option>
            <option value="Dhr."';  if ($values["gender"] == "Dhr.") {echo "selected";} echo '>Dhr.</option>
            <option value="Dhr. / Mevr."';  if ($values["gender"] == "Dhr. / Mevr.") {echo "selected";} echo '>Dhr. / Mevr.</option>
            <option value="Mevr. / Dhr."'; if ($values["gender"] == "Mevr. / Dhr.") {echo "selected";} echo '>Mevr. / Dhr.</option>
            <option value="Onbepaald"'; if ($values["gender"] == "Onbepaald") {echo "selected";} echo '>Zeg ik liever niet.</option>
        </select>';
        echo '<span class="error"> * ' . $errors["gender_error"] . '</span>';

        echo '<label for="fullname"><br>Voor- en achternaam: </label><input type="text" id="fullname" name="fullname" value="' .  $values["name"] . '" placeholder="Marie Jansen">';
        echo '<span class="error"> * ' . $errors["name_error"] . ' </span>

        <label for="email"><br>Mailadres: </label><input type="text" id="email" name="email" value="' . $values["email"] . '" placeholder="voorbeeld@mail.com">';
        echo '<span class="error">'; if (!empty($errors["email_error"])) {echo " * " . $errors["email_error"];} echo '</span>

        <label for="phonenumber"><br>Telefoonnummer: </label><input type="tel" id="phonenumber" name="phonenumber" value="' . $values["phone"] . '" placeholder="0612345678">';
        echo '<span class="error">'; if (!empty($errors["phone_error"])) {echo " * " . $errors["phone_error"];} echo '</span>

        <label for="street"><br>Straat: </label><input type="text" id="street" name="street" value="' . $values["street"] . '" placeholder="Voorbeeldstraat">
        <label for="housenumber"><br>Huisnummer: </label><input type="number" id="housenumber" name="housenumber" value="' . $values["housenumber"] . '" placeholder="1">
        <label for ="addition"><br>Toevoeging: </label><input type="text" id="addition" name="addition" value="' . $values["house_add"] . '" placeholder="A">
        <label for="postalcode"><br>Postcode: </label><input type="text" id="postalcode" name="postalcode" value="'. $values["postalcode"] . '" placeholder="1234AB">
        <label for="municipality"><br>Gemeente: </label><input type="text" id="municipality" name="municipality" value="' . $values["municip"] . '" placeholder="Utrecht">
        <span class="error">'; if (!empty($errors["post_error"])) {echo "* " . $errors["post_error"];} echo '</span>

        <p>Communicatie voorkeur, via: <span class="error"> * '  . $errors["comm_error"] . '</span></p>
        <input type="radio" name="communicationpref" id="email" value="Email" '; if (isset($values["comm"]) && $values["comm"]=="Email") echo "checked"; echo '><label for="email">Email</label><br>
        <input type="radio" name="communicationpref" id="tel" value="Telefoon" '; if (isset($values["comm"]) && $values["comm"]=="Telefoon") echo "checked"; echo '><label for="tel">Telefoon</label><br>
        <input type="radio" name="communicationpref" id="mail" value="Post" '; if (isset($values["comm"]) && $values["comm"]=="Post") echo "checked"; echo '><label for="mail">Post</label><br>

        <label for="message"><br>Uw bericht: </label><span class="error"> * ' . $errors["msg_error"] . '<br></span><textarea id="message" name="message" rows="10" cols="60" placeholder="Typ hier uw bericht...">' . $values["msg"] . '</textarea><br><br>';
        echo '<input type="hidden" id="page" name="page" value="contact">';
        echo '<input type="submit" value="Verzenden">
    </form>';
} 
        
function showContactThanks($values) {
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
        echo '<div>Adres: ' . $values["street"] . ' ' . $values["housenumber"] . $values["house_add"] . '</div>
        <div>Woonplaats: ' . $values["postalcode"] . ', ' . $values["municip"] . '</div>
        <div>Communicatievoorkeur: ' . $values["comm"] . '</div>';
    }
}