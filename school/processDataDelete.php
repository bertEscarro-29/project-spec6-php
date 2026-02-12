<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];


if($_POST && isset($_POST['confirmDelete'])){
    $schoolID = $_POST['schoolID'];
    
  
        //check if the school entry has associated department entries, if it does, prevent deletion and return an error message

        $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptcollid = :schoolID");
        $dbStatement->execute(['schoolID' => $schoolID]);
        $departments = $dbStatement->fetchAll(PDO::FETCH_ASSOC);

        if(count($departments) > 0){
            $_SESSION['messages']['updateError'] = "Cannot delete school entry because it has associated department entries";
            $_SESSION['messages']['updateSuccess'] = "";
            header("Location: $entryURL", true, 301);
            exit();
        }

            $dbStatement = $db->prepare('DELETE FROM colleges WHERE collid = ?');
            $dbResult = $dbStatement->execute([
                $schoolID
            ]);

            if($dbResult){
                $_SESSION['messages']['updateSuccess'] = "School entry deleted successfully";
                $_SESSION['messages']['updateError'] = "";
            } else {
                $_SESSION['messages']['updateError'] = "Failed to delete school entry";
                $_SESSION['messages']['updateSuccess'] = "";
            }

        header("Location: $entryURL", true, 301);

       
    }

?>