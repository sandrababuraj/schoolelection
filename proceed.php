<?php
session_start();
require 'config.php';

// Check if the user is logged in; otherwise, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


// Fetch all candidate IDs
$candidateQuery = "SELECT id FROM candidates";
$candidateResult = mysqli_query($conn, $candidateQuery);

if ($candidateResult && mysqli_num_rows($candidateResult) > 0) {
    // Loop through each candidate and count the votes
    while ($candidate = mysqli_fetch_assoc($candidateResult)) {
        $candidate_id = $candidate['id'];

        // Count the votes for the current candidate
        $voteCountQuery = "SELECT COUNT(*) as vote_count FROM votes WHERE candidate_id = $candidate_id";
        $voteCountResult = mysqli_query($conn, $voteCountQuery);

        if ($voteCountResult && mysqli_num_rows($voteCountResult) > 0) {
            $voteCountData = mysqli_fetch_assoc($voteCountResult);
            $vote_count = $voteCountData['vote_count'];

            // Update the result in the results table by adding the vote count
            $updateResultQuery = "
                UPDATE results 
                SET votes = votes + $vote_count 
                WHERE candidate_id = $candidate_id";
            mysqli_query($conn, $updateResultQuery);
        }
    }

    // Truncate the votes table after processing all candidates
    $truncateVotesQuery = "TRUNCATE TABLE votes";
    mysqli_query($conn, $truncateVotesQuery);
}

// Redirect to a results page or any other page as needed
header("Location: admin.php");
exit();
?>
