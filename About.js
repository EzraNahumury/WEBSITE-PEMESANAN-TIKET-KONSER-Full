// confirm_booking.js

function confirmBooking() {
    var confirmation = confirm("Are you sure you want to proceed with the booking?"); // Show confirmation dialog
    if (confirmation) {
        window.location.href = "Pembayaran.php"; // Redirect to Pembayaran.php if confirmed
    }
}
