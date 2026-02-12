<?php
    require_once("data/db.php");
    session_start();
    session_regenerate_id();
    // index.php?section=department&page=departmentList&deptcollid=5
    $departmentID = $_GET['deptid'];

    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptid = :departmentID");
    $dbStatement->execute(['departmentID' => $departmentID]);
    $department = $dbStatement->fetch();
?>
<h1>Department Delete</h1>
<span>
    <?php echo $_SESSION['messages']['updateSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['updateError'] ?? null; ?>
</span>
<form action="index.php?section=department&page=processDataDelete" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Department ID:</td>
            <td style="width: 30em;"><input type="text" id="departmentID" name="departmentID" value="<?php echo $department['deptid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Department Full Name:</td>
            <td><input type="text" id="departmentFullName" name="departmentFullName" value="<?php echo $department['deptfullname']; ?>" readonly class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['departmentFullName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="deptShortName" name="deptShortName" value="<?php echo $department['deptshortname']; ?>" readonly class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['deptShortName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=department&page=departmentList&deptcollid=<?php echo $department['deptcollid']; ?>" class="btn btn-primary">
                    Cancel Operation
                </a>                
                <button type="submit" name="confirmDelete" class="btn btn-danger">
                    Confirm Operation
                </button>
            </td>
        </tr>
    </table>
</form>    
