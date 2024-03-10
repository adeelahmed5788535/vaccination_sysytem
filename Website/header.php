<header class="header_section">
      <div class="container">
        <nav class="navbar navbar-expand-lg custom_nav-container ">
          <a class="navbar-brand" href="index.html">
            <span>
              Vaccination System
            </span>
          </a>

          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class=""> </span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="about.html"> About</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="departments.html">Hospital</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="doctors.html">Contact Us</a>
              </li>
              <?php
                  if(isset($_SESSION['patient_session']))
                  {
                    echo 
                    "<li class='nav-item'>
                      <a class='nav-link' href='profile.php'>$_SESSION[patient_name]</a>
                    </li>
                    <li class='nav-item'>
                      <a class='nav-link' href='logout.php'><i class='fa fa-sign-out' style='font-size: 22px;'></i></a>
                    </li>";
                  }
                  else
                  {
                    echo 
                    '<li class="nav-item">
                      <a class="nav-link" href="login.php"><i class="fa fa-user" style="font-size: 22px;"></i></a>
                    </li>';
                  }
              ?>
              
            </ul>
          </div>
        </nav>
      </div>
    </header>