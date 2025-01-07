<?php
include 'db_connection.php';
session_start();
$query = "SELECT workoutID, workoutName, imageURL, sets, reps, weight, date FROM workout WHERE username ='" .  $_SESSION['login_user'] . "'ORDER BY date DESC";
$result = $mysqli->query($query);
$workouts = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $workouts[] = $row;
    }
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Your Workouts</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<style>
    body {
    background-color: #efd0ca;
  }
  h2 {
    margin-top: 0;
    background-color: #f8f9fa;
    padding-top: 0.5rem; 
    padding-bottom: 0.5rem; 
  }
  .navbar-brand, .navbar-nav .nav-link {
    display: inline-block;
  }
  .navbar-nav {
    width: 100%;
    text-align: center;
  }
  .navbar-nav .nav-link {
    position: absolute;
    right: 0;
  }

  .workout-item {
    background-color: #e9ecef;
    border-radius: 0.25rem;
    padding: 1rem;
    margin-bottom: 1rem;
  }
  .workout-image {
    background-color: #6c757d;
    width: 100%;
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-bottom: 1rem;
  }
  .workout-image img {
        max-height: 100%;
        max-width: 100%; 
        object-fit: contain; 
    }
  .workout-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .workout-edit {
    color: #1c91df;
    cursor: pointer;
  }
  .button-group {
    display: flex;
    justify-content: space-between;
    margin-top: 1rem;
  }
</style>
<meta name="description" content="This page will display all of your previous workouts in chronological order.">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-between">
        <a href = "home.php" class="nav-item h3 nav-link">Home</a>
        <div class="navbar-nav">    
            <span class = "nav-item h2"> Your Workouts</span>
            <a href="logout.php" class="nav-item nav-link">Logout</a>
        </div>
    </nav>


  <!-- dynamic workout items -->
  <div class="container mt-4">
        <?php foreach ($workouts as $workout): ?>
        <div class="workout-item">
            <div class="workout-image">
                <img src="<?= htmlspecialchars($workout['imageURL']) ?>" alt="<?= htmlspecialchars($workout['workoutName']) ?>">
            </div>
            <div class="workout-details">
                <div>
                    <strong><?= htmlspecialchars($workout['workoutName']) ?> --- <?= date("m/d/Y", strtotime($workout['date'])) ?></strong>
                    <p>Sets: <?= htmlspecialchars($workout['sets']) ?> --- Reps: <?= htmlspecialchars($workout['reps']) ?> --- Weight: <?= htmlspecialchars($workout['weight']) ?></p>
                </div>
                <span class="workout-edit btn" onclick="editWorkout('<?= $workout['workoutID'] ?>')">EDIT</span>
                <span class="workout-delete btn btn-primary" onclick="deleteWorkout('<?= $workout['workoutID'] ?>')">DELETE</span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

  <div class="text-center">
    <a href="add_workout.php" class="btn btn-primary">Add</a>
  </div>
</div>
</body>

<script>
    function deleteWorkout(workoutId) 
    {
      window.location.href = 'delete_workout.php?workoutID=' + workoutId;
    }
    function editWorkout(workoutId)
    {
      window.location.href = 'edit_workout.php?workoutID=' + workoutId;
    }
</script>
</html>