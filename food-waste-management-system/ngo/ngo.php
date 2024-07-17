<?php
session_start();
include '../connection.php';

if (!isset($_SESSION['Nid'])) {
    header("Location: signin.php");
    exit();
}

$ngo_id = $_SESSION['Nid'];

// Fetch assigned food requests
$query = "
    SELECT fd.*, dp.name as dp_name, dp.phone as dp_phone 
    FROM food_donations fd 
    LEFT JOIN delivery_persons dp ON fd.delivery_by = dp.Did 
    WHERE fd.assigned_to = '$ngo_id'";
$result = mysqli_query($connection, $query);

// Fetch available food donations
$available_query = "SELECT * FROM food_donations WHERE assigned_to IS NULL";
$available_result = mysqli_query($connection, $available_query);

// Handle food request
if (isset($_POST['request_food'])) {
    $food_id = $_POST['food_id'];
    $update_query = "
        UPDATE food_donations 
        SET assigned_to = '$ngo_id', status = 'assigned' 
        WHERE Fid = '$food_id'";

    if (mysqli_query($connection, $update_query)) {
        echo "<script>alert('Food request submitted successfully.');</script>";
        header("Refresh:0"); // Refresh the page to update the list
    } else {
        echo "<script>alert('Error requesting food.');</script>";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="ngo.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <title>NGO Dashboard</title>
</head>
<body>
    <nav>
        <a href="../logout.php">Logout</a>
        <a href="ngoprofile.php">
            <i class="uil uil-user"></i>
            <span class="link-name">Profile</span>
        </a>
        <h1>NGO Dashboard</h1>
    </nav>
    
    <section class="requests">
        <h2>Assigned Food Donations</h2>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Food</th>
                    <th>Category</th>
                    <th>Phone No</th>
                    <th>Date/Time</th>
                    <th>Address</th>
                    <th>Quantity</th>
                    <th>Delivery Person</th>
                    <th>Delivery Phone</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['food']); ?></td>
                    <td><?php echo htmlspecialchars($row['category']); ?></td>
                    <td><?php echo htmlspecialchars($row['phoneno']); ?></td>
                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($row['dp_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['dp_phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>

    <section class="requests">
        <h2>Available Food Donations</h2>
        <table>
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
                <?php while ($row = mysqli_fetch_assoc($available_result)) { ?>
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
                            <input type="hidden" name="food_id" value="<?php echo $row['Fid']; ?>">
                            <button type="submit" name="request_food">Request Food</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</body>
</html>