<?php
//Initialises session
session_start();

//Connecting/checking the connection to the lego data base
$connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego");
if (!$connection) {
    die('Im fuuuucked, aka dead');
}

//A function to test the input from the search field, trimming it and preventing hacking
function test_input($data, $connection)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($connection, $data);
    return $data;
}


//Calling the function above
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $setID = test_input($_GET['setid'], $connection);
    $number_parts = test_input($_GET['nr_parts'], $connection);
    $set_year = test_input($_GET['set_year'], $connection);
}

// If the $_SESSION['recent_sets'] dous not exist, we create it
if (!isset($_SESSION['recent_sets'])) {
    $_SESSION['recent_sets'] = [];
}

// We add the set that the user entered to the $_SESSION['recent_sets'] at the beginnin gof the array
array_unshift($_SESSION['recent_sets'], $setID);

// If there is more then five recent sets stored, we remove the most recent one, the one at the end
if (count($_SESSION['recent_sets']) > 5) {
    array_pop($_SESSION['recent_sets']);
}

// If the $_SESSION['recent_sets_nr_parts'] dous not exist, we create it
if (!isset($_SESSION['recent_sets_nr_parts'])) {
    $_SESSION['recent_sets_nr_parts'] = [];
}

// We add the number of parts in the set that the user entered to the $_SESSION['recent_sets_nr_parts'] at the beginnin gof the array
array_unshift($_SESSION['recent_sets_nr_parts'], $number_parts);

// If there is more then five recent set numbers, we remove the most recent one, the one at the end
if (count($_SESSION['recent_sets_nr_parts']) > 5) {
    array_pop($_SESSION['recent_sets_nr_parts']);
}



//What we want to select, when the setID is something specific, (the search)
$sql = "SELECT inventory.Quantity, parts.Partname, colors.Colorname, images.has_gif, 
images.has_jpg, images.ItemtypeID, images.ItemID, images.ColorID 
FROM inventory, parts, colors, images 
WHERE inventory.SetID = '$setID'
AND inventory.ItemID = parts.PartID
AND inventory.ColorID = colors.ColorID
AND inventory.ColorID = images.ColorID
AND inventory.ItemID = images.ItemID
AND inventory.ItemtypeID = images.ItemtypeID";

// Performs a query on the database
$result = mysqli_query($connection, $sql);


//Getting the set name when you search with a set-ID
$sql_set_info = "SELECT SetID, Setname FROM sets WHERE SetID = '$setID'";
$result_set_info = mysqli_query($connection, $sql_set_info);
$row3 = mysqli_fetch_array($result_set_info);
$setname = $row3['Setname'];



//Getting the set image when you search with a set-ID
$sql_set_image = "SELECT has_largejpg, has_largegif FROM images WHERE ItemID = '$setID'";
$result_img = mysqli_query($connection, $sql_set_image);
$row3 = mysqli_fetch_array($result_img);
$has_jpg = $row3['has_largejpg'];
$has_gif = $row3['has_largegif'];


//First part of the link to the image, which wont change.
$pre_link = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/';


//Finishing the link depending on if it's a gif or jpg
if ($has_gif == 1) {
    $afterlink = "SL/" . $setID . ".gif";
} else if ($has_jpg == 1) {
    $afterlink = "SL/" . $setID . ".jpg";
}
$link = $pre_link . $afterlink;

