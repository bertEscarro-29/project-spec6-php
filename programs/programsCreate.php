<?php
    require_once("data/db.php");
   session_start();
   session_regenerate_id();


   $dbStatement = $db->prepare("SELECT * FROM departments  WHERE deptid = :deptid");
    $dbStatement->execute(['deptid' => $_GET['deptid']]);
    $department = $dbStatement->fetch();
?>




    <h1>Program Create for Department <?= $department['deptcollid'] ?></h1>
    <span>
        <?php echo $_SESSION['messages']['createSuccess'] ?? null; ?>
        <?php echo $_SESSION['messages']['createError'] ?? null; ?>
    </span>
    <form action="index.php?section=programs&page=processProgramsData" method="post">
        <table>
            <tr>
                <td style="width: 10em;">Program ID:</td>
                <td style="width: 30em;"><input type="text" id="programID" name="programID" value="<?= $_SESSION['input']['programID'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['programID'] ?? null; ?>
                    </span>
                </td>
            </tr>
             <tr>
                <td>Department ID:</td>
                <td><input readonly type="text" id="departmentID" name="departmentID" value="<?= $_GET['deptid']; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['departmentID'] ?? null; ?>
                    </span>
                </td>
            </tr>
             <tr>
                <td>College ID:</td>
                <td><input readonly type="text" id="collegeID" name="collegeID" value="<?= $department['deptcollid']; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['collegeID'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Program Full Name:</td>
                <td><input type="text" id="programFullName" name="programFullName" value="<?= $_SESSION['input']['programFullName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['programFullName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Program Short Name:</td>
                <td><input type="text" id="programShortName" name="programShortName" value="<?= $_SESSION['input']['programShortName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['programShortName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" name="saveNewProgramEntry" class="btn">
                        Save New Program Entry
                    </button>
                    <button type="submit" name="clearEntries" class="btn">
                        Reset Form
                    </button>
                    <a href="index.php?section=programs&page=programsList&deptid=<?= $_GET['deptid'] ?? null; ?>" class="btn btn-danger">
                        Exit
                    </a>
                </td>
            </tr>
        </table>
    </form>    
<!-- </body>
</html> -->