<!--Jason Lu 17985133-->
<!--Handle date from admin.js and send back to admin.html-->
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
    
    //Check search input if null or not. Also if the input is "Notnull" and AssignBRN, then it will run for the assign status
    //Show request in 2 hours
    if($_POST["SearchInput"] == ""){
        $SEARCH2HOURAGO = "SELECT *
        FROM CAB_DATABASE
        WHERE STATE = 'Unassigned'
        AND CONCAT( PICKDATE, ' ', PICKTIME )
        BETWEEN NOW( )
        AND NOW( ) + INTERVAL 2 HOUR ";
        
        $result = mysqli_query($dbConnect, $SEARCH2HOURAGO);
        showResult($result);
    }
    //Change status
    else if($_POST["SearchInput"] == "NotNull" && isset($_POST['AssignBRN'])){
        $AssignBRN = $_POST["AssignBRN"];
        //using mysqli_real_escape_string keep the input safe and avoid the hacking
        $AssignBRN=mysqli_real_escape_string($dbConnect,$AssignBRN);
        $UPDATESTATE = "UPDATE CAB_DATABASE SET STATE = 'Assigned' WHERE BOOKINGNUMBER = '$AssignBRN'";
        $UPDATEQUERY = mysqli_query($dbConnect, $UPDATESTATE);
        if(! $UPDATEQUERY){
            echo "ERROR! Can not assign!";
        }else{
            $Todate = date('Y-m-d');
            $Hour =date('H');
            $TowHourLater = $Hour + 2;
            $Minute = date('i');
            $NowTime = $Hour.':'.$Minute;
            $TwoHourLaterTime = $TowHourLater.':'.$Minute;

            $SEARCH2HOURAGO = "SELECT * FROM CAB_DATABASE WHERE PICKTIME <= '$TwoHourLaterTime' AND PICKTIME >= '$NowTime' AND PICKDATE = '$Todate' AND STATE = 'NOTASSIGN' ";
            $result = mysqli_query($dbConnect, $SEARCH2HOURAGO);
            showResult($result);
            echo "<h1>Congratulations! Booking request " . $AssignBRN ." has been assigned! </h1>";
        }
    }
    //Show search deatil
    else{
        //using mysqli_real_escape_string keep the input safe and avoid the hacking
        $SearchInput=mysqli_real_escape_string($dbConnect,$_POST["SearchInput"]);
        $SEARCH = "SELECT * FROM CAB_DATABASE WHERE BOOKINGNUMBER = '$SearchInput'";
        $result = mysqli_query($dbConnect, $SEARCH);
        //check if the record is exist
        showResult($result);
    }
    
    //function for show booking detail
    function showResult($input){
        if(mysqli_num_rows($input)>=1){
            //generate the table to show record
            echo "<table border='1'>";
            echo "<tr>";
            echo "<td>Booking reference number</td>";
            echo "<td>Customer name</td>";
            echo "<td>Phone</td>";
            echo "<td>Unit Numnber</td>";
            echo "<td>Street Number</td>";
            echo "<td>Street Name</td>";
            echo "<td>Pickup suburb</td>";
            echo "<td>Destination suburb</td>";
            echo "<td>Pickup date and time </td>";
            echo "<td>Status</td>";
            echo "<td>Assign</td>";
            echo "</tr>";
            while($row = mysqli_fetch_array($input)){
                echo "<tr>";
                echo "<td>",$row["BOOKINGNUMBER"],"</td>";
                echo "<td>",$row["CNAME"],"</td>";
                echo "<td>",$row["CPHONE"],"</td>";
                echo "<td>",$row["UNUMBER"],"</td>";
                echo "<td>",$row["SNUMBER"],"</td>";
                echo "<td>",$row["SNAME"],"</td>";
                echo "<td>",$row["SUB"],"</td>";
                echo "<td>",$row["DESSUB"],"</td>";
                echo "<td>",$row["PICKDATE"] ," ", $row["PICKTIME"],"</td>";
                echo "<td>",$row["STATE"],"</td>";
                //Change button by checking the status of booking
                if($row["STATE"] == "Assigned"){
                    echo "<td> Assigned </td>";
                }else{
                    echo "<td><input type='button' name='sbutton' id='Assign' onClick=AssignCab('$row[BOOKINGNUMBER]') value='Assign'></input></td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }else if (mysqli_num_rows($input)<1){
            echo "<p>No Record. Please try a different keyword.</p>";
        }else{
            "<p>Error. Can not search.</p>";
        }

        //relase the result which contain the num_row info
        mysqli_free_result($input);
    }

    //disconnect to the database
    $dbConnect->close();
?>