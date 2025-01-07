<?php
include 'db_connection.php'; 
session_start();

if (isset($_GET['workoutID'])) {
    $workoutID = $_GET['workoutID'];
    if ($stmt = $mysqli->prepare("SELECT imageURL FROM workout WHERE workoutID = ?")) {
        $stmt->bind_param("i", $workoutID); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) 
        {
            $row = $result->fetch_assoc();
            $imagePath = $row['imageURL'];

            //should delete file from upload foledr
            if (file_exists($imagePath)) 
            {
                unlink($imagePath);
            }

            //del from sql
            if($deleteStmt = $mysqli->prepare("DELETE FROM workout WHERE workoutID = ?")) 
            {
                $deleteStmt->bind_param("i", $workoutID);
                $deleteStmt->execute();
                $deleteStmt->close();
            }
        }
        $stmt->close();
    }
}
$mysqli->close();
header("Location: workouts.php");
?>