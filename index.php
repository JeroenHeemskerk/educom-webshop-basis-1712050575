<?php 
$page = getRequestedPage();
showPage($page);

function getRequestedPage() {
    $request_type = $_SERVER['REQUEST_METHOD'];
    if ($request_type == "POST") {
        $request_page = getPostPage("page");
    }
    else {
        $request_page = getGetPage("page");
    }
    return $request_page;
}

function getPostPage($key, $default="home") {
    # Gets the page var from the POST request and applies no filter (currently)
    $value = filter_input(INPUT_POST, $key); 
        
    # If it is not found, return the default
    return isset($value) ? $value : $default;
}

function getGetPage($key, $default="home") {
    # Gets the page var from the GET request and applies no filter (currently)
    $value = filter_input(INPUT_GET, $key);

    # If it is not found, return the default
    return isset($value) ? $value : $default;
}

function beginDocument() {
    echo '<!doctype html> 
    <html>'; 
}

function showHeader($page) {
    echo "<head>";

    switch ($page) {
        case "contact":
            echo "<title>Contact</title>";
            break;
        case "about":
            echo "<title>About</title>";
            break;
        case "home":
            echo "<title>Home</title>";
            break;
        default:
            echo "<title>Page Not Found</title>";

    }
            
    echo '<link rel="icon" type="svg" href="Images/online-form-icon.svg">';
    echo '<link rel="stylesheet" type="text/css" href="CSS/styles.css">';
    echo '<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather|Open+Sans">';
    echo '</head>';
}

function showBody($page) {
    echo "<body>" . PHP_EOL;
    echo "<h1>Formulierensite</h1>";
    showNavBar();
    showContent($page);
    showFooter();
    echo "</body>" . PHP_EOL;
}

function showNavBar() {
    echo '<ul class="navbar">
    <li><a href="index.php?page=home">HOME</a></li>
    <li><a href="index.php?page=about">ABOUT</a></li>
    <li><a href="index.php?page=contact">CONTACT</a></li>
    </ul>';
}

function showContent($page) {
    switch ($page) {
        case "about":
            require('about.php');
            showAboutContent();
            break;

        case "contact":
            require('contact.php');
            [$valid, $values, $errors] = validateContact();
            if ($valid) {
                showContactThanks($values);
            }
            else {
                showContactContent($values, $errors);
            }
            break;

        case "home":
            require('home.php');
            showHomeContent();
            break;

        default:
            require('error404.php');
            show404Content();
    }
}

function showPage($page) {
    beginDocument();
    showHeader($page);
    showBody($page);
    echo "</html>";
}

function showFooter() {
    echo '<footer>
    <p>&copy; Florian van der Steen 2024<br></p>
    </footer>';
}
