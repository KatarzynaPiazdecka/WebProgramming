<!DOCTYPE html> //declaration; instruction to the web browser about what version of HTML the page is written in; here HTML5
<?php
require_once "database.php";
$db = Database::getInstance();
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html"; charset=utf-8" />
        <title>Filozofowie</title>
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
            <link href='http://fonts.googleapis.com/css?family=Irish+Grover' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=La+Belle+Aurore' rel='stylesheet' type='text/css'>
            <link href='http://fonts.googleapis.com/css?family=Hammersmith+One&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <link href="style.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />
    </head>
    <body>
        <div class="wrapper">
            <div class="philchoice">Philosophers</div>
            <div class="religion">
            <div class="religioncontent">
            Choose religion:
            <?php 
                $db->getDistinct('religion', 'philosophers');
                $results = $db->results();
            ?>
            <form action="" id="religion" method="post">
                <select name="religion" form="religion">
                <option selected="selected">Unknown</option>
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
