$(document).ready(function () {
  $("#register-btn").click(function (event) {
    var username = $("input[name=Register-username]").val();
    var password = $("input[name=Register-Password]").val();

    $.ajax({
      type: "POST",
      url: "./php/register.php",
      data: { username: username, password: password },
      success: function (response) {
        console.log(response);
        if (response.status === "success") {
          Swal.fire({
            icon: "success",
            title: "REGISTRATION SUCCESSFUL",
            text: "You can Login now...",
          }).then(function () {
            window.location.href = "login.html";
          });
        } else if (response.status === "exists") {
          Swal.fire({
            icon: "error",
            title: "REGISTRATION FAILED",
            text: "Username is already taken. Try another one...",
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "REGISTRATION FAILED",
            text: "An error occurred.",
          });
        }
      },
    });
  });
});
