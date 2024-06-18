<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="kelolapesanan.css" />
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
                <li><a href="#">My Ticket</a></li>
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
        <?php
        // Include database connection
        include_once "dbconnect.php";

        // Check if user is logged in
        if (isset($_SESSION['user_id'])) {
            // Retrieve userKey from session
            $userKey = $_SESSION['user_id'];

            // Query to retrieve data from invoice table based on userKey
            $sql = "SELECT ticket_number, full_name, email, address, city, state, no_hp, cat, NamaKonser FROM invoice WHERE userKey = ?";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("i", $userKey);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<table id='update_data' border='1'>
                    <tr>
                        <th>Name</th>
                        <th>Ticket Type</th>
                        <th>Show</th>
                        <th>Update Data</th>
                    </tr>";
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>      
                        <td>" . htmlspecialchars($row["full_name"]) . "</td>
                        <td>" . htmlspecialchars($row["cat"]) . "</td>
                        <td>" . htmlspecialchars($row["NamaKonser"]) . "</td>
                        <td><button onclick=\"showUpdateForm('" . htmlspecialchars($row["ticket_number"]) . "', '" . htmlspecialchars($row["full_name"]) . "', '" . htmlspecialchars($row["email"]) . "', '" . htmlspecialchars($row["address"]) . "', '" . htmlspecialchars($row["city"]) . "', '" . htmlspecialchars($row["state"]) . "', '" . htmlspecialchars($row["no_hp"]) . "', '" . htmlspecialchars($row["cat"]) . "', '" . htmlspecialchars($row["NamaKonser"]) . "')\">Update</button></td>
                      </tr>";
                }
                echo "</table>";
            } else {
                echo "No results found.";
            }

            // Close connection
            $stmt->close();
            
        } else {
            echo "Please log in to view your invoices.";
        }
        ?>

        <div id="updateFormContainer" style="display:none;">
            <div class="container">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
                    <input type="hidden" name="ticket_number" id="ticket_number">
                    <div class="row">
                        <div class="col">
                            <h4>Update Ticket Information</h4>
                            <div class="inputbox">
                                <span>Full name :</span>
                                <input type="text" name="name" id="name" placeholder="Nama Lengkap Anda.." required>
                            </div>
                            <div class="inputbox">
                                <span>Email :</span>
                                <input type="email" name="email" id="email" placeholder="example@gmail.com" required>
                            </div>
                            <div class="inputbox">
                                <span>Address :</span>
                                <input type="text" name="address" id="address" placeholder="room - street">
                            </div>
                            <div class="inputbox">
                                <span>City :</span>
                                <input type="text" name="city" id="city" placeholder="Your City">
                            </div>
                            <div class="flex">
                                <div class="inputbox">
                                    <span>State :</span>
                                    <input type="text" name="state" id="state" placeholder="Indonesia">
                                </div>
                                <div class="inputbox">
                                    <span>No. HP :</span>
                                    <input type="text" name="no_hp" id="no_hp" placeholder="+82">
                                </div>
                            </div>
                            <div class="inputbox">
                                <span>CAT :</span>
                                <input type="text" name="cat" id="cat" placeholder="Your Category">
                                <br>
                                <br>
                            </div>
                            <div class="inputbox">
                                <span>Nama Konser :</span>
                                <input type="text" name="NamaKonser" id="NamaKonser" placeholder="Nama Konser">
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                    <input type="submit" value="Update" class="submit-btn">
                </form>
            </div>
        </div>
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
        function showUpdateForm(ticket_number, full_name, email, address, city, state, no_hp, cat, NamaKonser) {
            document.getElementById('ticket_number').value = ticket_number;
            document.getElementById('name').value = full_name;
            document.getElementById('email').value = email;
            document.getElementById('address').value = address;
            document.getElementById('city').value = city;
            document.getElementById('state').value = state;
            document.getElementById('no_hp').value = no_hp;
            document.getElementById('cat').value = cat;
            document.getElementById('NamaKonser').value = NamaKonser;
            document.getElementById('updateFormContainer').style.display = 'block';
        }
    </script>

<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    
    // Retrieve form data
    $ticket_number = $_POST['ticket_number'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $no_hp = $_POST['no_hp'];
    $cat = $_POST['cat'];
    $NamaKonser = $_POST['NamaKonser'];

    // Update data in the database
    $sql_update = "UPDATE invoice SET full_name=?, email=?, address=?, city=?, state=?, no_hp=?, cat=?, NamaKonser=? WHERE ticket_number=?";
    $stmt_update = $mysqli->prepare($sql_update);
    $stmt_update->bind_param("ssssssssi", $name, $email, $address, $city, $state, $no_hp, $cat, $NamaKonser, $ticket_number);

    if ($stmt_update->execute()) {
        echo "<script>alert('Data updated successfully.');</script>";
        // Refresh the page or redirect to a specific location
        echo "<script>window.location.href = 'kelolapesanan.php';</script>";
        // exit();
    } else {
        echo "<script>alert('Error updating data.');</script>";
    }

    // Close statement
    $stmt_update->close();
    // Close database connection
    $mysqli->close();
}
?>
<script>
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

</body>

</html>