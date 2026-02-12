<?php
    require_once("data/db.php");
   session_start();
   session_regenerate_id();


   $dbStatement = $db->prepare("SELECT * FROM programs  WHERE progid = :progid");
    $dbStatement->execute(['progid' => $_GET['progid']]);
    $department = $dbStatement->fetch();
?>

<!--
+---------------+-------------+------+-----+---------+-------+
 | Field         | Type        | Null | Key | Default | Extra |
+---------------+-------------+------+-----+---------+-------+
| studid        | int         | NO   | PRI | NULL    |       |
| studfirstname | varchar(50) | NO   |     | NULL    |       |
| studlastname  | varchar(50) | NO   |     | NULL    |       |
| studmidname   | varchar(50) | YES  |     | NULL    |       |
| studprogid    | int         | NO   |     | NULL    |       |
| studcollid    | int         | NO   | MUL | NULL    |       |
| studyear      | int         | NO   |     | NULL    |       |
+---------------+-------------+------+-----+---------+-------+ -->


    <h1>Student Create for Program <?= $department['progshortname'] ?></h1>
    <span>
        <?php echo $_SESSION['messages']['createSuccess'] ?? null; ?>
        <?php echo $_SESSION['messages']['createError'] ?? null; ?>
    </span>
    <form action="index.php?section=students&page=processStudentData" method="post">
        <table>
            <tr>
                <td style="width: 10em;">Student ID:</td>
                <td style="width: 30em;"><input type="text" id="studentID" name="studentID" value="<?= $_SESSION['input']['studentID'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['studentID'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Student First Name:</td>
                <td><input type="text" id="studentFirstName" name="studentFirstName" value="<?= $_SESSION['input']['studentFirstName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['studentFirstName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Student Middle Name:</td>
                <td><input type="text" id="studentMiddleName" name="studentMiddleName" value="<?= $_SESSION['input']['studentMiddleName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['studentMiddleName'] ?? null; ?>
                    </span>
                </td>
            </tr>
              <tr>
                <td>Student Last Name:</td>
                <td><input type="text" id="studentLastName" name="studentLastName" value="<?= $_SESSION['input']['studentLastName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['studentLastName'] ?? null; ?>
                    </span>
                </td>
            </tr>
               <tr>
                <td>Student College ID:</td>
                <td><input readonly type="text" id="studentCollID" name="studentCollID"  value="<?= $department['progcollid']; ?>"  class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['studentCollID'] ?? null; ?>
                    </span>
                </td>
            </tr>
             <tr>
                <td>Program ID:</td>
                <td><input readonly type="text" id="programID" name="programID" value="<?= $_GET['progid']; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['programID'] ?? null; ?>
                    </span>
                </td>
            <tr>
            <tr>
                <td>Department ID:</td>
                <td><input readonly type="text" id="departmentID" name="departmentID"  value="<?= $department['progcolldeptid']; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['departmentID'] ?? null; ?>
                    </span>
                </td>
            <tr>
                   <tr>
                <td>Student Year:</td>
                <td><input type="text" id="studyear" name="studyear" value="<?= $_SESSION['input']['studyear'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['studyear'] ?? null; ?>
                    </span>
                </td>
            <tr>
                <td colspan="2">
                    <button type="submit" name="saveNewStudentEntry" class="btn">
                        Save New Student Entry
                    </button>
                    <button type="submit" name="clearEntries" class="btn">
                        Reset Form
                    </button>
                    <a href="index.php?section=students&page=studentsList&progid=<?= $_GET['progid']; ?>" class="btn btn-danger">
                        Exit
                    </a>
                </td>
            </tr>
        </table>
    </form>    
<!-- </body>
</html> -->