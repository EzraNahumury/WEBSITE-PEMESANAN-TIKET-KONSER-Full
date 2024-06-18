<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IDTH-Home</title>
  <link rel="stylesheet" href="Home.css" />
  <script src="https://kit.fontawesome.com/4592f70558.js" crossorigin="anonymous"></script>
</head>

<body>
  <header>
    <div class="navbar">
      <div class="logo">
        <a href="#"><img src="Foto/I.png" alt=""></a>
      </div>
      <ul>
        <li><a href="Home.php">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Service</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="kelolapesanan.php">My Ticket</a></li>
      </ul>
      <div class="search-bar">
        <form method="GET" action="home.php">
          <input type="text" name="search" placeholder="Search...">
          <button type="submit">Search</button>
        </form>
      </div>
      <div class="profile">
        <i class="fas fa-user" id="profileIcon"></i>
        <div class="dropdown-menu" id="dropdownMenu">
          <!-- PHP untuk memeriksa apakah pengguna sudah login dan menampilkan opsi yang sesuai -->
          <?php
          session_start();
          if (isset($_SESSION['namaLengkap'])): ?>
            <p><?php echo htmlspecialchars($_SESSION['namaLengkap']); ?></p>
            <a href="logout.php">Logout</a>
          <?php else: ?>
            <a href="login.php">Login/SignUp</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>
  <main>
    <div class="container1">
      <div class="box1">
        <div class="Detail">
          <div class="title">NewJeans</div>
          <div class="sub_title">KPOP</div>
          <div class="sub_title2">Jakarta • June 23</div>
          <div class="btn">
            <button data-artist-id="1">
              <a href="About.php">Find Ticket</a>
            </button>
          </div>
        </div>
      </div>
    </div>
    
    <?php
include 'dbconnect.php';

// Penanganan pencarian
$searchQuery = '';
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $sql = "SELECT artist_id, title, location, date, genre, image FROM events 
            WHERE title LIKE '%$searchQuery%' 
            OR location LIKE '%$searchQuery%' 
            OR date LIKE '%$searchQuery%'";
} else {
    $sql = "SELECT artist_id, title, location, date, genre, image FROM events";
}

$result = $mysqli->query($sql);

// Menampilkan data
if ($result) {
  if ($result->num_rows > 0) {
      echo '<div class="container2"><div class="box-luar">';
      while ($row = $result->fetch_assoc()) {
          echo '<div class="box2">';
          echo '  <div class="box-dalam">';
          echo '    <img src="data:image/jpeg;base64,' . base64_encode($row["image"]) . '" alt="' . htmlspecialchars($row["title"]) . '"/>';
          echo '    <h3>' . htmlspecialchars($row["title"]) . '</h3>';
          echo '    <p>' . htmlspecialchars($row["location"]) . ' • ' . htmlspecialchars($row["date"]) . '</p>';
          echo '    <p>' . htmlspecialchars($row["genre"]) . '</p>';
          echo '    <div class="buttons">';
          echo '      <button class="find-ticket-button" data-artist-id="' . htmlspecialchars($row["artist_id"]) . '">Find Ticket</button>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
      }
      echo '</div></div>';
  } else {
      echo "0 results";
  }
} else {
  // Tangani error
  echo "Query failed: " . mysqli_error($mysqli);
}


// Tutup koneksi
mysqli_close($mysqli);
?>
  </main>
  <footer class="footer">
    <div class="container3">
      <div class="row">
        <div class="footer-col">
          <h4>company</h4>
          <ul>
            <li><a href="#">about us</a></li>
            <li><a href="#">our services</a></li>
            <li><a href="#">privacy policy</a></li>
            <li><a href="#">affiliate program</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>get help</h4>
          <ul>
            <li><a href="#">FAQ</a></li>
            <li><a href="#">shipping</a></li>
            <li><a href="#">returns</a></li>
            <li><a href="#">order status</a></li>
            <li><a href="#">payment options</a></li>
          </ul>
        </div>
        <div class="footer-col">
          <h4>follow us</h4>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
      </div>
    </div>
    <p>&copy;Indonesia Ticket Hub 2024</p>
  </footer>
  <script>
    // Toggle the dropdown menu on icon click
    document.getElementById('profileIcon').addEventListener('mouseover', function() {
      var dropdown = document.getElementById('dropdownMenu');
      dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
      if (!event.target.matches('#profileIcon')) {
        var dropdown = document.getElementById('dropdownMenu');
        if (dropdown.style.display === 'block') {
          dropdown.style.display = 'none';
        }
      }
    }
  </script>
  <script>
        // Mengambil semua tombol dengan class 'find-ticket-button'
        const buttons = document.querySelectorAll('.find-ticket-button');

        // Menambahkan event listener pada setiap tombol
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                // Mengambil artist_id dari data-attribute tombol yang diklik
                const artistId = this.getAttribute('data-artist-id');
                // Mengarahkan ke halaman artist.php dengan parameter artist_id
                window.location.href = `About.php?artist_id=${artistId}`;
            });
        });
    </script>
</body>
</html>
