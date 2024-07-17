<?php
include "login.php"; 

if ($_SESSION['name'] == '') {
    header("location: index.html");
}

$emailid = $_SESSION['email'];
$connection = mysqli_connect("localhost", "root", "", "food");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
        </ul>
    </nav>
</header>
<script>
    hamburger = document.querySelector(".hamburger");
    hamburger.onclick = function() {
        navBar = document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>

<div class="profile">
    <div class="profilebox">
        <p class="headingline" style="text-align: left; font-size:30px;"> 
            <img src="" alt="" style="width:40px; height: 25px; padding-right: 10px; position: relative;">Profile
        </p>
        <div class="info" style="padding-left:10px;">
            <p>Name: <?php echo $_SESSION['name']; ?></p><br>
            <p>Email: <?php echo $_SESSION['email']; ?></p><br>
            <a href="logout.php" style="float: left; margin-top: 6px; border-radius: 5px; background-color: #06C167; color: white; padding: 10px;">Logout</a>
        </div>
        <br><br>
        <hr><br>
        <p class="heading">Your donations</p>
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Date/Time</th>
                            <th>NGO person name</th>
                            <th>Delivery person name</th>
                            <th>Delivery person phone no</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT fd.*,n.name AS nname,d.name AS dname,d.phone AS dphone FROM food_donations fd LEFT JOIN ngo n ON 
                        fd.assigned_to = n.Nid LEFT JOIN delivery_persons d ON fd.delivery_by = d.Did WHERE (fd.assigned_to IS NULL OR
                        fd.assigned_to = n.Nid) AND (fd.delivery_by IS NULL OR fd.delivery_by=d.Did)";
                        $result = mysqli_query($connection, $query);
                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td>{$row['food']}</td>
                                        <td>{$row['type']}</td>
                                        <td>{$row['category']}</td>
                                        <td>{$row['date']}</td>
                                        <td>{$row['nname']}</td>
                                        <td>{$row['dname']}</td>
                                        <td>{$row['dphone']}</td>
                                        <td>{$row['status']}</td>
                                      </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
