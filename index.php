<html lang="en">
<head>
    <!-- A bit of php stuff -->
    <?php
    include('config.php'); //loading the config file...
    error_reporting(E_ALL); //just to display errors during "dev". Can be disabled if you prefer.
    ?>
    <!-- loading some resources -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- title stuff -->
    <title><?php echo $websiteTitle; ?></title>
    <!-- here we are echoing the website title :-) I'd recommend that you actually use a different format for the config file, so there are no conflicts in the future if you're making your own stuff. -->
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php?pg=1"><?php echo $websiteName; ?></a>
        <!-- just echoing the websiteName from the config file, not a smart way to do this. Don't do it this way :) -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4">
        <h1 class="display-3">Welcome to <?php echo $websiteName; ?>!</h1>
        <!-- once again echoing the website name, and once again. Don't do it like this. There are better ways to setup a config file. -->
        <p class="lead">You can watch lived streamed TV for free!</p>
    </header>

    <!-- Page Features -->

    <div class="row text-center"> <!-- We have this out of the loop to prevent everything from spawning in one line -->

        <!--
        Some PHP for the channel linking and page system starts here
        -->

        <?php
        $initialPage = $_GET['pg']; //getting the "?pg=X" from index.php
        $maxChannels; //loading the maximum channels per page from config.php
        $currentLoadedChannel = 0; //setting the initial value to 0 when loading pages. (so count starts from 0)
        $xml = simplexml_load_file("http://www.streamlive.to/api/live.xml"); //loading the xml file
        foreach ($xml->children() as $livechannels) //loading all the data as a loop and calling it $livechannels
        {
            $currentLoadedChannel += 1;
            if ($currentLoadedChannel > ($initialPage * $maxChannels) && $currentLoadedChannel < ($initialPage * $maxChannels + $maxChannels)) {
                $channelPicture = $livechannels->image; //reading the direct URL to the channel image
                $channelName = $livechannels->name; //reading the name of the channel.
                $amountOfViews = $livechannels->views; //reading the amount of views.
                $channelLanguage = $livechannels->language; //reading the language.
                $channelURL = $livechannels->url; //and reading the URL of the channel.
                ?>

                <?php
                if (($_GET['pg']) < ($_GET['pg'] <= 1) || (($_GET['pg']) < ($_GET['pg'] == 31))) { // round($currentLoadedChannel / $maxChannels)
                    header("Location: index.php?pg=1");
                    exit;
                }
                ?>

                <!--
                initial PHP code for the page system and chanel displaying ends here pretty much. We don't close the with } because we still need to use some of the calls. [InitialPHP-Start]
                -->

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card h-100">
                        <img class="card-img-top" <?php echo "alt=$channelName" ?> <?php echo "src=$channelPicture" ?>>
                        <!-- displaying the image by having access do the direct URL to the image -->
                        <div class="card-body">
                            <h4 class="card-title"><?php echo substr($channelName, 0, 50) ?></h4>
                            <!-- displaying the name of the channel, and limit the chars to 50 to keep all the cards the same size. -->
                            <p class="card-text">
                                <b>Views:</b> <?php echo round($amountOfViews); ?> <!-- Displaying amount of views -->
                                <br/>
                                <b>Language:</b> <?php echo $channelLanguage; ?> <!-- displaying the langauge -->
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo $channelURL; ?>" class='btn btn-primary'>Watch now!</a>
                            <!-- and here we are making a button with a direct URL to the channel -->
                        </div>
                    </div> <!-- Ending for the cards -->

                </div> <!-- Ending for the coloms -->

                <?php
            } //ending the if statement
        } // ending the foreach statement
        ?>

        <!--
        We're ending the code here ^ [InitialPHP-End]
         -->

        <nav aria-label="channel-pages">
            <!-- starting the nav here, out of the loop so it doesn't create shitton of new navs-->
            <ul class="pagination"> <!-- starting the pagination class here, out of the loop for the same reason -->
                <li class="page-item "><a class="page-link" href="index.php?pg=<?php echo $_GET['pg'] + 1 ?>">Back</a>
                </li>
                <!-- next pg simple by doing +1 by getting the current page number. this ofc can go above whatever max page, but not really that important to fix atm -->
                <?php /* if (($_GET['pg']) == 1) {
                    echo "<li class=\"page-item\" <a class=\"page-link\" href=\"index.php?pg=\$_GET['pg'] - 1\">Back BEFORE</li></a>";
                } else {
                    echo "<li class=\"page-item\" <a class=\"page-link\" href=\"index.php?pg=\$_GET['pg'] - 1\">Back AFTER</li></a>";
                } */ // Working on a button that disables itself when you reach MIN and MAX page.
                ?>
                </li>
                <!-- back simply by doing -1 by getting the current page number, and we can go below 1, but I am way too lazy to fix this and this code is messier than my room so there is that. -->
                <?php

                for ($i = 1; $i <= ($currentLoadedChannel / $maxChannels); $i++) { // [CrCNLoo] starts a for loop statement here. This creates a new page by counting the amount of channels and dividing them by the max channels that you wanted per page, then it adds a new value to i till there is none more left.
                    ?>


                    <li class="page-item">
                        <?php if ($i == $_GET['pg']) {  //checks if page number aka $i equals your page number on index.php?pg=$i.
                            echo "<li class=\"page-item active\">";
                            echo "<a class=\"page-link\" href=\"index.php?pg=$i\">$i<span class=\"sr-only\">(current)</span></a>"; // <!-- Creates a new numbered pagination till the loop ends and SELECTS your current page number -->
                            echo "</li>";
                        } else {
                            echo "<a class=\"page-link\" href=\"index.php?pg=$i\">$i</a>"; //if its not select show everything else as unselected.
                        } //ending the if statement for the pagination.
                        ?>
                    </li>


                <?php } ?> <!-- [CrCNLoo] ending the for loop here -->


                <li class="page-item "><a class="page-link" href="index.php?pg=<?php echo $_GET['pg'] + 1 ?>">Next</a>
                </li>
                <!-- next pg simple by doing +1 by getting the current page number. this ofc can go above whatever max page, but not really that important to fix atm -->
            </ul>
        </nav>

    </div> <!-- We have this out of the loop to prevent everything from spawning in one line -->

    <!-- /.row -->

</div>
<!-- /.container -->

<!-- Footer -->
<footer class="py-5 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; <?php echo $websiteName; ?> 2020
            - <?php echo date("Y"); ?></p>
        <!-- eching the website name from the config file, and then eching the current date -->
    </div>
    <!-- /.container -->
</footer>
<!-- a bit of javascript -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>
</html>