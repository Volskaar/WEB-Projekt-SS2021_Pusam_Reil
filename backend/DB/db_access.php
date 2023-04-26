<?php
    /* +++ Database access +++ */
    #usernameAdmin: admin
    #passwordAdmin: 123

    $servername = "localhost";

    $usernameAdmin = "admin";
    $passwordAdmin = "123";

    $database = "appointment_db";

    //create connection
    $connection = new mysqli($servername, $usernameAdmin, $passwordAdmin, $database);

    //check connection
    if ($connection->connect_error) {
        die("Connection failure: " . $conn->connect_error);
        echo("failure");
    }
    
    if ($mysqli->ping()) {
        printf ("Our connection is ok!\n"); 
        } else {
        printf ("Error: %s\n", $mysqli->error); 
        }
    /*close connection
    $connection->close();
    */
?>