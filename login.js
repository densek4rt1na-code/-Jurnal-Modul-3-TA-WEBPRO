document.addEventListener("DOMContentLoaded", function () {

  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");

  const toRegister = document.getElementById("toRegister");
  const toLogin = document.getElementById("toLogin");
  const formTitle = document.getElementById("formTitle");

  const loginUser = document.getElementById("loginUser");
  const loginPass = document.getElementById("loginPass");

  const regUser = document.getElementById("regUser");
  const regPass = document.getElementById("regPass");
  const regConfirm = document.getElementById("regConfirm");

  /* ===============================
     TOGGLE FORM
  =============================== */
  toRegister.onclick = () => {
    loginForm.classList.add("hidden");
    registerForm.classList.remove("hidden");
    formTitle.innerText = "Register";
  };

  toLogin.onclick = () => {
    registerForm.classList.add("hidden");
    loginForm.classList.remove("hidden");
    formTitle.innerText = "Login";
  };

  /* ===============================
     DEFAULT ADMIN
  =============================== */
  if (!localStorage.getItem("users")) {
    const defaultUsers = [
      { username: "admin", password: "admin123", role: "admin" }
    ];
    localStorage.setItem("users", JSON.stringify(defaultUsers));
  }

  /* ===============================
     LOGIN
  =============================== */
  loginForm.onsubmit = function (e) {
    e.preventDefault();

    const username = loginUser.value.trim();
    const password = loginPass.value.trim();

    const users = JSON.parse(localStorage.getItem("users")) || [];

    const foundUser = users.find(
      u => u.username === username && u.password === password
    );

    if (!foundUser) {
      alert("Username atau password salah!");
      return;
    }

    localStorage.setItem("isLogin", "true");
    localStorage.setItem("currentUser", JSON.stringify(foundUser));

    if (foundUser.role === "admin") {
      window.location.href = "ADMIN/Admin.html";
    } else {
      window.location.href = "USER/User.html";
    }
  };

  /* ===============================
     REGISTER
  =============================== */
  registerForm.onsubmit = function (e) {
    e.preventDefault();

    const username = regUser.value.trim();
    const password = regPass.value.trim();
    const confirm = regConfirm.value.trim();

    if (password !== confirm) {
      alert("Password tidak sama!");
      return;
    }

    let users = JSON.parse(localStorage.getItem("users")) || [];

    if (users.some(u => u.username === username)) {
      alert("Username sudah terdaftar!");
      return;
    }

    users.push({
      username,
      password,
      role: "user"
    });

    localStorage.setItem("users", JSON.stringify(users));

    alert("Registrasi berhasil! Silakan login.");
    registerForm.reset();
    toLogin.click();
  };

});