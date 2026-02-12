<?php
   require_once("data/db.php");
   session_start();
   session_destroy();

   $limit = 5;

   $dbStatement = $db->prepare("SELECT * FROM programs  WHERE progid = :progid");
   $dbStatement->execute(['progid' => $_GET['progid']]);
   $school = $dbStatement->fetch();

   $dbStatement = $db->prepare("SELECT * from students s JOIN programs p WHERE s.studprogid = p.progid AND p.progid = :progid");
   $dbStatement->execute(['progid' => $_GET['progid']]);
   $totalSchools = $dbStatement->rowCount();

   $totalPages = ceil($totalSchools / $limit);


   if(!isset($_GET['pgSection']) || !is_numeric($_GET['pgSection'])) {
       $currentPage = 1;
   } else {
       $currentPage = intval($_GET['pgSection']);
   }

   $offset = ($currentPage - 1) * $limit;

   $dbStatement = $db->prepare("SELECT * from students s JOIN programs p WHERE s.studprogid = p.progid AND p.progid = :progid ORDER BY p.progid LIMIT :offset, :limit;");
   $dbStatement->bindParam('progid', $_GET['progid'], PDO::PARAM_INT);
   $dbStatement->bindParam('offset', $offset, PDO::PARAM_INT);
   $dbStatement->bindParam('limit', $limit, PDO::PARAM_INT);
   $dbStatement->execute();


   
   $departments = $dbStatement->fetchAll();
?>

<h1>Student Lists - <?php echo $school['progfullname']; ?></h1>
<div>
    <h2><a href="index.php?section=students&page=studentCreate&progid=<?php echo $_GET['progid']; ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Create Student</a></h2>
</div>
<table>
    <tr>
        <th>Student  ID</th>
        <th>Student Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($departments as $department): ?>
    <tr>
        <td><?php echo $department['studid']; ?></td>
        <td><?php echo $department['studfirstname'] . ' ' . $department['studlastname']; ?></td>
        
        <td>
            <a href="index.php?section=students&page=studentUpdate&progid=<?php echo $_GET['progid']; ?>&studid=<?php echo $department['studid']; ?>" class="btn btn-info"><i class="fas fa-edit"></i> Edit</a>
            <a href="index.php?section=students&page=studentDelete&studid=<?php echo $department['studid']; ?>&progid=<?php echo $_GET['progid']; ?>" class="btn btn-danger"><i class="fas fa-trash"></i> Delete</a>
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
                <a href="index.php?section=students&page=studentList&progid=<?php echo $_GET['progid']; ?>&pgSection=<?= $currentPage - 1 ?>" class="btn btn-primary">Previous</a>
            <?php else: ?>
                <span>Previous</span>
            <?php endif; ?>
            <?php if ($currentPage < $totalPages): ?>
                
                <a href="index.php?section=students&page=studentList&progid=<?php echo $_GET['progid']; ?>&pgSection=<?= $currentPage + 1 ?>" class="btn btn-primary">Next</a>
            <?php else: ?>
                <span>Next</span>
            <?php endif; ?>
          <?php endif; ?>  
        </td>
    </tr>
    
</table>