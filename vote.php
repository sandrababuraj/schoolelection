<?php
session_start(); // Start the session

// Check if the user is logged in; otherwise, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'config.php';

// Get the logged-in username
$username = $_SESSION['username'];

// Fetch user-specific data from the tb_data table
$query = "SELECT * FROM data WHERE username = '$username'";
$result = mysqli_query($conn, $query);

if ($result) {
    // Fetch the data as an associative array
    $userData = mysqli_fetch_assoc($result);
} else {
    // Handle database query error if needed
    $userData = array();
}

// Initialize a variable to store the submission status
$submissionStatus = '';

// Fetch the total number of votes polled
$totalVotesQuery = "SELECT FLOOR(COUNT(*)/3) as total_votes FROM votes";
$totalVotesResult = mysqli_query($conn, $totalVotesQuery);
$totalVotes = 0;

if ($totalVotesResult && mysqli_num_rows($totalVotesResult) > 0) {
    $totalVotesData = mysqli_fetch_assoc($totalVotesResult);
    $totalVotes = $totalVotesData['total_votes'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate if any radio button is selected for each position
    $valid = true;
    foreach ($_POST['votes'] as $position_id => $candidate_id) {
        if (empty($candidate_id)) {
            $valid = false;
            break;
        }
    }

    if ($valid) {
        // Process votes insertion
        foreach ($_POST['votes'] as $position_id => $candidate_id) {
            $sql = "INSERT INTO votes (candidate_id) VALUES ('$candidate_id')";
            mysqli_query($conn, $sql);
        }

        $submissionStatus = "Votes cast successfully";

        // Redirect to user.php after successful submission
        echo '<script>window.location = "user.php";</script>';
        exit;
    } else {
        $submissionStatus = "Please select a candidate for each position.";
    }
}

$positions = mysqli_query($conn, "SELECT * FROM positions");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="vote.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Briem+Hand:wght@100..900&display=swap" rel="stylesheet">
    <script>
        function validateForm() {
            // Get all radio buttons
            var radioButtons = document.querySelectorAll('input[type="radio"]');
            var positions = {};

            // Group radio buttons by position ID and check if at least one is checked
            radioButtons.forEach(function(button) {
                var positionId = button.getAttribute('data-position-id');
                if (!positions[positionId]) {
                    positions[positionId] = false;
                }
                if (button.checked) {
                    positions[positionId] = true;
                }
            });

            // Check if all positions have a radio button selected
            var allValid = true;
            for (var positionId in positions) {
                if (!positions[positionId]) {
                    allValid = false;
                    break;
                }
            }

            // If all positions have a radio button checked, submit the form
            if (allValid) {
                return true;
            } else {
                alert('Please select a candidate for each position.');
                return false;
            }
        }
    </script>
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
        <center><img src="logo.jpg" alt="" ></center>
        <center>
            <div class="form">
                <?php if (!empty($submissionStatus)) { ?>
                    <p><?php echo $submissionStatus; ?></p>
                <?php } ?>
                <form method="post" onsubmit="return validateForm()">
                    <?php while ($position = mysqli_fetch_assoc($positions)) { ?>
                        <h1><?= $position['name'] ?></h1>
                        <table border="1">
                            <tr>
                                <th>Symbol</th>
                                <th>Candidate Name</th>
                                <th>Select</th>
                            </tr>
                            <?php
                            $candidates = mysqli_query($conn, "SELECT * FROM candidates WHERE position_id = " . $position['id']);
                            while ($candidate = mysqli_fetch_assoc($candidates)) { ?>
                                <tr>
                                    <td>
                                        <center><img src="<?= $candidate['symbol_image'] ?>" alt="<?= $candidate['name'] ?> Symbol" ></center>
                                    </td>
                                    <td><center><?= $candidate['name'] ?></td></center>
                                    <td>
                                        <center><input type="radio" name="votes[<?= $position['id'] ?>]" value="<?= $candidate['id'] ?>" data-position-id="<?= $position['id'] ?>"> </center>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                    <button type="submit" value="Vote">Vote</button>
                </form>
            </div>
        </center>
    </div>
</body>

</html>