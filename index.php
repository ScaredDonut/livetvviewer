<?php
    $xml = simplexml_load_file("http://www.streamlive.to/api/live.xml");
    echo "<h2>".$xml->getName()."</h2><br />";
    foreach($xml->children() as $livechannels)
    {
		echo "<br />";
		echo "<table border='1'>";
		echo "<tr>";
        echo "<td><b>Channel Name: </b></td><td><u>".$livechannels->name."</td></u> <br />";
		echo "</tr>";
		echo "<tr>";
        echo "<td><b>Image: </b></td> <td><center><img src='".$livechannels->image."'/></center></td> <br />";
       	echo "</tr>";
		echo "<tr>";
		echo "<td><b>URL to Watch: </b></td> <td><center><url><a href='".$livechannels->url."'/>Click to Watch</a> </td><br />";
        echo "</tr>";
		echo "<tr>";
		echo "<td><b>Language:</b></td><td><u>".$livechannels->language."</td></u> <br />";
		echo "</tr>";
		echo "<tr>";
        echo "<td><b>Number of Viewers:</b></td><td><u> ".$livechannels->views."</td></u> <br />";
        echo "<hr/>";
		echo "</table>";
    }
?>