<?php
session_start();
include '../connection.php';

// Check if the user is logged in and has an NGO profile
if (!isset($_SESSION['Nid'])) {
    header("Location: signin.php");
    exit();
}

$ngo_id = $_SESSION['Nid'];

// Fetch NGO details
$query = "SELECT * FROM ngo WHERE Nid = '$ngo_id'";
$ngo_result = mysqli_query($connection, $query);
$ngo_data = mysqli_fetch_assoc($ngo_result);

// Fetch assigned food requests
$assigned_query = "SELECT * FROM food_donations WHERE assigned_to = '$ngo_id'";
$assigned_result = mysqli_query($connection, $assigned_query);

// Fetch pending food requests
$pending_query = "SELECT * FROM food_donations WHERE status = 'pending'";
$pending_result = mysqli_query($connection, $pending_query);

// Handle acceptance of food request
if (isset($_POST['accept'])) {
    $donation_id = $_POST['donation_id'];
    $update_query = "UPDATE food_donations SET status = 'assigned', assigned_to = '$ngo_id' WHERE id = '$donation_id'";
    mysqli_query($connection, $update_query);
    header("Location: ngo_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ngo_profile.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>NGO Profile</title>
</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <!-- <img src="images/logo.png" alt=""> -->
            </div>
            <span class="logo_name">NGO</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="ngo_profile.php">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Profile</span>
                </a></li>
                <li><a href="ngo_requests.php">
                    <i class="uil uil-archive"></i>
                    <span class="link-name">Requests</span>
                </a></li>
                <li><a href="logout.php">
                    <i class="uil uil-signout"></i>
                    <span class="link-name">Logout</span>
                </a></li>
            </ul>
        </div>
    </nav>

    <section class="profile">
        <div class="top">
            <h1 class="page-title">NGO Profile</h1>
        </div>
        <div class="profile-details">
            <h2>NGO Details</h2>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($ngo_data['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($ngo_data['email']); ?></p>
            <p><strong>Phone No:</strong> <?php echo htmlspecialchars($ngo_data['phone']); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($ngo_data['city']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($ngo_data['address']); ?></p>
        </div>

        <div class="food-requests">
            <h2>Pending Food Requests</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Food</th>
                        <th>Category</th>
                        <th>Phone No</th>
                        <th>Date/Time</th>
                        <th>Address</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($pending_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['food']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['phoneno']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="donation_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" name="accept">Accept</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>

            <h2>Assigned Food Requests</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Food</th>
                        <th>Category</th>
                        <th>Phone No</th>
                        <th>Date/Time</th>
                        <th>Address</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($assigned_result)) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['food']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td><?php echo htmlspecialchars($row['phoneno']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
