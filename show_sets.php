<?php
session_start(); //start session
$connection = mysqli_connect("mysql.itn.liu.se", "lego", "", "lego"); //connect to database
if (!$connection) {
    die('Im fuuuucked, aka dead'); //if connection fails
}

//Function to trim the data clean it to protect from php injection m.m. 
function test_input($data, $connection)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($connection, $data);
    return $data;
}



$search = "";
$page_nr = 0;
$sort_name = "";
$sort_year = "";

//Test the input and clean it to protect from php injection m.m. 
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search = test_input($_GET['search'], $connection);
    $page_nr = test_input($_GET['page_nr'], $connection);
    $sort_name = test_input($_GET['sort_name'], $connection);
    $sort_year = test_input($_GET['sort_year'], $connection);
}


// if the user doesn't search on anything we send them back to the search page with an error message
if (empty($search)) {
    $error = "0 results found. Please search better";
    $_SESSION["error"] = $error;
    header("Location: index.php");
    exit();
}

// The number of sets that should be shown at each page
$number_sets_page = 10;

// set the page number from the beginning
if (empty($page_nr) || $page_nr < 0) {
    $page_nr = 0;
}


// Calculate which sets are to be shown, if the page number is 2 we show the 2-1 (because the page numbering begins with 0) * 10 = the limit = 10
$limit = ($page_nr * $number_sets_page);

// sort the data by lenght or year
if ($sort_year == !10 && $sort_year == !20) {
    $sort = "LENGTH (sets.Setname) asc";
} else {
    if ($sort_year == 10) {
        $sort = 'sets.year asc';
        $sort_send = 10;
    } elseif ($sort_year == 20) {
        $sort = 'sets.year desc';
        $sort_send = 20;
    }
}

// Calculate what the $sort_what should be when we switch page, so that it stays the way it was on the previous page
if (empty($sort_year)) {
    $sort_what = 10;
} else {
    if ($sort_year == 10) {
        $sort_what = 20;
    } elseif ($sort_year == 20) {
        $sort_what = 10;
    }
}



// Getting all the sets so we can get how many sets comes up in total to that search word
$sql_all_sets = "SELECT
sets.SetID
FROM sets
WHERE
sets.Setname LIKE '%$search%'
OR
sets.SetID = '$search'";

// Performs a query on the database
$result_all = mysqli_query($connection, $sql_all_sets);

// Get the number of rows/getting the number of total sets that comes up when a search is done
$all_set_amount = mysqli_num_rows($result_all);

// We calculate the total number of pages
$page_numbers = $all_set_amount / $number_sets_page;


// Get sets that matches the users search ordered by what it have selected
$sql_sets = "SELECT
sets.SetID, sets.Setname, sets.Year
FROM sets
WHERE
sets.Setname LIKE '%$search%'
OR
sets.SetID = '$search'
ORDER BY $sort
LIMIT $limit, $number_sets_page";

// Performs a query on the database
$result = mysqli_query($connection, $sql_sets);

