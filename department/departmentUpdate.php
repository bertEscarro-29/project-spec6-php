<?php
    require_once("data/db.php");
    session_start();
    session_regenerate_id();

    $deptid = $_GET['deptid'];
    $depcollid = $_GET['collid'];

    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptid = :deptid");
    $dbStatement->execute(['deptid' => $deptid]);
    $school = $dbStatement->fetch();
?>
<h1>Department Update</h1>
<span>
    <?php echo $_SESSION['messages']['updateSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['updateError'] ?? null; ?>
</span>
<form action="index.php?section=department&page=processDataChanges" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Department ID:</td>
            <td style="width: 30em;"><input type="text" id="deptid" name="deptid" value="<?php echo $school['deptid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Department Full Name:</td>
            <td><input type="text" id="deptFullName" name="deptFullName" value="<?php echo $school['deptfullname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['deptFullName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="deptShortName" name="deptShortName" value="<?php echo $school['deptshortname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['deptShortName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveChanges" class="btn">
                    Update Department Entry
                </button>
                <button type="submit" name="clearChanges" class="btn">
                    Reset Form
                </button>
                <a href="index.php?section=department&page=departmentList&deptcollid=<?php echo $depcollid; ?>" class="btn btn-danger">
                    Exit
                </a>
            </td>
        </tr>
    </table>
</form>    
