<?php //start session
session_start();

// connect to the database
$connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
if (!$connection) { // if connection fails
    die('Lost connection');
}

//Test the input and clean it to protect from php injection m.m. 
$error = "";
$error2 = "";
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $error2 = test_input($_GET['error2'], $connection);
}

//Function to trim the data
function test_input($data, $connection)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($connection, $data);
    return $data;
}

// if the session variable 'error' exists, we set the $error to that value  to later in the code display that message
if (isset($_SESSION["error"])) {
    $error = $_SESSION["error"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search</title>
    <link rel="stylesheet" href="style_index.css">

    <!--Google translate-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&display=swap" rel="stylesheet">
</head>

<body>

    <div class="top-section"> <!-- The settings buton and averything above the lego logo -->
        <div class="dropdown-wrapper">
            <button id="settings-button" class="dropdown-button" title="Settings"> <!--settings button-->
                <img src="Media/settings_24px.png" alt="" class="settings-gear" id="gear-icon">
            </button>
            <div class="dropdown-content" id="drop-content"> <!--settings content-->
                <div class="settings-header">
                    <h2 id="settings-heading">Webb settings</h2>
                </div>
                <div class="settings-content">
                    <div id="appearance">
                        <h3 id="settings-subtitle-app">APPEARANCE</h3>
                        <div class="settings-breakline"></div>
                        <div class="settings-button-wrapper">
                            <div class="settings-button-section" id="settings-grid-item-1">

                                <div class="inner-box" id="light-box"></div>
                                <p class="inner-text" id="light-text">Aa</p>
                                <button class="darkmode" onclick="lightmode()" value="Dark-mode"> <!--Darkmode funtion-->
                                </button>
                            </div>
                            <p class="settings-grid-item2" id="settings-grid-item-2">Light</p>

                            <div class="settings-button-section" id="settings-grid-item-3">

                                <div class="inner-box" id="dark-box"></div>
                                <p class="inner-text" id="dark-text">Aa</p>
                                <button class="darkmode" onclick="darkmode()" value="Dark-mode"><!--darkmode function-->
                                </button>
                            </div>
                            <p class="settings-grid-item-4" id="settings-grid-item-4">Dark</p>
                        </div>
                    </div>
                    <div id="language"><!--Google translate-->
                        <h3 id="settings-subtitle-lang">LANGUAGE</h3>
                        <div class="settings-breakline"></div>
                        <div id="google_translate_element"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- The background -->
    <div class="background-image">
        <img src="Media/Background.png" alt="background image">
    </div>

    <!--searchbar and help popup-->
    <div class="wrapper2">
        <div class="logo">
            <img src="Media/LEGO.png" alt="lego" id="logo">
        </div>
        <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> <!--Google translate-->
        <div id="search-bar" class="grid-item">
            <form method="get" action="show_sets.php" class="form" id="1">
                <img class="search-icon" src="Media/search_24px_outlined.svg" alt="search" width="36" height="36">
                <input type="text" placeholder="Search for a set..." name="search" class="search" id="12345">
                <div class="popup" onclick="myFunction()"><!--help popup-->
                    <img class="help-icon" src="Media/help_outline_24px.svg" alt="help" width="36" height="36">
                    <div class="popuptext" id="myPopup">
                        <h2>In need of help?</h2>
                        <div class="hl" id="help"></div>
                        <p>This is the lego set database. Here you can search on a set either by SET-ID or SET-NAME.
                            You will then be able to see the search results and choose a
                            set and see that what pieces it includes. You can not search with an empty search bar.
                            You can for example search for "Car" or "22-1"
                        </p>
                    </div>
                </div>
            </form>
            <?php
            // If the error varibles has values, if there is erors with the search, these desplays here
            if (!empty($error)) {
                echo ("<h3 class='error'>$error</h3>"); //If the users search doesnt match anything
                unset($_SESSION["error"]);
            }
            if (!empty($error2)) {
                echo ("<h3 class='error2'>$error2</h3>");
            }
            ?>
        </div>

        <!--recently visited sets-->
        <div class="sets">
            <?php
            // if there is recent sets, the headline, "Recent visited sets:" displays
            if (!empty($_SESSION['recent_sets'])) {
            ?>
                <h2 id="sets_headline">Recent visited sets:</h2>
            <?php
            }
            ?>
            <div class="recent_sets">
                <?php

                // The counter is for accesing the correct place in the "$_SESSION['recent_sets_nr_parts']" variable
                $counter = 0;

                // we print all the recent sets stored in the session variable 'recent_sets'
                foreach ($_SESSION['recent_sets'] as $set) {
                    
                    // We get the setid and setname from the database from the setid stored in 'recent_sets'
                    $sql_set_info = "SELECT SetID, Setname, Year FROM sets WHERE SetID = '$set'";
                    $result_set_info = mysqli_query($connection, $sql_set_info);
                    $row3 = mysqli_fetch_array($result_set_info);

                    $setid = $row3['SetID'];
                    $setname = $row3['Setname'];
                    $set_year = $row3['Year'];

                    // We get the number of parts in the set corrsponding the the $set
                    $number_parts = $_SESSION['recent_sets_nr_parts'][$counter];

                    // We gert the image links to the set stored in $set
                    $sql_set_image = "SELECT has_largejpg, has_largegif FROM images WHERE ItemID = '$set'"; 
                    $result_set_img = mysqli_query($connection, $sql_set_image);
                    $row4 = mysqli_fetch_array($result_set_img);

                    $has_jpg = $row4['has_largejpg'];
                    $has_gif = $row4['has_largegif'];
                    $pre_link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/';

                    // if the set has an gif image of an jpg image we set the link the that
                    if ($has_gif == 1) {
                        $afterlink = "SL/" . $set . ".gif";
                    } else if ($has_jpg == 1) {
                        $afterlink = "SL/" . $set . ".jpg";
                    }
                    $link = $pre_link . $afterlink;
                ?>
                    <div class="recent_set">
                        <!-- We make the link to the set with the data as GET so it can display correct -->
                        <a href="set_content.php?setid=<?php echo $set ?>&nr_parts=<?php echo $number_parts ?>&set_year=<?php echo $set_year ?>">
                            <?php
                            echo ("<img src=" . $link . " alt='Image on the set'> ");
                            ?>
                            <h2><?php echo ($setname) ?></h2>
                            <h3>#<?php echo ($setid) ?></h3>
                        </a>
                    </div>
                <?php
                    $counter += 1;
                }
                ?>
            </div>
        </div>
    </div>

    <!--Web setting button and content-->

    <script src="java.js"></script> <!--settings function, help popup and google translate-->
    <script src="colorchange.js"></script> <!--darkmode functions-->
</body>

</html>