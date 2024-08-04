<?php
session_start();
require 'config.php';

// Check if the user is logged in; otherwise, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// // Fetch the total number of votes polled
// $totalVotesQuery = "SELECT FLOOR(COUNT(*)/3) as total_votes FROM votes";
// $totalVotesResult = mysqli_query($conn, $totalVotesQuery);
// $totalVotes = 0;
// if ($totalVotesResult && mysqli_num_rows($totalVotesResult) > 0) {
//     $totalVotesData = mysqli_fetch_assoc($totalVotesResult);
//     $totalVotes = $totalVotesData['total_votes'];
// }

$positions = mysqli_query($conn, "SELECT * FROM positions");
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="style4.css">
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
        
        <!-- <center><p>Total votes polled: <?php echo $totalVotes; ?></p></center> -->

        <h1>Election Results</h1>
        <?php while ($position = mysqli_fetch_assoc($positions)) { ?>
            <h2><?= $position['name'] ?></h2>
            <?php
            $results = mysqli_query($conn, "
                SELECT candidates.name, COUNT(votes.id) as vote_count
                FROM votes
                JOIN candidates ON votes.candidate_id = candidates.id
                WHERE candidates.position_id = " . $position['id'] . "
                GROUP BY votes.candidate_id
                ORDER BY vote_count DESC
            ");
            ?>
            <table>
                <tr>
                    <th>Candidate</th>
                    <th>Votes</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($results)) { ?>
                    <tr>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['vote_count'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>

        <div class="form"><form method="post" action="proceed.php">
            <center><button type="submit" class="proceed-button">Proceed</button></center>
        </form></div>
    </div>
</body>

</html>
