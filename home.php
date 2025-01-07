<?php
include 'db_connection.php';
session_start(); 

// fetch random posts from the database
$query = "SELECT * FROM posts ORDER BY RAND() LIMIT 4";
$result = $mysqli->query($query);
$posts = [];
if ($result->num_rows > 0) 
{
    while($row = $result->fetch_assoc()) 
    {
        $posts[] = $row;
    }
}
// //check if admin/has posting privileges 
// $add_perm = false;
// if (isset($_SESSION['login_user'])) {
//   $query2 = "SELECT admin FROM users WHERE username = " . $_SESSION['login_user'];
//   $result2 = $mysqli->query($query2);
//   if($row = $result2->fetch_assoc())
//   {
//     $add_perm = (bool) $row['admin'];
//   }
// } 

$add_perm = false;
if (isset($_SESSION['login_user'])) {
    $stmt = $mysqli->prepare("SELECT admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['login_user']); 
    $stmt->execute();
    $result2 = $stmt->get_result();
    if ($row = $result2->fetch_assoc()) 
    {
        $add_perm = (bool) $row['admin'];
    }
    $stmt->close();
}


$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="This is the homepage. On it are some general, random articles submitted by admins regarding weightlifting. There is also a random workout taken from an API.">
<title>FitHelper</title>
<!-- Link to Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<style>
  body{
        background-color: #efd0ca;
    }

  .im {
    background-color: #0a2463; 
    color: #bababa;
    height: 200px; 
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .im img {
        max-height: 100%;
        max-width: 100%; 
        object-fit: contain; 
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
  .card {
    height: 400px; 
    overflow: hidden; 
    background-color: #e1eded;
  }
  .card-body {
    overflow-y: auto; /* adds scrollbar */
  }
  
  .add-article-btn {
            position: fixed; 
            right: 10px; 
            bottom: 10px; 
            z-index: 1000; /* ensure it sits on top of other elements */
        }
  h2{
    color: white;
    margin-right: 26px;
    margin-left: 26px;
  }
</style>

</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <?php
  if(isset($_SESSION['login_user']))
  {
    echo '<a href="workouts.php" class="nav-item nav-link">Your Workouts</a>';
  }
  ?>
  <div class="navbar-nav">
    <span class="nav-item h2">Home</span>
    <?php
      if (isset($_SESSION['login_user'])) {
          echo '<a href="logout.php" class="nav-item nav-link">Logout</a>';
      } else {
          echo '<a href="login.php" class="nav-item nav-link">Login</a>';
      }
    ?>
  </div>
</nav>

<div class="container mt-4">
  <div class="row mb-3">
    <div class="col-12">
      <div class="card">
        <div class="im">
            <img src="imgs/gym.jpg" alt="gym">
            <h2>Try out this workout sometime!</h1>
            <img src="imgs/gym.jpg" alt="gym">
        </div>
        <div class="card-body">
          <h5 id = "exerciseNameAPI" class="card-title">Barbell Bicep Curl</h5>
          <p id = "exerciseDescAPI"class="card-text">A simple exercise in which one keeps their elbows in place at their sides, straightens their back, and curls the bar up through their natural range of motion.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Articles in two columns -->
  <div class="row">
    <?php foreach ($posts as $post): ?>
      <div class="col-md-6 mb-3">
        <div class="card">
          <div class="im">
              <img src="<?= htmlspecialchars($post['imageURL']) ?>" alt="<?= $post['imageURL']?>">
          </div>
          <div class="card-body">
            <h5 class="card-title"><?= nl2br(htmlspecialchars($post['author'])) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars(strip_tags($post['textContent']))) ?></p>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Additional articles can be added here following the same structure -->
  </div>
</div>
<?php
if($add_perm)
{
  echo "<a href=\"add_post.php\" class=\"btn btn-success add-article-btn\">Add Article</a>";
}
?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    fetchRandomExercise();
  });

  function fetchRandomExercise() {
    const url = 'https://api.api-ninjas.com/v1/exercises'; 
    const options = {
      method: 'GET',
      headers: {'X-Api-Key': 'coGlkWFI1CB+Jch5oUMKCQ==rALrEy3P5sGRpqqc'}
    };

    fetch(url, options)
      .then(response => response.json())
      .then(data => {
          const randomExercise = data[Math.floor(Math.random() * data.length)];
          document.getElementById('exerciseNameAPI').innerHTML = randomExercise.name;
          document.getElementById('exerciseDescAPI').innerHTML = randomExercise.instructions;
      })
      .catch(error => console.error('Error api'));
  }
</script>
</body>
</html>