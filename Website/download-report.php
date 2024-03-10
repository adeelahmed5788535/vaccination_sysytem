<?php
  include("../Admin/connection.php");
  session_start();
  if(!isset($_SESSION['patient_session']))
  {
    echo "<script>window.location.href='login.php'</script>";
  }
  $test_id = $_GET['id'];
  $query = "SELECT tbl_patient.name as 'pname',tbl_hospital.name as 'hname', tbl_test.* FROM tbl_test INNER JOIN tbl_hospital ON tbl_test.h_id = tbl_hospital.id INNER JOIN tbl_patient ON tbl_test.p_id = tbl_patient.id WHERE tbl_test.id = $test_id";
  $result = mysqli_query($connection,$query);
  $test_details = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    body
    {
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
  .reportCard
  {
    height: 400px;
    display: inline-block;
    border: 2px solid #000;
    padding: 20px;
  }
  .reportCard h2
  {
    text-align: center;
    font-size: 45px;
    padding-bottom: 10px;
    border-bottom: 1px solid #000;
  }
  .reportCard h4
  {
    margin: 30px 0px;
    font-size: 30px;
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
    <div class="reportCard">
        <h2><b>COVID Test Report</b></h2>
        <h4>Patient Name : <?php echo $test_details['pname'];?></h4>
        <h4>Hospital Name : <?php echo $test_details['hname'];?></h4>
        <h4>Date : <?php echo $test_details['date'];?></h4>
        <h4>Result : <?php echo $test_details['result'];?></h4>
    </div>
    <script>
        window.onload(print())
    </script>
</body>
</html>