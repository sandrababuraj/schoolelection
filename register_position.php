<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="style copy.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Briem+Hand:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>

    <div class="bgimg">
        <div class="navbar">
            <ul class="navbar list">
                <li><a href="profile.php?tab=dashboard" class="tablinks <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="profile.php?tab=profile" class="tablinks <?php echo (isset($_GET['tab']) && $_GET['tab'] == 'profile') ? 'active' : ''; ?>">Profile</a></li>
                <li style="float:right"><a href="login.php" class="tablinks">Sign Out</a></li>
            </ul>
        </div>
<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $position_name = $_POST['position_name'];

    $sql = "INSERT INTO positions (name) VALUES ('$position_name')";
    if (mysqli_query($conn, $sql)) {
        echo "Position registered successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<center><form method="post">
    Position Name: <input type="text" name="position_name"><br>
    <button type="submit" value="Register Position">Submit</button>
</form></center>
