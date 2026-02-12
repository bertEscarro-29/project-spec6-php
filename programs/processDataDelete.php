<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];


if($_POST && isset($_POST['confirmDelete'])){
    $programID = $_POST['programID'];
    
  
        //check if the department entry has associated student entries, if it does, prevent deletion and return an error message

        $dbStatement = $db->prepare("SELECT * FROM students WHERE studprogid = :programID");
        $dbStatement->execute(['programID' => $programID]);
        $programs = $dbStatement->fetchAll(PDO::FETCH_ASSOC);

        if(count($programs) > 0){
            $_SESSION['messages']['updateError'] = "Cannot delete program entry because it has associated student entries";
            $_SESSION['messages']['updateSuccess'] = "";
            header("Location: $entryURL", true, 301);
            exit();
        }

            $dbStatement = $db->prepare('DELETE FROM programs WHERE progid = ?');
            $dbResult = $dbStatement->execute([
                $programID
            ]);

            if($dbResult){
                $_SESSION['messages']['updateSuccess'] = "Program entry deleted successfully";
                $_SESSION['messages']['updateError'] = "";
            } else {
                $_SESSION['messages']['updateError'] = "Failed to delete program entry";
                $_SESSION['messages']['updateSuccess'] = "";
            }

        header("Location: $entryURL", true, 301);

       
    }

?>