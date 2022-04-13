
<?php
require('components/connection.php');

  $sql = "SELECT first_name ,last_name, email, gender, address from user_data order by email asc";
  $query = $con->prepare($sql);
  $query->execute();
  $results=$query->fetchAll(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Registered Users</title>
  <?php include 'components/head.html'; ?>
</head>

<body>

      <!-- navigation bar -->
<?php include "components/header.php" ?>
            <br>
<div class="container">
  <div  class= "home_display">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3><b>Registered Users</b></h3> <hr />
                <div class="table-responsive"><br>               
                <table id="mytable" class="table table-bordred table-striped">                 
                    <thead>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Address</th>
                    </thead>

                    <tbody>
    
                <?php 
                $cnt=1;

                if($query->rowCount() > 0)
                {
                //In case that the query returned at least one record, we can echo the records within a foreach loop:
                foreach($results as $result)
                {               
                ?>  
                <tr>
                    <td><?php echo htmlentities($cnt);?></td>
                    <td><?php echo htmlentities($result->first_name);?></td>
                    <td><?php echo htmlentities($result->last_name);?></td>
                    <td><?php echo htmlentities($result->email);?></td>
                    <td><?php echo htmlentities($result->gender);?></td>
                    <td><?php echo htmlentities($result->address);?></td>
                </tr>
                <?php    $cnt++;
                }}

                else{
                  echo '<strong>No results!</strong><div>'; }
              ?>
                </tbody>     
            </table>
            
            </div>            
            </div>
        </div>
    </div>
</div>
</div>
  <?php include 'components/footer.html'; ?>  
</body>
</html>