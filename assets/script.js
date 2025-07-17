function validateForm() {
    let password = document.getElementById('password').value;
    let confirmPassword = document.getElementById('confirm_password').value;

    if (password !== confirmPassword) {
        alert("Password dan Konfirmasi Password tidak cocok.");
        return false;
    }
    return true;
}
