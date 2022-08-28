<!--Jason Lu 17985133-->
<!--Handle data from booking.js and send back to booking.html-->
<?php
    //connect to database
	require_once ("../../conf/settings.php");
    // mysqli_connect returns false if connection failed, otherwise a connection value
    $dbConnect = @mysqli_connect(
        $sql_host,
        $sql_user,
        $sql_pass
    );

    // Checks if connection is successful
    if (!$dbConnect) {
        // Displays an error message
        echo "<p>Database connection failure. Error code " . mysqli_connect_errno()		. ": " . mysqli_connect_error(). "</p>";
    } else {
        console.log("Database connection sucessful");
    }

    $dbSelect = @mysqli_select_db($dbConnect,$sql_db);
    if (!$dbSelect) {
        // Displays an error message
        echo "<p>Unable to select the database.</p>"
        . "<p>Error code " . mysqli_errno($dbConnect)
        . ": " . mysqli_error($dbConnect) . "</p>";
    } else {
        console.log ("Database connection sucessful");
    }
    
	//Check is the table exist
        //Create table SQL statement
        $CREATE_CAB_TABLE = "CREATE TABLE CAB_DATABASE(
            BOOKINGNUMBER VARCHAR(40) PRIMARY KEY NOT NULL,
            CNAME VARCHAR(40) NOT NULL,
            CPHONE VARCHAR(40) NOT NULL,
            UNUMBER VARCHAR(100),
            SNUMBER VARCHAR(100) NOT NULL,
            SNAME VARCHAR(100) NOT NULL,
            SUB VARCHAR(100),
            DESSUB VARCHAR(100),
            PICKDATE VARCHAR(40) NOT NULL,
            PICKTIME VARCHAR(40) NOT NULL,
            STATE VARCHAR(40) NOT NULL
            )";

        //The DESCRIBESTATUS will return true if the table exist, otherwise it will return false.
        if ($dbConnect->query ("DESCRIBE CAB_DATABASE")) {
            console.log("Table exist");
        } else {
            //Run Create table statement
            if ($dbConnect->query($CREATE_CAB_TABLE) === TRUE) {
                console.log("Table STATUS is exist now");
            } else {
                //display fail message
                console.log("Fail to create table");
            }
        }
        
        // get date from booking.html
        $cname = $_POST["cname"];
        $phone = $_POST['phone'];
        $unumber = $_POST['unumber'];
        $snumber = $_POST['snumber'];
        $stname = $_POST['stname'];
        $sbname = $_POST['sbname'];
        $dsbname = $_POST['dsbname'];
        $date = $_POST['date'];
        $time = $_POST['time'];

		//using mysqli_real_escape_string keep the input safe and avoid the hacking
        $BRN = GenerateBRN();
		$cname=mysqli_real_escape_string($dbConnect,$cname);
        $phone=mysqli_real_escape_string($dbConnect,$phone);
        $unumber=mysqli_real_escape_string($dbConnect,$unumber);
        $snumber=mysqli_real_escape_string($dbConnect,$snumber);
        $stname=mysqli_real_escape_string($dbConnect,$stname);
        $sbname=mysqli_real_escape_string($dbConnect,$sbname);
        $dsbname=mysqli_real_escape_string($dbConnect,$dsbname);
        $date=mysqli_real_escape_string($dbConnect,$date);
        $time=mysqli_real_escape_string($dbConnect,$time);

        //Insert booking reqest to sql db, each request will set the status as unassigned
        $INSERT_RECORD = "INSERT INTO CAB_DATABASE (BOOKINGNUMBER, CNAME, CPHONE, UNUMBER, SNUMBER, SNAME, SUB, DESSUB, PICKDATE, PICKTIME, STATE) VALUES 
            ('$BRN', '$cname', '$phone', '$unumber', '$snumber', '$stname', '$sbname','$dsbname', '$date', '$time', 'Unassigned');";
            
            //display booking detail if successful insert data to db
            if($dbConnect->query($INSERT_RECORD) === TRUE){
                //replace the format from yyyy/mm/dd to dd/mm/yyyy
                $newdate = str_replace('/','/',$date);
                $newdate= date('d/m/Y', strtotime($newdate));
                echo "<h1 id='reference'>Thank you for your booking</h1>";
                echo "<p id='reference'>Booking reference number:". $BRN ."</p>";
                echo "<p id='reference'>Pickup time: ". $time ."</p>";
                echo "<p id='reference'>Pickup date: ". $newdate ."</p>";
            }else{
                //display error message
                echo "<p>Fail to Request</p>";
            }
        //disconnect to the database
        $dbConnect->close();

        //Create Booking number for the request. Set 5 random number by rand().
        function GenerateBRN(){
            $randNum = array();
            for($i=0; $i<5; $i++){
                $randNum[$i] = rand(0,9);
            }
            $BRN = "BRN". $randNum[0] .$randNum[1] . $randNum[2] .$randNum[3] . $randNum[4];
            return $BRN;
        }
?>
