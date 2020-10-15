<!DOCTYPE html>
<html>
    <head>
        <title>My-form</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            
        function jsFunction()
        {
            <?php session_start();?>
            document.getElementById("city").innerHTML ="<?PHP echo $_SESSION['city'] ?>";
            document.getElementById("long").innerHTML ="<?PHP echo $_SESSION['long'] ?>";
            document.getElementById("lat").innerHTML ="<?PHP echo $_SESSION['lat'] ?>";
            document.getElementById("submit").disabled = false;
        }
        function error()
        {
            document.getElementsByTagName("input")[0].setAttribute("style", "border: 1px solid #ff0404;");
            document.getElementsByTagName("input")[1].setAttribute("style", "border: 1px solid #ff0404;");
        }
</script>
    </head>
<body>
    <fieldset>
    <legend><h2>Form</h2></legend>
    <div class="container">
    <form method="POST">
        <div class="form-group">
            <label for="fname">Full name:</label><br>
            <input type="text" name="fullname" class="form-control"><br>
        </div>
        <div class="form-group">
            <label for="fname">Password:</label><br>
            <input type="password" name="password" class="form-control"><br>
        </div>
        <div class="form-group"><label>City:</label><label id="city" class="label label-primary"></label><br></div>
        <div class="form-group"><label>Longitude:</label><label id="long" class="label label-primary"></label><br></div>
        <div class="form-group"><label>Latitude:</label><label id="lat" class="label label-primary"></label><br><br></div>
        <input type="submit" name="locateme" Value="Locate me" id="locate" class="btn btn-default">
        <input type="submit" name="submit" Value="Submit" id="submit" class="btn btn-default"><br>
    </form>
    </div>
    </fieldset>
    
    <?php
        echo '<script type="text/javascript">document.getElementById("submit").disabled = true;</script>';
        if(isset($_POST["locateme"]))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://ip-api.com/json");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result = curl_exec($ch);
            $result = json_decode($result); 

            if($result -> status=='success')
            {
                $city = $result->city;
                $longitude = $result->lon;
                $latitude = $result->lat;
                $_SESSION["long"] = $longitude;
                $_SESSION["lat"] = $latitude;
                $_SESSION["city"] = $city;
                
                echo '<script type="text/javascript">document.getElementById("locate").disabled = true;</script>';
                echo '<script type="text/javascript">jsFunction();</script>';
                 
            }
        }
        if(isset($_POST["submit"]))
        {  
            echo '<script type="text/javascript">document.getElementById("locate").disabled = true;</script>';
            echo '<script type="text/javascript">jsFunction();</script>';
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "credentials";
            
            
            $db = new mysqli($servername, $username, $password, $dbname);
            if ($db->connect_error)
            {
                die("Connection failed: " . $db->connect_error);
            }
            
            if($_POST["fullname"]=="" && $_POST["password"]=="")
            {
                echo '<script type="text/javascript">error();</script>';
            }
            else
            {
                $fullname=$_POST["fullname"];
                $password=$_POST["password"];
                $long = $_SESSION['long'];
                $lat = $_SESSION['lat'];
                $city = $_SESSION['city'];

                $sql = "INSERT INTO users (fullname, password, latitude, longitude, city)
                VALUES ('$fullname', '$password',  '$lat', '$long', '$city' )";

                if ($db->query($sql) === TRUE) 
                {
                    echo "New record created successfully";
                } 
                else 
                {
                    echo "Error: " . $sql . "<br>" . $db->error;
                }
            }
            
            
            $db->close();
        }
    ?>


</body>
</html>