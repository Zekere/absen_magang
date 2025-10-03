document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const userRadio = document.getElementById("user");
    const adminRadio = document.getElementById("admin");
    const usernameLabel = document.getElementById("usernameLabel");
    const usernameInput = document.getElementById("username");

    function updateInputLabel() {
        if (adminRadio.checked) {
            usernameLabel.textContent = "Email Anda";
            usernameInput.placeholder = "Masukkan email";
            usernameInput.name = "email";
            form.action = "/prosesloginadmin";
        } else {
            usernameLabel.textContent = "ID Anda";
            usernameInput.placeholder = "Masukkan ID";
            usernameInput.name = "nik";
            form.action = "/proseslogin";
        }
    }

    // Jalankan saat pertama kali
    updateInputLabel();

    // Event listener perubahan role
    userRadio.addEventListener("change", updateInputLabel);
    adminRadio.addEventListener("change", updateInputLabel);

    // (Opsional) Debugging - bisa dihapus nanti
    form.addEventListener("submit", function (e) {
        console.log("Action:", form.action);
        console.log("Role:", adminRadio.checked ? "admin" : "user");
        console.log("Username:", usernameInput.value);
    });

});

