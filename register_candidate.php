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
    $candidate_name = $_POST['candidate_name'];
    $position_id = $_POST['position_id'];
    $symbol_image = '';

    if (isset($_FILES['symbol_image']) && $_FILES['symbol_image']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["symbol_image"]["name"]);
        if (move_uploaded_file($_FILES["symbol_image"]["tmp_name"], $target_file)) {
            $symbol_image = $target_file;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $sql = "INSERT INTO candidates (name, position_id, symbol_image) VALUES ('$candidate_name', '$position_id', '$symbol_image')";
    if (mysqli_query($conn, $sql)) {
        echo "Candidate registered successfully";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

$positions = mysqli_query($conn, "SELECT * FROM positions");
?>

<div class="form">
    <form method="post" enctype="multipart/form-data">
        <center>Candidate Name: <input type="text" name="candidate_name"><br></center>
        <center>Position: 
            <select name="position_id">
                <?php while ($position = mysqli_fetch_assoc($positions)) { ?>
                    <option value="<?= $position['id'] ?>"><?= $position['name'] ?></option>
                <?php } ?>
            </select><br>
        </center>
        <center>Symbol Image: <input type="file" name="symbol_image"><br></center>
        <center><button type="submit">Submit</button></center>
    </form>
</div>
</body>
</html>
