<?php 
if (session_status() !== PHP_SESSION_ACTIVE) {session_start();}
// var_dump($_POST);
$page = getRequestedPage();
showPage($page);

function getRequestedPage() {
    $request_type = $_SERVER['REQUEST_METHOD'];
    if ($request_type == "POST") {
        $request_page = getPostVar("page");
    }
    else {
        $request_page = getGetVar("page");
    }
    return $request_page;
}
function getGetVar($key, $default="") {  
    $value = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);  
     
    return isset($value) ? trim($value) : $default;
}

function getPostVar($key, $default="", $filter=false) { 
    $value = filter_input(INPUT_POST, $key, $filter | FILTER_SANITIZE_SPECIAL_CHARS); 

    return isset($value) ? trim($value) : $default;   
}



function beginDocument() {
    echo '<!doctype html> 
    <html>'; 
}

function showHeader($page) {
    echo "<head><title>";

    switch ($page) {
        case "contact":
            include_once('contact.php');
            echo getContactTitle();
            break;
        case "about":
            include_once('about.php');
            echo getAboutTitle();
            break;
        case "home":
            include_once('home.php');
            echo getHomeTitle();
            break;
        case "register":
            include_once('register.php');
            echo getRegisterTitle();
            break;
        case "login":
            include_once('login.php');
            echo getLoginTitle();
            break;

        default:
            include_once('error404.php');
            echo get404Title();

    }
    echo "</title>";    
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
    $menu = array("home"=>"HOME", "about"=>"ABOUT", "contact"=>"CONTACT");

    include_once('communication.php');
    if (isUserLoggedIn()) {
        // If user is logged in, display Logout option
        $menu["logout"] = 'LOGOUT ' . getLoggedInUser();
    } 
    else {
        // If user is not logged in, display Register and Login options
        $menu["register"] = "REGISTER";
        $menu["login"] = "LOGIN";
    }
    echo '<ul class="navbar">';
    foreach($menu as $page=>$label) {
        echo '<li><a href="index.php?page=' . $page . '">' . $label . '</a></li>';
    }
    echo '</ul>';
}

function showContent($page) {
    switch ($page) {
        case "about":
            include_once('about.php');
            showAboutContent();
            break;

        case "contact":
            include_once('contact.php');
            showContactContent();
            break;

        case "home":
            include_once('home.php');
            showHomeContent();
            break;

        case "register":
            include_once('register.php');
            showRegisterContent();
            break;

        case "login":
            include_once('login.php');
            showLoginContent();
            break;

        case "logout":
            include_once('logout.php');
            logout();
            break;

        default:
            include_once('error404.php');
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
