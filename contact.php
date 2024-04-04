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
        global $gender_error;
        $gender_error = "Vul alsjeblieft je aanhefvoorkeur in of geef aan dat je dit liever niet laat weten.";
        $valid = False;
    }

    if (empty($name)) {
        global $name_error;
        $name_error = "Vul alsjeblieft je volledige naam in.";
        $valid = False;
    }

    else if (strlen(ctype_alpha($name)) < 1) {
        global $name_error;  
        $name_error = "Vul alsjeblieft een naam in met minstens 1 letter.";
        $valid = False;

    if (empty($bericht)) {
        global $bericht_error;
        $bericht_error = "Vul alsjeblieft een bericht in.";
        $valid = False;
    }

    switch ($comm) {
        case empty($comm):
            global $comm_error;
            $comm_error = "Vul alsjeblieft je communicatievoorkeur in.";
            $valid = False;
            break;
        
        case $comm == "Email":
            global $comm_error;
            list($valid, $comm_error) = handle_email();
            break;

        case $comm == "Telefoon":
            global $comm_error;
            list($valid, $comm_error) = handle_phone();
            break;
    
        case $comm == "Post":
            global $comm_error;
            list($valid, $comm_error) = handle_post();
            break;
    }
}

}

    // moet ik afhandelen als er een get request wordt gestuurd?
?>
<body> 
    <h1>Formulierensite</h1>
    <ul class="navbar">
        <li><a href="index.html">HOME</a></li>
        <li><a href="about.html">ABOUT</a></li>
        <li><a href="contact.html">CONTACT</a></li>
    </ul>

    <h2>Het Contactformulier</h2>
    <form method="POST" action="contact.php">
        <p>Neem contact op:</p>
        <label for="gender">Aanhef: </label>
        <select id="gender" name="contact">
            <option value="none">--</option>
            <option value="woman">Mevr.</option>
            <option value="man">Dhr.</option>
            <option value="nonbinary">Non-binair.</option>
            <option value="decline">Zeg ik liever niet.</option>
        </select>
        <label for="fullname"><br>Voor- en achternaam: </label><input type="text" id="fullname" placeholder="Marie Jansen">
        <label for="email"><br>Mailadres: </label><input type="email" id="email" placeholder="voorbeeld@mail.com">
        <label for="phonenumber"><br>Telefoonnummer: </label><input type="tel" id="phonenumber" placeholder="0612345678">
        <label for="street"><br>Straat: </label><input type="text" id="street" placeholder="Voorbeeldstraat">
        <label for="housingnumber"><br>Huisnummer: </label><input type="number" id="housingnumber" placeholder="1">
        <label for ="addition"><br>Toevoeging: </label><input type="text" id="addition" placeholder="A"> 
        <label for="postalcode"><br>Postcode: </label><input type="text" id="postalcode" placeholder="1234AB">
        <label for="municipality"><br>Gemeente: </label><input type="text" id="municipality" placeholder="Utrecht">

        <p>Communicatie voorkeur, via:</p>
        <input type="radio" name="communicationpref" id="email" value="Email"><label for="email">Email</label><br>
        <input type="radio" name="communicationpref" id="tel" value="Telefoon"><label for="tel">Telefoon</label><br>
        <input type="radio" name="communicationpref" id="mail" value="Post"><label for="mail">Post</label><br>

        <label for="message"><br>Uw bericht:<br></label><textarea id="message" rows="10" cols="60" placeholder="Typ hier uw bericht..."></textarea><br><br>
        <input type="submit" value="Verzenden">
    </form>

    <footer>
        <p>&copy; Florian van der Steen 2024<br></p>
    </footer>
</body>

</html>