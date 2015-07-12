<!DOCTYPE html>
<?php
require_once "database.php"; // dziala jak Require poza tym, że sprawdza czy dany plik by już dolaczony, jesli tak to go nie dolacza znowu
$db = Database::getInstance(); // returns the Database object instance (chyba! - zrodlo http://search.cpan.org/~aff/PHP-Interpreter-1.0.2/lib/PHP/Interpreter.pm#getInstance%)
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html"; charset=utf-8" /> //okreslamy zbior/charakter uzywanych znakow
        <title>Filozofowie</title>
        <!--[if lt IE 9]> // comment  - if less than IE 9
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script> //points to external JavaScript
        <![endif]-->
            <link href='http://fonts.googleapis.com/css?family=Irish+Grover' rel='stylesheet' type='text/css'> //link do okreslonego adresu; REL okresla stosunek miedzy dokumentami
            <link href='http://fonts.googleapis.com/css?family=La+Belle+Aurore' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Hammersmith+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <link href="style.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" /> // Nie chce mi sie tego pisac ale tu jest o Favicon: http://apidock.com/rails/ActionView/Helpers/AssetTagHelper/favicon_link_tag
    </head>
    <body>
        <div class="wrapper">
            <div class="philchoice">Philosophers</div> // PO CO JEST DIV?
            <div class="religion">
            <div class="religioncontent">
            Choose religion:
            <?php 
                $db->getDistinct('religion', 'philosophers'); //CO ROBI GETDISTINCT?
                $results = $db->results(); //wywolujemy [metode] results z [klasy] db (chyba)
            ?>
            <form action="" id="religion" method="post"> // form - creates form for user input; action - where to send the form-data; post - przekazuje paramtery w sposob niewidoczny dla zwyklego uzytkownika (nie widac ich na pasku przegladarki)
                <select name="religion" form="religion"> //select - robi liste drop-down; form - defines form the select fields belong to
                <option selected="selected">Unknown</option> //wybiera domyslnie opcje Unknown (chyba)
                    <?php
                        foreach($results as $result)
                        {
                            if(!($result->religion === "Unknown"))
                            {
                                echo "<option value=\"" . $result->religion . "\">" . $result->religion . "</option>";
                            }
                        }
                    ?>
                </select>
                </br>
                <input type="submit" value="go!">
            </form>
            <p>Philosophers that belongs to this religion are:</p>
            <?php
            if(isset($_POST['religion']))
            {
                $db->get('name, url', 'philosophers', array('religion', '=', $_POST['religion']));
                $results = $db->results();
                foreach($results as $result)
                {
                    echo "<div class='name'><a target='_blank' href='" . $result->url . "'>" . $result->name . "</a></div>";
                }
            }
            ?>
            </div>
            </div>
        </div>
    </body>
