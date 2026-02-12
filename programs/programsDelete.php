<?php
    require_once("data/db.php");
    session_start();
    session_regenerate_id();
    // index.php?section=programs&page=programsList&deptid=4001
    $programID = $_GET['progid'];

    $dbStatement = $db->prepare("SELECT * FROM programs WHERE progid = :programID");
    $dbStatement->execute(['programID' => $programID]);
    $program = $dbStatement->fetch();
?>
<h1>Program Delete</h1>
<span>
    <?php echo $_SESSION['messages']['updateSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['updateError'] ?? null; ?>
</span>
<form action="index.php?section=programs&page=processDataDelete" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Program ID:</td>
            <td style="width: 30em;"><input type="text" id="programID" name="programID" value="<?php echo $program['progid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Program Full Name:</td>
            <td><input type="text" id="programFullName" name="programFullName" value="<?php echo $program['progfullname']; ?>" readonly class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['programFullName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="deptShortName" name="deptShortName" value="<?php echo $program['progshortname']; ?>" readonly class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['deptShortName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=programs&page=programsList&deptid=<?php echo $program['progcolldeptid']; ?>" class="btn btn-primary">
                    Cancel Operation
                </a>                
                <button type="submit" name="confirmDelete" class="btn btn-danger">
                    Confirm Operation
                </button>
            </td>
        </tr>
    </table>
</form>    
