<?php 
  //db connection
	$host = "303.itpwebdev.com";
	$user = "pangulur_pangulur";
	$pass = "OOrang#8234";
	$db = "pangulur_fit_helper";

	$mysqli = new mysqli($host, $user, $pass, $db);

	//connection errors
	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit();
	}

	$sql = "SELECT * FROM workout;";

	$results_genres = $mysqli->query($sql);

  //sql errors check
	if ($results_genres == false) {
		echo $mysqli->error;
		$mysqli->close();
		exit();
	}
  session_start();

  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      //handle fileupload
      $upload_path = NULL;
      if(isset($_FILES["imageInput"]) && $_FILES["imageInput"]["error"] == 0){
        $allowed = [
          "jpg" => "image/jpeg", 
          "jpeg" => "image/jpeg", 
          "gif" => "image/gif", 
          "png" => "image/png"
        ];
        $filename = $_FILES["imageInput"]["name"];
        $filetype = $_FILES["imageInput"]["type"];
        $filesize = $_FILES["imageInput"]["size"];

        //Verify file extension (asked openAI chatgpt for this)
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");

      //stores all images in this folder
        $upload_dir = "upload/";

        //function to increment filename if it already exists
        function incrementFilename($filename, $upload_dir) {
            $file_info = pathinfo($filename);
            $base_name = $file_info['filename'];
            
            $extension = '';
            if (isset($file_info['extension'])) {
                $extension = '.' . $file_info['extension']; 
            }
            $counter = 1; 
            $new_filename = $base_name . $counter . $extension; 

            while (file_exists($upload_dir . $new_filename)) {
                $counter++;
                $new_filename = $base_name . $counter . $extension;
            }
            return $new_filename;
        }

        if (file_exists($upload_dir . $filename)) {
            $filename = incrementFilename($filename, $upload_dir);
        }

        $upload_path = $upload_dir . $filename; 

        if (move_uploaded_file($_FILES["imageInput"]["tmp_name"], $upload_path)) {
            echo '';
        } 
        else {
            echo '<script>alert("Error uploading file");</script>';
        }
      }

      //form POST
      if (!empty($upload_path)||!empty($_POST['exerciseInput'])||!empty($_POST['setsInput'])||!empty($_POST['repsInput'])||!empty($_POST['weightInput'])||!empty($_POST['dateInput'])) {
        $sql = "UPDATE workout SET ";
        $restSQL = [];

        if (!empty($upload_path)) {
          $restSQL[] = "imageURL='$upload_path'";
        }

        if (!empty($_POST['exerciseInput'])) {
            $exercise = mysqli_real_escape_string($mysqli, $_POST['exerciseInput']);
            $restSQL[] = "workoutName='$exercise'";
        }
        if (!empty($_POST['setsInput'])) {
            $sets = $_POST['setsInput'];
            $restSQL[] = " sets=$sets";
        }
        if (!empty($_POST['repsInput'])) {
            $reps = $_POST['repsInput'];
            $restSQL[] = " reps=$reps";
        }
        if (!empty($_POST['weightInput'])) {
            $weight = $_POST['weightInput'];
            $restSQL[] = " weight=$weight";
        }
        if (!empty($_POST['dateInput'])) {
            $date = $_POST['dateInput'];
            $restSQL[] = " date='$date'";
        }

        if (!empty($restSQL)) {
          for ($i = 0; $i < count($restSQL); $i++) 
          {
            $sql .= $restSQL[$i];
            if ($i < count($restSQL) - 1) {  
                $sql .= ',';  
            }
          }
          $sql .= " WHERE workoutID = " . $_POST['workoutID'] .";";

          $result = $mysqli->query($sql);
        }  
        else{
        echo '<script>alert("One or more fields are empty.");</script>';
        }
    $mysqli->close();
    header("location: workouts.php");
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Workout</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="addEditWorkout.css">

<meta name="description" content="Use this page to edit previously uploaded workouts, in case you made an error or wanted to change the picture.">
</head>
<body>

<div class="container mt-5">
  <!-- Back button -->
  <div class="text-left btn-back">
    <a href="workouts.php" class="btn btn-outline-secondary">Back</a>
  </div>
  
  <div class="form-container">
    <!-- Workout form -->
    <form action = "edit_workout.php" method = "post" enctype="multipart/form-data">
      <!-- Use for getting the workoutID in post -->
      <input type="hidden" name="workoutID" value="<?= $_GET['workoutID'] ?>">
      <!-- Image upload box -->
      <label for="image-upload">Add Image</label>
      <div class="image-upload">
        <input type="file" name = "imageInput" id="imageInput">
      </div>  
      <div class="form-group">
          <label for="exerciseInput">Exercise:</label>
          <input type="text" class="form-control" name = "exerciseInput" id="exerciseInput" placeholder="Name of exercise">
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="setsInput">Sets:</label>
          <input type="text" class="form-control" name = "setsInput" id="setsInput">
        </div>
        <div class="form-group col-md-6">
          <label for="dateInput">Date:</label>
          <input type="date" class="form-control" name = "dateInput" id="dateInput">
        </div>
      </div>
      <div class="form-group">
        <label for="repsInput">Reps:</label>
        <input type="text" class="form-control" name = "repsInput" id="repsInput">
      </div>
      <div class="form-group">
        <label for="weightInput">Weight:</label>
        <input type="text" class="form-control" name = "weightInput" id="weightInput">
      </div>
      <!-- Add Workout button -->
      <button type="submit" class="btn btn-primary" id="addWorkoutBtn">Submit Edit</button>
    </form>
  </div>
</div>

<script>
</script>

</body>
</html>