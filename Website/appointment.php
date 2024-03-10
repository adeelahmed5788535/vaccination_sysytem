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
</style>
<body>
    <?php
        include("header.php");
    ?>
    <div class="mainContent">
      <div class="leftSide">
        <h2>Book Appointment</h2>
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
            <input type="date" name="date">
            <select name="time">
                <option hidden>Select Time</option>
                <option>9-11</option>
                <option>11-1</option>
                <option>1-3</option>
                <option>3-5</option>
                <option>5-7</option>
            </select>
            <select name="vid">
                <option hidden>Select Any Vaccine</option>
                <?php 
                    $query = "SELECT * FROM tbl_vaccine WHERE status='available'";
                    $result = mysqli_query($connection,$query);
                    foreach($result as $row)
                    {
                        echo "<option value='$row[id]'>$row[name]</option>";
                    }
                ?>
            </select>
            <input type="submit" value="Book Appointment" name="btnbook">
        </form>
        <?php
            if(isset($_POST['btnbook']))
            {
                $pid = $_POST['pid'];
                $hid = $_POST['hid'];
                $date = $_POST['date'];
                $time = $_POST['time'];
                $vid = $_POST['vid'];
                
                $existing_appointment = mysqli_query($connection,"SELECT * FROM tbl_appointment WHERE h_id='$hid' and date='$date' and time='$time' and p_id='$_SESSION[patient_session]'");

                if(mysqli_num_rows($existing_appointment)>0)
                {
                    echo 
                    "<script>
                        alert('Appointment Already Exist');
                    </script>";
                }
                else
                {
                  $current_vaccine = mysqli_query($connection, "SELECT * FROM tbl_appointment WHERE p_id='$_SESSION[patient_session]'");
                  $existing_vaccine = mysqli_fetch_assoc($current_vaccine);

                  if (empty($existing_vaccine) || $existing_vaccine['v_id'] == $vid) 
                  {
                      $query = "INSERT INTO tbl_appointment(p_id, h_id, date, time, v_id) VALUES ('$pid', '$hid', '$date', '$time', '$vid')";
                      $result = mysqli_query($connection, $query);
                      if ($result) {
                          echo "<script>
                                  alert('Appointment Booked Successfully');
                                  window.location.href='appointment.php'
                                </script>";
                      }
                  } 
                  else 
                  {
                      echo "<script>
                              alert('You have to Select Same Vaccine');
                              window.location.href='appointment.php'
                            </script>";
                  }

                }
            }
        
        ?>
      </div>
      <div class="rightSide">
      <h2>Your Appointment</h2>
       <table>
        <thead>
            <tr>
                <th>Hospital Name</th>
                <th>Vaccine Name</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $query = "SELECT tbl_hospital.name as 'hname', tbl_vaccine.name as 'vname', tbl_appointment.* FROM tbl_appointment INNER JOIN tbl_hospital ON tbl_appointment.h_id = tbl_hospital.id INNER JOIN tbl_vaccine ON tbl_appointment.v_id = tbl_vaccine.id INNER JOIN tbl_patient ON tbl_appointment.p_id = tbl_patient.id WHERE tbl_patient.id=$_SESSION[patient_session]";
                $result = mysqli_query($connection,$query);
                foreach($result as $row)
                {
                    echo 
                    "<tr>
                        <td>$row[hname]</td>
                        <td>$row[vname]</td>
                        <td>$row[date]</td>
                        <td>$row[time]</td>
                        <td>$row[status]</td>
                    </tr>";
                }
            ?>
        </tbody>
       </table>
      </div>
    </div>
</body>
</html>