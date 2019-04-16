<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            session_start();
            if($_SESSION["username"] == "" && $_SESSION["password"] == "") {
                header("Location:login.php");
            }
            $USER_NAME = $_SESSION["username"];
            $PASS_WORD = $_SESSION["password"];
        ?>
        <meta charset="utf-8"/>

        <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="css/custom.css">

        <title>News Feed Project</title>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <script type="text/javascript">
            var USER_NAME = '<?php echo $USER_NAME;?>';
        </script>
        <script src="scripts.js"></script>
    </head>
    <body>
        <div id="header">
            <img src="http://a.espncdn.com/i/espn/teamlogos/lrg/trans/espn_dotcom_black.gif" />
            <h1>Sports News</h1>
            <a id="logout" href="logout.php" class="btn" onclick="logout()">Logout</a>
        </div>

        <p><h3><b>Last visited: </b></h3><div id="lastVisit"></div></p>
        <br/>
        <br/>

        <h3><b>Favorites:</b></h3>
        <div id="favorites">
            Selected favorites will display here.
        </div>
        <br/>
        <br/>

        <div>
            <label for="nfl" class="btn btn-primary"> NFL News
              <input onchange="check(this)" type="checkbox" id="nfl" class="badgebox">
              <span class="badge">&check;
              </span>
            </label>
            <label for="mlb" class="btn btn-danger"> MLB News
              <input onchange="check(this)" type="checkbox" id="mlb" class="badgebox">
              <span class="badge">&check;
              </span>
            </label>
            <label for="nhl" class="btn btn-success"> NHL News
              <input onchange="check(this)" type="checkbox" id="nhl" class="badgebox">
              <span class="badge">&check;
              </span>
            </label>

        </div>
        <br/>
        <div id="sportsContent">
        </div>

    </body>
</html>
