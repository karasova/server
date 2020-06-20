<?php 

    include "server.php";

    $events = new Event;
    
    if (!empty($_GET)) {
        if (isset($_GET['action'])) {

            if ($_GET['action'] == "add") {
                echo $events->add_event();
            }

            if ($_GET['action'] == "delete") {
                echo $events->delete();
            }

            if ($_GET['action'] == "update") {
                echo $events->update_db();
            }

            if ($_GET['action'] == "getId") {
                echo $events->read_by_id();         
             
            }

        }  
    }
    else {
        echo $events->read_from_db();
    }  

?>
