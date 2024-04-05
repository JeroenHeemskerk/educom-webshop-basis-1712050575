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
$email_error = "";
$phone_error = "";
$post_error = ""; 

$gender = "--";
$name = "";
$email = "";
$phone = "";
$street = ""; 
$housenumber = ""; 
$house_add = ""; 
$postalcode = ""; 
$municip = "";
$bericht = "";
$comm = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gender = $_POST["gender"];
    $name = $_POST["fullname"];
    $email = $_POST["email"];
    $phone = $_POST["phonenumber"];
    $street = $_POST["street"];
    $housenumber = $_POST["housenumber"];
    $house_add = $_POST["addition"];
    $postalcode = $_POST["postalcode"];
    $municip = $_POST["municipality"];
    $bericht = $_POST["message"];

    if ($gender == "--") {
        $gender_error = "Vul alsjeblieft je gendervoorkeur in of geef aan dat je dit liever niet laat weten.";
    }

    if (empty($name)) {
        $name_error = "Vul alsjeblieft je volledige naam in.";
    }
    else if (!preg_match('/[a-zA-Z]/', $name)) {  
        $name_error = "Vul alsjeblieft een naam in met minstens 1 letter.";
    }

    if (empty($bericht)) {
        $bericht_error = "Vul alsjeblieft een bericht in.";
    }
    
    if (empty($_POST["communicationpref"])) {
        $comm_error = "Vul alsjeblieft je communicatievoorkeur in.";
    }

    else {
        $comm = $_POST["communicationpref"];
    }

    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Vul alsjeblieft een geldig emailadres in.";
    }

    if (empty($phone)) {
        $phone_error = "Vul alsjeblieft een telefoonnummer in. ";
    }
    else if (!ctype_digit($phone)) {
        $phone_error = "Vul alsjeblieft een telefoonnummer in met alleen cijfers.";
    }

    $post_error = handle_post($comm, $street, $housenumber, $postalcode, $municip);

    $valid = empty($gender_error) && empty($name_error) && empty($email_error) && empty($bericht_error) && empty($comm_error) && empty($email_error) && empty($phone_error) && empty($post_error);
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
} ?>

<body> 
    <h1>Formulierensite</h1>
    <ul class="navbar">
        <li><a href="index.html">HOME</a></li>
        <li><a href="about.html">ABOUT</a></li>
        <li><a href="contact.php">CONTACT</a></li>
    </ul>
    <h2>Het Contactformulier</h2>


    <?php if (!$valid) { /* Show the next part only when $valid is false */ ?>
        <form method="POST" action="contact.php">
            <p>Neem contact op:</p>
            <label for="gender">Aanhef: </label>
            <select id="gender" name="gender" value="<?php echo $gender; ?>">
                <option value="--">--</option>
                <option value="Mevr." <?php if ($gender == "Mevr.") {echo "selected";} ?>>Mevr.</option>
                <option value="Dhr." <?php if ($gender == "Dhr.") {echo "selected";} ?>>Dhr.</option>
                <option value="Dhr. / Mevr." <?php if ($gender == "Dhr. / Mevr.") {echo "selected";} ?>>Dhr. / Mevr.</option>
                <option value="Mevr. / Dhr." <?php if ($gender == "Mevr. / Dhr.") {echo "selected";} ?>>Mevr. / Dhr.</option>
                <option value="Onbepaald" <?php if ($gender == "Onbepaald") {echo "selected";} ?>>Zeg ik liever niet.</option>
            </select>
            <span class="error">* <?php echo $gender_error; ?></span>

            <label for="fullname"><br>Voor- en achternaam: </label><input type="text" id="fullname" name="fullname" value="<?php echo $name; ?>" placeholder="Marie Jansen">
            <span class="error">* <?php echo $name_error; ?></span>

            <label for="email"><br>Mailadres: </label><input type="email" id="email" name="email" value="<?php echo $email; ?>" placeholder="voorbeeld@mail.com">
            <span class="error"><?php if (!empty($email_error)) {echo "*" . $email_error;} ?></span>

            <label for="phonenumber"><br>Telefoonnummer: </label><input type="tel" id="phonenumber" name="phonenumber" value="<?php echo $phone; ?>" placeholder="0612345678">
            <span class="error"><?php if (!empty($phone_error)) {echo "* " . $phone_error;} ?></span>

            <label for="street"><br>Straat: </label><input type="text" id="street" name="street" value="<?php echo $street; ?>" placeholder="Voorbeeldstraat">
            <label for="housenumber"><br>Huisnummer: </label><input type="number" id="housenumber" name="housenumber" value="<?php echo $housenumber; ?>" placeholder="1">
            <label for ="addition"><br>Toevoeging: </label><input type="text" id="addition" name="addition" value="<?php echo $house_add; ?>" placeholder="A">
            <label for="postalcode"><br>Postcode: </label><input type="text" id="postalcode" name="postalcode" value="<?php echo $postalcode; ?>" placeholder="1234AB">
            <label for="municipality"><br>Gemeente: </label><input type="text" id="municipality" name="municipality" value="<?php echo $municip; ?>" placeholder="Utrecht">
            <span class="error"><?php if (!empty($post_error)) {echo "* " . $post_error;} ?></span>

            <p>Communicatie voorkeur, via: <span class="error">* <?php echo $comm_error; ?></span></p>
            <input type="radio" name="communicationpref" id="email" <?php if (isset($comm) && $comm=="Email") echo "checked";?> value="Email"><label for="email">Email</label><br>
            <input type="radio" name="communicationpref" id="tel" <?php if (isset($comm) && $comm=="Telefoon") echo "checked";?> value="Telefoon"><label for="tel">Telefoon</label><br>
            <input type="radio" name="communicationpref" id="mail" <?php if (isset($comm) && $comm=="Post") echo "checked";?> value="Post"><label for="mail">Post</label><br>

            <label for="message"><br>Uw bericht: </label><span class="error">* <?php echo $bericht_error; ?><br></span><textarea id="message" name="message" rows="10" cols="60" placeholder="Typ hier uw bericht..."><?php echo $bericht; ?></textarea><br><br>
            <input type="submit" value="Verzenden">
        </form>
    <?php } else { ?>
        <p>Bedankt, <?php echo $name; ?>, voor je reactie:</p>
        <div>Aanhef: <?php echo $gender; ?></div>
        <div>Naam: <?php echo $name; ?></div>
        <?php if (!empty($phone)) { ?>
            <div>Tel: <?php echo $phone; ?></div>
        <?php } ?>
        <?php if (!empty($email)) { ?>
        <div>Email: <?php echo $email; ?></div>
        <?php } ?>
        <?php 
        // At this point, either all are filled in, or none. So only one check required.
        if (!empty($street)) { ?>
        <div>Adres: <?php echo $street; echo " " . $housenumber . $house_add;?></div>
        <div>Woonplaats: <?php echo $postalcode . ", " . $municip; ?></div>
        <div>Communicatievoorkeur: <?php echo $comm; ?></div>
            <?php } ?>
    <?php } /* End of conditional showing */ ?>
    <footer>
        <p>&copy; Florian van der Steen 2024<br></p>
    </footer>
</body>

</html>