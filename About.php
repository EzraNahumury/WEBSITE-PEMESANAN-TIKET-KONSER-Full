<?php
require 'dbconnect.php';
session_start();

$artist_id = isset($_GET['artist_id']) ? intval($_GET['artist_id']) : 1;
$_SESSION['artist_id'] = $artist_id;

// Fetch general artist data
$artist_result = $mysqli->query("SELECT namaKonser, image, tentangKonser, imageSeat FROM events WHERE artist_id = $artist_id");
$artist_data = $artist_result->fetch_assoc();

$songs_result = $mysqli->query("SELECT * FROM songs WHERE artist_id = $artist_id");
$tour_dates_result = $mysqli->query("SELECT * FROM tour_dates WHERE artist_id = $artist_id");
$tickets_result = $mysqli->query("SELECT * FROM tickets WHERE artist_id = $artist_id");

$_SESSION['namaKonser'] = $artist_data['namaKonser'];

$quantity_data = isset($_SESSION['quantity']) ? $_SESSION['quantity'] : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Data</title>
    <link rel="stylesheet" href="About.css">
    <script src="https://kit.fontawesome.com/4592f70558.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <a href="Home.php"><img src="Foto/I.png" alt=""></a>
            </div>
            <ul>
                <li><a href="Home.php">Home</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Service</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="kelolapesanan.php">My Ticket</a></li>
            </ul>
            <div class="profile">
                <i class="fas fa-user" id="profileIcon"></i>
                <div class="dropdown-menu" id="dropdownMenu">
                    <?php if (isset($_SESSION['namaLengkap'])): ?>
                        <p><?php echo htmlspecialchars($_SESSION['namaLengkap']); ?></p>
                        <a href="logout.php">Logout</a>
                    <?php else: ?>
                        <a href="Login.php">Login/SignUp</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
    <main>
        <h1><?= htmlspecialchars($artist_data['namaKonser']) ?></h1>
        <center>
        <table class="intro">
            <tr>
                <td>
                    <img src="data:image/jpeg;base64,<?= base64_encode($artist_data['image']) ?>" alt="<?= htmlspecialchars($artist_data['namaKonser']) ?> Tour Image">
                </td>
                <td>
                    <section class="about-us">
                        <h2>About <?= htmlspecialchars($artist_data['namaKonser']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($artist_data['tentangKonser'])) ?></p>
                    </section>
                </td>
            </tr>
        </table>
        </center>
        <section class="our-album">
            <hr>
            <h2>Songs</h2>
            <table>
                <tr class="mp3">
                    <?php while ($song = $songs_result->fetch_assoc()): ?>
                        <td>
                            <a href="<?= htmlspecialchars($song['url']) ?>" target="_blank">
                                <?php if ($song['image']): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($song['image']) ?>" alt="<?= htmlspecialchars($song['title']) ?>">
                                <?php else: ?>
                                    <img src="path/to/default/image.jpg" alt="No Image Available">
                                <?php endif; ?>
                            </a>
                        </td>
                    <?php endwhile; ?>
                </tr>
                <tr class="link">
                    <?php $songs_result->data_seek(0); ?>
                    <?php while ($song = $songs_result->fetch_assoc()): ?>
                        <td>
                            <a href="<?= htmlspecialchars($song['url']) ?>" target="_blank">
                                <?= htmlspecialchars($song['title']) ?>
                            </a>
                        </td>
                    <?php endwhile; ?>
                </tr>
            </table>
        </section>

        <section class="tour-dates">
            <hr>
            <h2>Tour Dates</h2>
            <table>
                <thead>
                <tr>
                    <th>City</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($tour_date = $tour_dates_result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($tour_date['city']) ?></td>
                        <td><?= htmlspecialchars($tour_date['date']) ?></td>
                        <td><?= htmlspecialchars($tour_date['time']) ?></td>
                        <td><?= htmlspecialchars($tour_date['venue']) ?></td>
                    </tr> 
                <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section class="venue">
            <hr>
            <h2>Seating Plan & Tickets</h2>
            <form action="Pembayaran.php" method="post" id="ticketForm">
                <input type="hidden" name="redirectTo" value="About.php?artist_id=<?= $artist_id ?>">
                <img src="data:image/jpeg;base64,<?= base64_encode($artist_data['imageSeat']) ?>" alt="<?= htmlspecialchars($artist_data['namaKonser']) ?> Tour Seating Plan Image">
                <table class="stock">
                    <tr>
                        <th>Ticket Type</th>
                        <th>Stock Remaining</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                    <?php while ($ticket = $tickets_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($ticket['type']) ?></td>
                            <td><?= htmlspecialchars($ticket['stock']) ?></td>
                            <td><?= htmlspecialchars($ticket['price']) ?></td>
                            <td>
                                <input type="number" name="quantity[<?= htmlspecialchars($ticket['id']) ?>]" min="0" max="<?= htmlspecialchars($ticket['stock']) ?>" value="<?= isset($quantity_data[$ticket['id']]) ? htmlspecialchars($quantity_data[$ticket['id']]) : '0' ?>">
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
                <br>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <button type="submit" class="buy-tickets-btn" onclick="confirmBooking()">Buy Tickets Now!</button>
                <?php else: ?>
                    <button type="button" class="buy-tickets-btn" onclick="redirectToLogin();">Buy Tickets Now!</button>
                <?php endif; ?>
            </form>
        </section>

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
    <script src="About.js"></script>
    <script>
        function redirectToLogin() {
            alert('Please login to buy tickets.');
            var currentUrl = window.location.href;
            window.location.href = 'Login.php?redirectTo=' + encodeURIComponent(currentUrl);
        }

        document.getElementById('profileIcon').addEventListener('click', function() {
            var dropdown = document.getElementById('dropdownMenu');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        window.onclick = function(event) {
            if (!event.target.matches('#profileIcon')) {
                var dropdown = document.getElementById('dropdownMenu');
                if (dropdown.style.display === 'block') {
                    dropdown.style.display = 'none';
                }
            }
        }
    </script>
    <script src="About.js"></script>
</body>
</html>