$amount = mysqli_num_rows($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="style.css">

    <title>Document</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&display=swap" rel="stylesheet">
</head>

<body onload="color()">

    <!--This is the drop down menu where your can change dark/light mode and languages-->
    <div class="for-responsive">
        <div class="top-section">
            <!--Lego logo-->
            <div class="logo">
                <a href="index.php">
                    <img src="Media/lego_small.png" alt="small logo">
                </a>
            </div>
            <div class="dropdown-wrapper">
                <button id="settings-button" class="dropdown-button" title='Settings'>
                    <img src="Media/settings_24px.png" alt="" class="settings-gear" id="gear-icon">
                </button>
                <div class="dropdown-content" id="drop-content">
                    <div class="settings-header">
                        <h2 id="settings-heading">Webb settings</h2>
                    </div>
                    <div class="settings-content">
                        <div id="appearance">
                            <h3 id="settings-subtitle-app">APPEARANCE</h3>
                            <div class="settings-breakline"></div>
                            <div class="settings-button-wrapper">
                                <div class="settings-button-section settings-grid-item-1">

                                    <div class="inner-box" id="light-box"></div>
                                    <p class="inner-text" id="light-text">Aa</p>
                                    <button class="darkmode" onclick="lightmode()" value="Dark-mode">
                                    </button>
                                </div>
                                <p class="settings-grid-item-2" id="settings-grid-item-2">Light</p>

                                <div class="settings-button-section settings-grid-item-3" id="dark-outer-box">

                                    <div class="inner-box" id="dark-box"></div>
                                    <p class="inner-text" id="dark-text">Aa</p>
                                    <button class="darkmode" onclick="darkmode()" value="Dark-mode">
                                    </button>
                                </div>
                                <p class="settings-grid-item-4" id="settings-grid-item-4">Dark</p>
                            </div>
                        </div>
                        <div id="language">
                            <h3 id="settings-subtitle-lang">LANGUAGE</h3>
                            <div class="settings-breakline"></div>
                            <div id="google_translate_element"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Wrapper for basically the whole page-->
    <div class="show-set-page-wrapper">
        <div class="top-header-section">
            <div class="grid-item" id="search-bar"><!--Search bar/help-->
                <form method="get" action="show_sets.php" class="form" id="search_form">
                    <img class="search-icon" src="Media/search_24px_outlined.svg" alt="search" width="36" height="36">
                    <input type="text" value="<?php echo $search ?>" name="search" class="search" id="search-text">
                    <div class="popup" onclick="myFunction()">
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
            </div>
        </div>

        <!--"The chosen set bar" part and the break line-->
        <div class="parts-section-header" id="top-site-heading">
            <div class="parts-icon">
                <img src="Media/arrow.png" style="transform: rotate(90deg);" alt="arrow immage">
            </div>
            <h2 class="Parts-list">Chosen set</h2>
        </div>
        <div class="parts-break-line"></div>
        <!--Set section, info about the set, over all the parts-->
        <div class="set-section">
            <div class="set-section-title-second">
                <?php
                print("<p>$setname</p>");
                ?>
            </div>
            <div class="set-section-image-wrapper">
                <?php
                print("<img class='set-section-image' src=" . $link . " alt='Image of the set'> ");
                ?>
            </div>
            <div class="set-section-info-wrapper">
                <div class="set-section-title">
                    <?php
                    print("<p>$setname</p>");
                    ?>
                </div>
                <div class="set-section-info">
                    <div class="set-section-info-cards" id="set-section-info-id">
                        <img src="Media/hashtag-big.png" alt="hasgtag image" class="set-section-info-cards-icons">
                        <div class="set-section-info-cards-big-text">
                            <?php
                            print("<p>$setID</p>");
                            ?>
                        </div>
                        <div class="set-section-info-cards-small-text">
                            <p>ID</p>
                        </div>
                    </div>
                    <div class="set-section-info-cards" id="set-section-info-date">
                        <img src="Media/date-big.png" alt="date icon" class="set-section-info-cards-icons">
                        <div class="set-section-info-cards-big-text">
                            <?php
                            print("<p>$set_year</p>");
                            ?>
                        </div>
                        <div class="set-section-info-cards-small-text">
                            <p>Date</p>
                        </div>
                    </div>
                    <div class="set-section-info-cards" id="set-section-info-parts">
                        <img src="Media/parts-big.png" alt="parts icon" class="set-section-info-cards-icons">
                        <div class="set-section-info-cards-big-text">
                            <?php
                            print("<p>$number_parts</p>");
                            ?>
                        </div>

                        <div class="set-section-info-cards-small-text">
                            <p>Parts</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <!--The parts section, starting from "Parts list", info about every piece you need for a set-->
        <div class="parts-section">
            <div class="parts-section-header">
                <div class="parts-icon">
                    <img src="Media/parts.png" alt="parts icon">
                </div>
                <h2 id="Parts-list">Parts list</h2>
            </div>
            <div class="parts-break-line"></div>
            <?php
            print("<div class='parts-list-container'>");

            //As long as there is parts to fetch, we'll print them out and all info about them and a picture of them
            while ($row = mysqli_fetch_array($result)) {
                $quantity = $row['Quantity'];
                $partname = $row['Partname'];
                $colorname = $row['Colorname'];
                $gif = $row['has_gif'];
                $jpg = $row['has_jpg'];
                $ItemtypeID = $row['ItemtypeID'];
                $ItemID = $row['ItemID'];
                $ColorID = $row['ColorID'];

                $prelink = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/';

                // if the set has an gif image of an jpg image we set the link the that
                if ($gif == 1) {
                    $afterlink = $ItemtypeID . "/" . $ColorID . "/" . $ItemID . ".gif";
                } else if ($jpg == 1) {
                    $afterlink = $ItemtypeID . "/" . $ColorID . "/" . $ItemID . ".jpg";
                }
                $link = $prelink . $afterlink;

                // pringing all the brick info
                print("<div class='part-card'>");
                print("<div class='part-card-top'>");
                print("<img src=" . $link . " alt='brick image' class='card-part-img'> ");
                print("</div>");
                print("<div class='part-card-bottom'>");
                print("<p class='card-part-name' title='Name'>$partname<p>");
                print("<div class='part-card-bottom-content'>");
                print("<p class='card-part-itemid' title='ID'># $ItemID</p>");
                print("<p class='card-part-quantity' title='Quantity'>x$quantity</p>");
                print("</div>");
                print("</div>");
                print("</div>");
            }

            ?>
        </div>
    </div>
    </div>

    <script src="java.js"></script>
    <script src="colorchange_setcontent.js"></script>

    <!--All Java functions, google translate, dark mode, drop down menu, help pop up-->
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
            }, 'google_translate_element');
        }
    </script>
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>

</html>