


<?php
   require_once("data/db.php");
   session_start();
   session_destroy();

   $limit = 5;

   $dbStatement = $db->prepare("SELECT * FROM departments  WHERE deptid = :deptid");
   $dbStatement->execute(['deptid' => $_GET['deptid']]);
   $school = $dbStatement->fetch();

   $dbStatement = $db->prepare("SELECT * from departments d JOIN programs p WHERE d.deptid = p.progcolldeptid AND deptid = :deptid");
   $dbStatement->execute(['deptid' => $_GET['deptid']]);
   $totalSchools = $dbStatement->rowCount();

   $totalPages = ceil($totalSchools / $limit);


   if(!isset($_GET['pgSection']) || !is_numeric($_GET['pgSection'])) {
       $currentPage = 1;
   } else {
       $currentPage = intval($_GET['pgSection']);
   }

   $offset = ($currentPage - 1) * $limit;

   $dbStatement = $db->prepare("SELECT * FROM departments d JOIN programs p ON d.deptid = p.progcolldeptid WHERE d.deptid = :deptid ORDER BY p.progid LIMIT :offset, :limit;");
   $dbStatement->bindParam('deptid', $_GET['deptid'], PDO::PARAM_INT);
   $dbStatement->bindParam('offset', $offset, PDO::PARAM_INT);
   $dbStatement->bindParam('limit', $limit, PDO::PARAM_INT);
   $dbStatement->execute();


   
   $departments = $dbStatement->fetchAll();
?>


<h1>Program List - <?php echo $school['deptfullname']; ?></h1>
<div>
    <h2><a href="index.php?section=programs&page=programsCreate&deptid=<?php echo $_GET['deptid']; ?>" class="btn btn-primary">Create Program</a></h2>
</div>
<table>
    <tr>
        <th>Program ID</th>
        <th>Program Full Name</th>
        <th>Program Short Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($departments as $department): ?>
    <tr>
        <td><?php echo $department['progid']; ?></td>
        <td><?php echo $department['progfullname']; ?></td>
        <td><?php echo $department['progshortname']; ?></td>
        <td>
            
            <a href="index.php?section=programs&page=programsUpdate&deptid=<?php echo $department['deptid']; ?>&progid=<?php echo $department['progid']; ?>" class="btn btn-info">Update</a>
            <a href="index.php?section=programs&page=programsDelete&progid=<?php echo $department['progid']; ?>" class="btn btn-danger">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
     <tr>
        <td colspan="2">
            <span>
                Total of: <?= $totalSchools ?> <?= (count($departments) === 1) ? 'department' : 'departments' ?> in the database
            </span>
        </td>
        <td colspan="2">
            
          <?php if($totalPages > 1): ?>  
            <?php if ($currentPage > 1): ?>
                <a href="index.php?section=programs&page=programsList&deptid=<?php echo $_GET['deptid']; ?>&pgSection=<?= $currentPage - 1 ?>" class="btn btn-primary">Previous</a>
            <?php else: ?>
                <span>Previous</span>
            <?php endif; ?>
            <?php if ($currentPage < $totalPages): ?>
                
                <a href="index.php?section=programs&page=programsList&deptid=<?php echo $_GET['deptid']; ?>&pgSection=<?= $currentPage + 1 ?>" class="btn btn-primary">Next</a>
            <?php else: ?>
                <span>Next</span>
            <?php endif; ?>
          <?php endif; ?>  
        </td>
    </tr>
</table>