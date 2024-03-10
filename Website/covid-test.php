<?php

  include("../Admin/connection.php");
  session_start();
  if(!isset($_SESSION['patient_session']))
  {
    echo "<script>window.location.href='login.php'</script>";
  }
  $query = "SELECT * FROM tbl_patient WHERE id=$_SESSION[patient_session]";
  $result = mysqli_query($connection,$query);
  $patient = mysqli_fetch_assoc($result);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="images/favicon.png" type="">

  <title>User Profile </title>

  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <link href="css/font-awesome.min.css" rel="stylesheet" />

  <!-- Custom styles for this template -->
  <link href="css/style.css" rel="stylesheet" />
  <!-- responsive style -->
  <link href="css/responsive.css" rel="stylesheet" />

</head>
<style>
  header
  {
    background-color: #178066;
  }
  .mainContent
  {
    display: flex;
    justify-content: space-between;
    padding: 50px;
  }
  .mainContent .leftSide
  {
    width: 45%;
  }
  .mainContent .leftSide input,
  .mainContent .leftSide select
  {
    width: 100%;
    padding: 10px;
    border: 1px solid lightgray;
    outline: none;
    background-color: #eee;
    border-radius: 8px;
    margin: 10px 0px;
  }
  .mainContent .leftSide input[type="submit"]
  {
    background-color: #178066;
    color: #fff;
    font-size: 20px;
  }
  .mainContent .rightSide
  {
    width: 50%;
  }
  .mainContent .rightSide .image
  {
    margin-bottom: 20px;
    height: 435px;
    width: 435px;
  }
  .mainContent .rightSide table
  {
    width: 100%;
    margin-top: 17px;
    text-align: center;
  }
  .mainContent .rightSide table,tr,th,td
  {
    border: 1px solid lightgray;
    padding: 8px;
  }
  .mainContent .rightSide table thead
  {
    background-color: #178066;
    color: #fff;
  }
  .reportCard
  {
    display: inline-block;
    border: 2px solid #000;
    padding: 20px;
    margin: 30px;
    visibility: hidden;
  }
  .reportCard h2
  {
    text-align: center;
    font-size: 36px;
    padding-bottom: 10px;
    border-bottom: 1px solid #000;
  }
  .reportCard h4
  {
    margin: 20px 0px;
  }
  @media print
  {
    .reportCard
    {
      visibility: visible;
    }
    .mainContent
    {
      display: none;
    }
  }
</style>
<body>
    <?php
        include("header.php");
    ?>
    <div class="mainContent">
      <div class="leftSide">
        <h2>Apply For Covid Test</h2>
        <form method="post">
            <input type="hidden" readonly value="<?php echo $patient['id'];?>" name="pid">
            <input type="text" readonly value="<?php echo $patient['name'];?>">
            <select name="hid">
                <option hidden>Select Any Hospital</option>
                <?php 
                    $query = "SELECT * FROM tbl_hospital WHERE status='activate'";
                    $result = mysqli_query($connection,$query);
                    foreach($result as $row)
                    {
                        echo "<option value='$row[id]'>$row[name]</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Apply For Test" name="btnbook">
        </form>
        <?php
            if(isset($_POST['btnbook']))
            {
                $pid = $_POST['pid'];
                $hid = $_POST['hid'];
                
                $query = "INSERT INTO tbl_test(p_id,h_id) VALUES($pid,$hid)";
                $result = mysqli_query($connection,$query);
                if($result)
                {
                    echo 
                    "<script>
                        alert('Request For Covid Test Has Been Sent');
                        window.location.href='covid-test.php';
                    </script>";
                }
            }
        
        ?>
      </div>
      <div class="rightSide">
      <h2>Your Current Test Status</h2>
       <table>
        <thead>
            <tr>
                <th>Hospital Name</th>
                <th>Patient Name</th>
                <th>Result</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $query = "SELECT tbl_patient.name as 'pname',tbl_hospital.name as 'hname', tbl_test.* FROM tbl_test INNER JOIN tbl_hospital ON tbl_test.h_id = tbl_hospital.id INNER JOIN tbl_patient ON tbl_test.p_id = tbl_patient.id";
                $result = mysqli_query($connection,$query);
                $patient_details = mysqli_fetch_assoc($result);
                foreach($result as $row)
                {
                    echo 
                    "<tr>
                        <td>$row[hname]</td>
                        <td>$row[pname]</td>
                        <td>$row[result]</td>";
                        if($row['result']!="Process")
                        {
                            echo 
                            "<td>
                                <a href='download-report.php?id=$row[id]'>Download Report</a>
                            </td>";
                        }   
                    "</tr>";
                }
            ?>
        </tbody>
       </table>
      </div>
    </div>
    <div class="reportCard">
        <h2><b>COVID Test Report</b></h2>
        <h4>Patient Name : <?php echo $patient_details['pname'];?></h4>
        <h4>Hospital Name : <?php echo $patient_details['hname'];?></h4>
        <h4>Date : <?php echo $patient_details['date'];?></h4>
        <h4>Result : <?php echo $patient_details['result'];?></h4>
    </div>
</body>
</html>