// If the search dousn't have a result, we send them back to the start page with an error message
$amount_result = mysqli_num_rows($result);
if ($amount_result == 0) {
    $error2 = "0 results found";
    header("Location: index.php?error2=$error2");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="style.css">
    <script src="java.js" defer></script><!--settings function, help popup and google translate-->
    <script src="colorchange_setcontent.js" defer></script><!--Dark and lightmode function for show sets page-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&display=swap" rel="stylesheet">
</head>

<body>

    <!--google translate-->
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.HORIZONTAL
            }, 'google_translate_element');
        }
    </script>
    <!--Web setting button and content-->
    <div class="for-responsive">
        <div class="top-section">
            <div class="logo">
                <a href="index.php">
                    <img src="Media/lego_small.png" alt="small logo">
                </a>
            </div>
            <div class="dropdown-wrapper">
                <button id="settings-button" class="dropdown-button" title='Settings'>
                    <img src="Media/settings_24px.png" alt="" class="settings-gear" id="gear-icon">
                </button>
                <div class="dropdown-content" id="drop-content"><!--toggled dropdown menu-->
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
                                    <button class="darkmode" onclick="lightmode()" value="Dark-mode"><!--Darkmode function-->
                                    </button>
                                </div>
                                <p class="settings-grid-item-2" id="settings-grid-item-2">Light</p>
                                <div class="settings-button-section settings-grid-item-3" id="dark-outer-box">
                                    <div class="inner-box" id="dark-box"></div>
                                    <p class="inner-text" id="dark-text">Aa</p>
                                    <button class="darkmode" onclick="darkmode()" value="Dark-mode"><!--Lightmode function-->
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
    <script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script><!--google translate-->

    <!--searchbar and help popup-->
    <div class="wrapper">
        <div class="scroll">
            <div class="grid-item" id="search-bar">
                <form method="get" action="show_sets.php" class="form" id="search_form"> <!--searchbar for user to type into-->
                    <img class="search-icon" src="Media/search_24px_outlined.svg" alt="search" width="36" height="36">
                    <input type="text" value="<?php echo $search ?>" name="search" class="search" id="search-text">
                    <div class="popup" onclick="myFunction()"> <!--help popup function-->
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

            <div class="sort_by">
                <h2>Sort by: </h2>
                <div class="sort_contents">
                    <div class="sort_content">
                        <h3>Name</h3>
                        <form action="show_sets.php" method="get"><!--sort content by name-->
                            <input type="hidden" value="<?php echo $search ?>" name="search">
                            <input type="hidden" name="page_nr" value="<?php echo ($page_nr) ?>">
                            <input type="hidden" name="sort_name" value="<?php echo (1) ?>">
                            <input type="image" src="Media/up-down2.png" alt="Submit">
                        </form>
                    </div>
                    <div class="sort_content">
                        <h3>Year</h3>
                        <form action="show_sets.php" method="get"><!--sort content by year-->
                            <input type="hidden" value="<?php echo $search ?>" name="search">
                            <input type="hidden" name="page_nr" value="<?php echo ($page_nr) ?>">
                            <input type="hidden" name="sort_year" value="<?php echo ($sort_what) ?>">
                            <input type="image" src="Media/up-down2.png" alt="Submit">
                        </form>
                    </div>
                    <div class="sort_content">
                        <h3>Quantity</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <?php
            // Display all the sets that comes up we the user search on something
            while ($row = mysqli_fetch_array($result)) {

                $setid = $row['SetID'];
                $setname = $row['Setname'];
                $set_year = $row['Year'];

                $sql_images = "SELECT has_largejpg, has_largegif FROM images WHERE ItemID LIKE '$setid'"; //get images that matches the setid
                $sql_quant = "SELECT inventory.Quantity FROM inventory WHERE SetID = '$setid'"; // get the quantity that matches the setid
                $result3 = mysqli_query($connection, $sql_quant);
                $result2 = mysqli_query($connection, $sql_images);
                $row2 = mysqli_fetch_array($result2);
                $ooga = 0;
                for ($x = 0; $x < mysqli_num_rows($result3); ++$x) { //count the number of bricks in the set
                    $row3 = mysqli_fetch_array($result3);
                    $ooga += $row3['Quantity'];
                }
                $has_jpg = $row2['has_largejpg'];
                $has_gif = $row2['has_largegif'];
                $prelink = 'http://www.itn.liu.se/~stegu76/img.bricklink.com/';

                if ($has_gif == 1) {
                    $afterlink = "SL/" . $setid . ".gif";
                } else if ($has_jpg == 1) {
                    $afterlink = "SL/" . $setid . ".jpg";
                }
                $link = $prelink . $afterlink;
                //print the sets
                echo ("<a href='set_content.php?setid=$setid&nr_parts=$ooga&set_year=$set_year'>");
                echo ("<div class='item'>");

                echo ("<img src=" . $link . " alt='set image' class='imgg'> ");

                echo ("<div class='items_text'>");
                echo ("<div class='item_text name_id'>");
                echo ("<h2 title='Name'>$setname</h2>");
                echo ("<h3 title='ID'>#$setid</h3>");
                echo ('</div>');

                echo ("<div class='item_text'>");
                echo ("<img src='Media/date.png' alt='date icon' class='img_date' title='Year'> ");
                echo ("<h3 title='Year'> $set_year</h3>");
                echo ('</div>');

                echo ("<div class='item_text'>");
                echo ("<img src='Media/parts.png' alt='parts icon' class='img_date' title='Quantity'> ");
                echo ("<h3 title='Quantity'> $ooga</h3>");
                echo ('</div>');
                echo ('</div>');

            ?>
                <div class="item_arrow"> <!--Arrow on set page-->
                    <div class="bouncy-item-arrow">
                        <div class="bouncy-item-arrow-img">

                        </div>
                        <img src="Media/large_arrow.png" alt="a nice arrow">
                    </div>
                </div>
            <?php

                echo ('</div>');
                echo ('</a>');
            }


            ?>
        </div>
        <div class="next_page"> <!--next page-->
            <?php
            // When the user is on the first page, we do not need to show the arrow that show that you can go the previous page
            if ($page_nr > 0) {
            ?>
                <form method="get" action="show_sets.php" class="arrows">
                    <input type="hidden" value="<?php echo $search ?>" name="search" class="search">
                    <input type="hidden" name="page_nr" class="page_nr" value="<?php echo ($page_nr - 1) ?>">
                    <input type="hidden" name="sort_year" value="<?php echo ($sort_send) ?>">
                    <input type="image" src="Media/arrow.png" alt="Submit" style="transform: rotate(180deg);" class="arrow">
                </form>
            <?php
            }
            ?>
            <div class="numbers">
                <?php
                // When the user is on the first page, we do not need to show the number that show that you can go the previous page
                if ($page_nr > 0) {
                ?>
                    <form method="get" action="show_sets.php" class="number">
                        <input type="hidden" value="<?php echo $search ?>" name="search" class="search">
                        <input type="hidden" name="page_nr" class="page_nr" value="<?php echo ($page_nr - 1) ?>">
                        <input type="hidden" name="sort_year" value="<?php echo ($sort_send) ?>">
                        <input type="submit" value="<?php echo ($page_nr) ?>" class="inside_number">
                    </form>
                <?php
                }
                ?>
                <form method="get" action="show_sets.php" class="number">
                    <input type="hidden" value="<?php echo $search ?>" name="search" class="search">
                    <input type="hidden" name="page_nr" class="page_nr" value="<?php echo ($page_nr) ?>">
                    <input type="hidden" name="sort_year" value="<?php echo ($sort_send) ?>">
                    <input type="submit" value="<?php echo ($page_nr + 1) ?>" id="current_number">
                </form>
                <?php
                // If the user is on the final page, we do not need to show the number that show that you can go the next page
                if ($page_numbers >= $page_nr + 1) {
                ?>

                    <form method="get" action="show_sets.php" class="number">
                        <input type="hidden" value="<?php echo $search ?>" name="search" class="search">
                        <input type="hidden" name="page_nr" class="page_nr" value="<?php echo ($page_nr + 1) ?>">
                        <input type="hidden" name="sort_year" value="<?php echo ($sort_send) ?>">
                        <input type="submit" value="<?php echo ($page_nr + 2) ?>" class="inside_number">
                    </form>
                <?php
                }
                ?>
            </div>
            <?php
            // If the user is on the final page, we do not need to show the arow that show that you can go the next page
            if ($page_numbers >= $page_nr + 1) {
            ?>
                <form method="get" action="show_sets.php" class="arrows">
                    <input type="hidden" value="<?php echo $search ?>" name="search" class="search">
                    <input type="hidden" name="page_nr" class="page_nr" value="<?php echo ($page_nr + 1) ?>">
                    <input type="hidden" name="sort_year" value="<?php echo ($sort_send) ?>">
                    <input type="image" src="Media/arrow.png" alt="Submit" class="arrow">
                </form>
            <?php
            }
            ?>
        </div>
    </div>

    <script src="java.js"></script><!--settings function, help popup and google translate-->
    <script src="colorchange_setcontent.js"></script><!--Dark and lightmode function for show sets page-->

</body>

</html>