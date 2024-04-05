<!DOCTYPE html><html lang="nl">
<head>
    <title>Contact</title>
    <link rel="icon" type="svg" href="Images/online-form-icon.svg">
    <link rel="stylesheet" type="text/css" href="CSS/styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather|Open+Sans"">
</head>
<?php 

$valid = false;
$gender_error = ""; 
$name_error = ""; 
$bericht_error = ""; 
$comm_error = ""; 

$aanhef = "";
$name = "";
$email = "";
$phone = "";
$street = ""; 
$housenumber = ""; 
$house_add = ""; 
$postalcode = ""; 
$municip = "";
$comm = "";
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $aanhef = $_POST["gender"];
    $name = $_POST["fullname"];
    $email = $_POST["email"];
    $phone = $_POST["phonenumber"];
    $street = $_POST["street"];
    $housenumber = $_POST["housingnumber"];
    $house_add = $_POST["addition"];
    $postalcode = $_POST["postalcode"];
    $municip = $_POST["municipality"];
    $comm = $_POST["communicationpref"];
    $msg = $_POST["message"];

    if ($aanhef == "--") {
        $gender_error = "Vul alsjeblieft je aanhefvoorkeur in of geef aan dat je dit liever niet laat weten.";
    }

    if (empty($name)) {
        $name_error = "Vul alsjeblieft je volledige naam in.";
    }
    else if (strlen(ctype_alpha($name)) < 1) {  
        $name_error = "Vul alsjeblieft een naam in met minstens 1 letter.";
    }

    if (empty($bericht)) {
        $bericht_error = "Vul alsjeblieft een bericht in.";
    }
    
    
    switch ($comm) {
        case empty($comm):
            $comm_error = "Vul alsjeblieft je communicatievoorkeur in.";
            break;
            
            case $comm == "Email":
            $comm_error = handle_email($email);
            break;
            
        case $comm == "Telefoon":
            $comm_error = handle_phone($phone);
            break;
            
            case $comm == "Post":
            $comm_error = handle_post($street, $housenumber, $postalcode, $municip);
            break;
        }

    $valid = empty($name_error) && empty($email_error) && empty($bericht_error) && empty($comm_error);
}
    
    function handle_email($email) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Vul alsjeblieft een geldig emailadres in.";
        }

        return "";
    }
    
    function handle_phone($phone) {
        if(!ctype_digit($phone)) {
            return "Vul alsjeblieft een telefoonnummer in met alleen cijfers.";
        }

        return ""; 
    }

    function handle_post($street, $housingnumber, $postalcode, $municip) {
        if (empty($street)) {
            return "Vul alsjeblieft je straatnaam in.";
        }
        if (empty($housingnumber)) {
            return "Vul alsjeblieft je huisnummer in.";
        }
        if (empty($postalcode)) {
            return "Vul alsjeblieft je postcode in.";
        }
        if (empty($municip)) {
            return "Vul alsjeblieft je gemeente in.";
        }

        return "";
    }
  ?>

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
            <select id="gender" name="gender" value="<?php echo $gender; ?>">
                <option value="--">--</option>
                <option value="woman">Mevr.</option>
                <option value="man">Dhr.</option>
                <option value="nonbinary-man">Dhr. / Mevr.</option>
                <option value="nonbinary-man">Mevr. / Dhr.</option>
                <option value="decline">Zeg ik liever niet.</option>
            </select>
            <span class="error">* <?php echo $gender_error; ?></span>

            <label for="fullname"><br>Voor- en achternaam: </label><input type="text" id="fullname" name="fullname" value="<?php echo $name; ?>" placeholder="Marie Jansen">
            <span class="error">* <?php echo $name_error; ?></span>

            <label for="email"><br>Mailadres: </label><input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="voorbeeld@mail.com">
            <span class="error">* <?php echo $comm_error; ?></span>

            <label for="phonenumber"><br>Telefoonnummer: </label><input type="tel" id="phonenumber" name="phonenumber" value="<?php echo $phone; ?>" placeholder="0612345678">
            <span class="error">* <?php echo $comm_error; ?></span>

            <label for="street"><br>Straat: </label><input type="text" id="street" name="street" value="<?php echo $street; ?>" placeholder="Voorbeeldstraat">
            <label for="housingnumber"><br>Huisnummer: </label><input type="number" id="housingnumber" name="housingnumber" value="<?php echo $housenumber; ?>" placeholder="1">
            <label for ="addition"><br>Toevoeging: </label><input type="text" id="addition" name="addition" value="<?php echo $house_add; ?>" placeholder="A">
            <label for="postalcode"><br>Postcode: </label><input type="text" id="postalcode" name="postalcode" value="<?php echo $postalcode; ?>" placeholder="1234AB">
            <label for="municipality"><br>Gemeente: </label><input type="text" id="municipality" name="municipality" value="<?php echo $municip; ?>" placeholder="Utrecht">

            <p>Communicatie voorkeur, via: <span class="error">*</span></p>
            <input type="radio" name="communicationpref" id="email" value="Email"><label for="email">Email</label><br>
            <input type="radio" name="communicationpref" id="tel" value="Telefoon"><label for="tel">Telefoon</label><br>
            <input type="radio" name="communicationpref" id="mail" value="Post"><label for="mail">Post</label><br>
            <span class="error"><?php echo $comm_error; ?></span>

            <label for="message"><br>Uw bericht: </label><span class="error">*<br></span><textarea id="message" name="message" rows="10" cols="60" placeholder="Typ hier uw bericht..."></textarea><br><br>
            <span class="error"> <?php echo $bericht_error; ?></span>
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