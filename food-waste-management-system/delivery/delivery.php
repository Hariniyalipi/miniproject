<?php
ob_start(); 
// $connection = mysqli_connect("localhost:3307", "root", "");
// $db = mysqli_select_db($connection, 'demo');
include("connect.php"); 
include '../connection.php';
if($_SESSION['name']==''){
	header("location:deliverylogin.php");
}
$name=$_SESSION['name'];
$city=$_SESSION['city'];
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"http://ip-api.com/json");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$result=curl_exec($ch);
$result=json_decode($result);
// $city= $result->city;
// echo $city;

$id=$_SESSION['Did'];
// Fetch unassigned orders for the city
$sql = "
    SELECT fd.Fid AS Fid, fd.location as cure, fd.name, fd.phoneno, fd.date, fd.delivery_by, 
           fd.address as From_address, ng.name AS NGO, 
           ng.address AS To_address,ng.phone as NGO_phone , fd.status
    FROM food_donations fd
    LEFT JOIN ngo ng ON fd.assigned_to = ng.Nid
    WHERE fd.assigned_to IS NOT NULL 
    AND fd.delivery_by IS NULL 
    AND fd.status='assigned';
";
$result = mysqli_query($connection, $sql);

// Fetch assigned orders for the delivery person
$assigned_sql = "
     SELECT fd.Fid AS Fid, fd.location as cure, fd.name, fd.phoneno, fd.date, fd.delivery_by, 
           fd.address as From_address, ng.name AS NGO, 
           ng.address AS To_address,ng.phone as NGO_phone , fd.status
    FROM food_donations fd
    LEFT JOIN ngo ng ON fd.assigned_to = ng.Nid
    WHERE fd.delivery_by = '$id'
";
$assigned_result = mysqli_query($connection, $assigned_sql);

// Handle taking an order
if (isset($_POST['take_order'])) {
    $order_id = $_POST['order_id'];
    $sql = "UPDATE food_donations SET delivery_by = '$id', status = 'in_transit' WHERE Fid = '$order_id'";
    if (mysqli_query($connection, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
        ob_end_flush();
    } else {
        echo "Error taking order: " . mysqli_error($connection);
    }
}

// Handle marking order as delivered
if (isset($_POST['mark_delivered'])) {
    $order_id = $_POST['order_id'];
    $sql = "UPDATE food_donations SET status = 'completed' WHERE Fid = '$order_id'";
    if (mysqli_query($connection, $sql)) {
        header('Location: ' . $_SERVER['REQUEST_URI']);
        ob_end_flush();
    } else {
        echo "Error marking order as delivered: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard</title>
    <link rel="stylesheet" href="../home.css">
    <link rel="stylesheet" href="delivery.css">
</head>
<body>
<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="#home" class="active">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php">My Orders</a></li>
            <li><a href="..\logout.php">Log Out</li>
        </ul>
    </nav>
</header>
<br>
<script>
    document.querySelector(".hamburger").onclick = function() {
        document.querySelector(".nav-bar").classList.toggle("active");
    }
</script>

<h2><center>Welcome <?php echo $name; ?></center></h2>

<div class="itm">
    <img src="../img/delivery.gif" alt="" width="400" height="400">
</div>

<div class="get">
    <div class="log">
        <a href="deliverymyord.php">My orders</a>
    </div>

    <div class="table-container">
        <h2>Unassigned Orders</h2>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                    <th>Name</th>
                    <th>Phone No</th>
                    <th>Date/Time</th>
                    <th>Pickup Address</th>
                    <th>NGO person name</th>
                    <th>NGO phone no</th>
                    <th>Delivery Address</th>
                    <th>Status</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td data-label="Phone No"><?php echo htmlspecialchars($row['phoneno']); ?></td>
                        <td data-label="Date/Time"><?php echo htmlspecialchars($row['date']); ?></td>
                        <td data-label="Pickup Address"><?php echo htmlspecialchars($row['From_address']); ?></td>
                        <td data-label="Name"><?php echo htmlspecialchars($row['NGO']); ?></td>
                        <td data-label="Phone No"><?php echo htmlspecialchars($row['NGO_phone']); ?></td>
                        <td data-label="Delivery Address"><?php echo htmlspecialchars($row['To_address']); ?></td>
                        <td data-label="Action">
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?php echo $row['Fid']; ?>">
                                <button type="submit" name="take_order">Take Order</button>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <h2>My Assigned Orders</h2>
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone No</th>
                        <th>Date/Time</th>
                        <th>Pickup Address</th>
                        <th>NGO person name</th>
                        <th>NGO phone no</th>
                        <th>Delivery Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($assigned_result)) { ?>
                    <tr>
                        <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td data-label="Phone No"><?php echo htmlspecialchars($row['phoneno']); ?></td>
                        <td data-label="Date/Time"><?php echo htmlspecialchars($row['date']); ?></td>
                        <td data-label="Pickup Address"><?php echo htmlspecialchars($row['From_address']); ?></td>
                        <td data-label="Name"><?php echo htmlspecialchars($row['NGO']); ?></td>
                        <td data-label="Phone No"><?php echo htmlspecialchars($row['NGO_phone']); ?></td>
                        <td data-label="Delivery Address"><?php echo htmlspecialchars($row['To_address']); ?></td>
                        <td data-label="Status"><?php echo htmlspecialchars($row['status']); ?></td>
                        <td data-label="Action">
                            <?php if ($row['status'] == 'in_transit') { ?>
                            <form method="post" action="">
                                <input type="hidden" name="order_id" value="<?php echo $row['Fid']; ?>">
                                <button type="submit" name="mark_delivered">Mark as Delivered</button>
                            </form>
                            <?php } else { ?>
                            No action available
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<br>
<br>
</body>
</html>