
<?php
include("login.php"); 
if($_SESSION['name']==''){
    header("location: signin.php");
}
$emailid = $_SESSION['email'];
$connection = mysqli_connect("localhost", "root", "");
$db = mysqli_select_db($connection, 'food');

if (!$connection || !$db) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch NGO details based on email
$ngo_query = "SELECT name, phone, address, city FROM ngo WHERE email='$emailid'";
$ngo_result = mysqli_query($connection, $ngo_query);

if ($ngo_result && mysqli_num_rows($ngo_result) > 0) {
    $ngo_details = mysqli_fetch_assoc($ngo_result);
    $name = $ngo_details['name'];
    $phoneno = $ngo_details['phone'];
    $address = $ngo_details['address'];
    $district = $ngo_details['city'];
} else {
    $name = '';
    $phoneno = '';
    $address = '';
    $district = '';
}

// Form processing logic
if(isset($_POST['submit'])) {
    $foodname = mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal = mysqli_real_escape_string($connection, $_POST['meal']);
    $category = $_POST['image-choice'];
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);

    // Processing logic here (e.g., validation, logging, etc.)
    echo '<script type="text/javascript">alert("Form submitted successfully. No data inserted into the database.")</script>';
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
                
                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required/>
                </div>
                
                <div class="radio">
                    <label for="meal">Meal type :</label> 
                    <br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required/>
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg">
                    <label for="Non-veg">Non-veg</label>
                </div>
                <br>
                <div class="input">
                    <label for="food">Select the Category:</label>
                    <br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food"><img src="img/raw-food.png" alt="raw-food"></label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food"><img src="img/cooked-food.png" alt="cooked-food"></label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food"><img src="img/packed-food.png" alt="packed-food"></label>
                    </div>
                </div>
                <div class="input">
                    <label for="quantity">Quantity:(number of person /kg)</label>
                    <input type="text" id="quantity" name="quantity" required/>
                </div>
                <b><p style="text-align: center;">Contact Details</p></b>
                <div class="input">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $name; ?>" required/>
                </div>
                <div class="input">
                    <label for="phoneno">PhoneNo:</label>
                    <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" value="<?php echo $phoneno; ?>" required />
                </div>
                <div class="input">
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" value="<?php echo $district; ?>" required />
                </div>
                <div class="input">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" value="<?php echo $address; ?>" required/>
                </div>
                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>