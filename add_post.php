<?php 
  include 'db_connection.php';
  session_start();

  if (isset($_SESSION['login_user'])) {
    $query = "SELECT admin from users WHERE username = '" . $_SESSION['login_user'] . "'"; 
    $result = $mysqli->query($query);
    if(!$result)
    {
      // make sure people cant circumvent and access it by just typig in the link
      header("location: home.php");
    }
  }
  else{
    // make sure people cant circumvent and access it by just typig in the link
    header("location: home.php");
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //handle fileupload
    $upload_path = NULL;
    if(isset($_FILES["imageInput"]) && $_FILES["imageInput"]["error"] == 0){
      $allowed = [
        "jpg" => "image/jpg", 
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
      //upload to new path
      if (move_uploaded_file($_FILES["imageInput"]["tmp_name"], $upload_path)) {
          echo '';
      } 
      else {
          echo '<script>alert("Error uploading file");</script>';
      }
    }

    //form POST
    if (isset($_POST['setsInput'], $_POST['repsInput'])) {
      $author = mysqli_real_escape_string($mysqli, $_POST['setsInput']);
      $content = mysqli_real_escape_string($mysqli, $_POST['repsInput']);
      $username = mysqli_real_escape_string($mysqli, $_SESSION['login_user']);
      $sql = "INSERT INTO posts (username, author, imageURL, textContent) VALUES ('$username', '$author', '$upload_path', '$content');";

      $result = $mysqli->query($sql);
    }  
    else
    {
      echo '<script>alert("One or more fields are empty.");</script>';
    }
    $mysqli->close();
    header("location: home.php");
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Workout</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<link rel="stylesheet" href="addEditWorkout.css">
<meta name="description" content="Use this page to add new posts to the home.">
</head>
<body>

<div class="container mt-5">
  <!-- Back button -->
  <div class="text-left btn-back">
    <a href="home.php" class="btn btn-outline-secondary">Back</a>
  </div>
  
  <div class="form-container">
    <!-- Post form -->
    <form action = "add_post.php" method = "post" enctype="multipart/form-data">
      <!-- Image upload box -->
      <label for="image-upload">Add Image</label>
      <div class="image-upload">
        <input type="file" name = "imageInput" id="imageInput">
      </div>
      <div class="form-row">
        <div class="form-group col-md-6">
          <label for="setsInput">Author/Blurb/Title:</label>
          <input type="text" class="form-control" name = "setsInput" id="setsInput">
        </div>
      </div>
      <div class="form-group">
        <label for="repsInput">Content:</label>
        <textarea rows="4" cols="50" class="form-control" name="repsInput" id="repsInput"></textarea>
      </div>
      <!-- Add Post button -->
      <button type="submit" class="btn btn-primary" id="addWorkoutBtn">Add post</button>
    </form>
  </div>
</div>

</body>
</html>