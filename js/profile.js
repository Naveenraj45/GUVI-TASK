$(document).ready(function () {
  var username = localStorage.getItem("username");

  function fetchUserData() {
    $.ajax({
      type: "POST",
      url: "./php/profile.php",
      data: { username: username },
      success: function (response) {
        if (response.status === "error") {
          alert("User data not found. Please check your username.");
        } else {
          var userData = response.data;
          $("#inputfirstname").val(userData.firstname);
          $("#inputlastname").val(userData.lastname);
          $("#inputage").val(userData.age);
          $("#inputdob").val(userData.dateOfBirth);
          $("#inputcontact").val(userData.contactNumber);

          // Disabling input fields initially
          disableInputFields();
        }
      },
    });
  }

  // Updating user data in MongoDB
  $("#update-btn").click(function (event) {
    event.preventDefault();

    var newPassword = $("#inputnewpassword").val();

    var userData = {
      username: username,
      firstname: $("#inputfirstname").val(),
      lastname: $("#inputlastname").val(),
      age: $("#inputage").val(),
      dob: $("#inputdob").val(),
      contact: $("#inputcontact").val(),
      newPassword: newPassword,
      action: "update",
    };

    $.ajax({
      type: "POST",
      url: "./php/profile.php",
      data: userData,
      success: function (response) {
        if (response.status === "success") {
          Swal.fire({
            icon: "success",
            title: "Profile updated successfully!",
          });

          // Toggling the visibility of buttons
          $("#edit-btn").show();
          $("#update-btn").hide();

          // Disabling input fields after update process
          disableInputFields();
        } else {
          Swal.fire({
            icon: "error",
            title: "Failed to Update Profile.you have not entered any data..",
          });
        }
      },
    });
  });

  // Handling the edit button as well as the update button
  $("#edit-btn").click(function () {
    enableInputFields();

    $("#edit-btn").hide();
    $("#update-btn").show();
  });

  // Disabling input fields
  function disableInputFields() {
    $("input").prop("disabled", true);
  }

  // Enabling  input fields for data entry
  function enableInputFields() {
    $("input").prop("disabled", false);
  }
  $("#inputImage").change(function () {
    readURL(this);
  });

  // To display the image
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $("#profileImage").attr("src", e.target.result);
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  fetchUserData();
});
//for logout process
$(document).ready(function () {
  $("#logout-btn").click(function () {
    localStorage.clear();

    window.location.href = "login.html";
  });
});
