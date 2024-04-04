<!DOCTYPE html><html lang="nl">
<head>
    <title>Contact</title>
    <link rel="icon" type="svg" href="Images/online-form-icon.svg">
    <link rel="stylesheet" type="text/css" href="CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather|Open+Sans"">
</head>
<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aanhef = $_POST["gender"];
    $name = $_POST["fullname"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $street = $_POST["street"];
    $housenumber = $_POST["housingnumber"];
    $house_add = $_POST["addition"];
    $postalcode = $_POST["postalcode"];
    $municip = $_POST["gemeente"];
    $comm = $_POST["communicationpref"];
    $msg = $_POST["message"];

    $valid = True;

    if ($aanhef == "--") {
        $GLOBALS["gender_error"] = "Vul alsjeblieft je aanhefvoorkeur in of geef aan dat je dit liever niet laat weten.";
        $valid = False;
    }

    if (empty($name)) {
        $GLOBALS["name_error"] = "Vul alsjeblieft je volledige naam in.";
        $valid = False;
    }

    else if (strlen(ctype_alpha($name)) < 1) {  
        $GLOBALS["name_error"] = "Vul alsjeblieft een naam in met minstens 1 letter.";
        $valid = False;

    if (empty($bericht)) {
        $GLOBALS["bericht_error"] = "Vul alsjeblieft een bericht in.";
        $valid = False;
    }

    function handle_email($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [False, "Vul alsjeblieft een geldig emailadres in."];
        }

        return [True, ""];
    }

    function handle_phone($phone) {
        if(!ctype_digit($phone)) {
            return [False, "Vul alsjeblieft een telefoonnummer in met alleen cijfers."];
        }

        return [True, ""]; 
    }

    function handle_post($street, $housingnumber, $postalcode, $municip) {
        if (empty($street)) {
            return [False, "Vul alsjeblieft je straatnaam in."];
        }
        if (empty($housingnumber)) {
            return [False, "Vul alsjeblieft je huisnummer in."];
        }

        if (empty($postalcode)) {
            return [False, "Vul alsjeblieft je postcode in."];
        }

        if (empty($municip)) {
            return [False, "Vul alsjeblieft je gemeente in."];
        }

        return [True, ""];
    }


    switch ($comm) {
        case empty($comm):
            $GLOBALS["comm_error"] = "Vul alsjeblieft je communicatievoorkeur in.";
            $valid = False;
            break;
        
        case $comm == "Email":
            list($valid, $GLOBALS["comm_error"]) = handle_email($email);
            break;

        case $comm == "Telefoon":
            global $comm_error;
            list($valid, $GLOBALS["comm_error"]) = handle_phone($phone);
            break;
    
        case $comm == "Post":
            global $comm_error;
            list($valid, $GLOBALS["comm_error"]) = handle_post($street, $housingnumber, $postalcode, $municip);
            break;
        }
    }

} ?>

<body> 
    <h1>Formulierensite</h1>
    <ul class="navbar">
        <li><a href="index.html">HOME</a></li>
        <li><a href="about.html">ABOUT</a></li>
        <li><a href="contact.html">CONTACT</a></li>
    </ul>

    <h2>Het Contactformulier</h2>
    <?php if (!$valid) { /* Show the next part only when $valid is false */ ?>
        <form method="POST" action="contact.php">
            <p>Neem contact op:</p>
            <label for="gender">Aanhef: </label>
            <select id="gender" name="contact" value="<?php echo $gender; ?>">
                <option value="none">--</option>
                <option value="woman">Mevr.</option>
                <option value="man">Dhr.</option>
                <option value="nonbinary-man">Dhr. / Mevr.</option>
                <option value="nonbinary-man">Mevr. / Dhr.</option>
                <option value="decline">Zeg ik liever niet.</option>
            </select>
            <span class="error">* <?php echo $gender_error; ?></span>

            <label for="fullname"><br>Voor- en achternaam: </label><input type="text" id="fullname" value="<?php echo $name; ?>" placeholder="Marie Jansen">
            <span class="error">* <?php echo $name_error; ?></span>

            <label for="email"><br>Mailadres: </label><input type="email" id="email" value="<?php echo $email; ?>" placeholder="voorbeeld@mail.com">
            <span class="error">* <?php echo $comm_error; ?></span>

            <label for="phonenumber"><br>Telefoonnummer: </label><input type="tel" id="phonenumber" value="<?php echo $phone; ?>" placeholder="0612345678">
            <span class="error">* <?php echo $comm_error; ?></span>

            <label for="street"><br>Straat: </label><input type="text" id="street" value="<?php echo $street; ?>" placeholder="Voorbeeldstraat">
            <label for="housingnumber"><br>Huisnummer: </label><input type="number" id="housingnumber" value="<?php echo $housenumber; ?>" placeholder="1">
            <label for ="addition"><br>Toevoeging: </label><input type="text" id="addition" value="<?php echo $house_add; ?>" placeholder="A">
            <label for="postalcode"><br>Postcode: </label><input type="text" id="postalcode" value="<?php echo $postalcode; ?>" placeholder="1234AB">
            <label for="municipality"><br>Gemeente: </label><input type="text" id="municipality" value="<?php echo $municip; ?>" placeholder="Utrecht">

            <p>Communicatie voorkeur, via:</p>
            <input type="radio" name="communicationpref" id="email" value="Email"><label for="email">Email</label><br>
            <input type="radio" name="communicationpref" id="tel" value="Telefoon"><label for="tel">Telefoon</label><br>
            <input type="radio" name="communicationpref" id="mail" value="Post"><label for="mail">Post</label><br>
            <span class="error">* <?php echo $comm_error; ?></span>

            <label for="message"><br>Uw bericht:<br></label><textarea id="message" rows="10" cols="60" placeholder="Typ hier uw bericht..."></textarea><br><br>
            <span class="error">* <?php echo $bericht_error; ?>></span>
            <input type="submit" value="Verzenden">
        </form>
    <?php } else { ?>
        <p>Bedankt, <?php echo $name; ?>, voor je reactie:</p>;
        <div>Aanhef: <?php echo $gender; ?></div>
        <div>Naam: <?php echo $name; ?></div>
        <div>Tel: <?php echo $phone; ?></div>
        <div>Email: <?php echo $email; ?></div>
        <div>Adres: <?php echo $street; echo "," . $housenumber . $house_add;?></div>
        <div>Woonplaats: <?php echo $postalcode . "," . $municip; ?></div>
        <div>Communicatievoorkeur: <?php echo $comm; ?></div>

    <?php } /* End of conditional showing */ ?>
    <footer>
        <p>&copy; Florian van der Steen 2024<br></p>
    </footer>
</body>

</html>