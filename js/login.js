$(document).ready(function () {
  // Change the event to click on #login-btn
  $("#login-btn").click(function () {
    var username = $("input[name=login-username]").val();
    var password = $("input[name=login-password]").val();

    var userData = {
      username: username,
      password: password,
    };

    $.ajax({
      type: "POST",
      url: "./php/login.php",
      data: userData,
      dataType: "json",
      success: function (response) {
        console.log("Full response:", response);

        if (response && response.status === "success") {
          localStorage.setItem("username", response.username);
          Swal.fire({
            icon: "success",
            title: "LOGIN SUCCESSFUL",
          }).then(function () {
            window.location.href = "profile.html";
          });
        } else {
          console.log("Login failed. Response:", response);
          Swal.fire({
            icon: "error",
            title: "lOGIN FAILED",
            text: "Invalid username or password",
          });
        }
      },
    });
  });
});
