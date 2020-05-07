$(document).ready(() => {
  $("#btnLogUser").click(Login);
});

/**
 * @author Hoarau Nicolas
 * @date 05.05.20
 * 
 * @brief Fonction qui rcupère les données du formulaire et les envois au serveur via un call ajax
 * @param {*} event
 * 
 * @version 1.0.0
 */
function Login(event) {
  if (event) {
    event.preventDefault();
  }

  let authenticator = $('#email').val();
  let password = $('#password').val();

  if (authenticator.length == 0) {
    $('#authenticator').focus();
    return;
  }

  if (password.length == 0) {
    $("#password").focus();
    return;
  }

  $.ajax({
    type: "post",
    url: "./php/login.php",
    data: { authenticator: authenticator, password: password },
    dataType: "json",
    success: (data) => {
      switch (data.ReturnCode) {
        case 0:
          Swal.fire({
            title: "Authentification",
            text: data.Success,
            icon: "success",
            button: {
              visible: false,
              closeModal: true
            },
            timer: 1300,
            closeOnClickOutside: false,
            closeOnEsc: false,
            dangerMode: false,
          }).then(() => {
            window.location.href = './index.php'
          });
          break;
        case 1:
          Swal.fire({
            title: "Login",
            text: data.Error,
            icon: "error",
            button: {
              visible: false,
              closeModal: true
            },
            timer: 1300,
            closeOnClickOutside: false,
            closeOnEsc: false,
            dangerMode: false,
          });
          break;
      }
    },
    error: (error) => {
      console.log(error);
    }
  });
}