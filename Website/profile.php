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
    align-items: center;
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
  .mainContent .rightSide .image img
  {
    object-fit: cover;
    width: 100%;
    height: 100%;
  }
  .mainContent .rightSide input[type="file"]
  {
    margin: 10px 0px;
  }
  .mainContent .rightSide input[type="submit"]
  {
    width: 100%;
    padding: 10px;
    border: 1px solid lightgray;
    outline: none;
    background-color: #eee;
    border-radius: 8px;
    margin: 10px 0px;
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
        <h2>My Profile</h2>
        <form method="post">
          <input type="text" placeholder="Enter Your Name" name="name" value="<?php echo $patient['name']?>"><br>
          <input type="text" placeholder="Enter Phone Number" name="phone" value="<?php echo $patient['contact']?>"><br>
          <input type="text" placeholder="Enter Email Address" name="email" value="<?php echo $patient['email']?>"><br>
          <input type="text" placeholder="Enter Your Password" name="password" value="<?php echo $patient['password']?>"><br>
          <select name="city">
            <option hidden>Select Any City</option>
            <?php 
                $query = "SELECT * FROM tbl_city";
                $result = mysqli_query($connection,$query);
                foreach ($result as $row1) {
                    echo "<option value='".$row1['id']."'";
                    if ($patient['cid'] == $row1['id']) {
                        echo " selected";
                    }
                    echo ">".$row1['name']."</option>";
                }
            ?>
          </select><br>
          <select name="gender">
            <option hidden>Select Any Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
          </select><br>
          <input type="text" placeholder="Enter Your Address" name="address" value="<?php echo $patient['address']?>"><br>
          <input type="submit" value="Update Profile" name="btnupdate">
        </form>
        <?php
          if(isset($_POST['btnupdate']))
          {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $city = $_POST['city'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $query = "UPDATE tbl_patient SET name='$name',contact='$phone',email='$email',password='$password',cid='$city',gender='$gender',address='$address' WHERE id=$_SESSION[patient_session]";
            $result = mysqli_query($connection,$query);
            if($result)
            {
              echo 
              "<script>
                  alert('Profile Updated');
                  window.location.href='profile.php';
              </script>";
            }
          }
        ?>
      </div>
      <div class="rightSide">
        <div class="image">
          <img src="<?php echo $patient['image'];?>">
        </div>
        <form method="post" enctype="multipart/form-data">
          <input type="file" name="image" required><br>
          <input type="submit" value="Upload Image" name="btnupload">
        </form>
        <?php
          if(isset($_POST['btnupload']))
          {
            $imageName = $_FILES['image']['name'];
            $tmpName = $_FILES['image']['tmp_name'];
            $path = "images/patient_images/$imageName";
            move_uploaded_file($tmpName,$path);
            $query = "UPDATE tbl_patient SET image='$path' WHERE id=$_SESSION[patient_session]";
            $result = mysqli_query($connection,$query);
            if($result)
            {
              echo 
              "<script>
                  alert('Picture Updated');
                  window.location.href='profile.php';
              </script>";
            }
          }
        ?>
      </div>
    </div>

</body>
</html>