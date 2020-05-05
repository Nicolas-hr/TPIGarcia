



$(document).ready(() => {
  $("#btnRegister").click(Register);
});

function Register(event) {
  if (event) {
    event.preventDefault();
  }

  let email = $('#email').val();
  let lastname = $('#lastname').val();
  let firstname = $('#firstname').val();
  let phoneNumber = $('#phoneNumber').val();
  let password = $('#password').val();
  let verifyPassword = $('#verifyPassword').val();

  if (lastname.length == 0) {
    $('#lastname').focus();
    return;
  }

  if (firstname.length == 0) {
    $('#firstname').focus();
    return;
  }

  if (phoneNumber.length == 0) {
    $('#phoneNumber').focus();
    return;
  }

  if (email.length == 0) {
    $('#email').focus();
    return;
  }

  if (password.length == 0) {
    $('#password').focus();
    return;
  }

  if (verifyPassword.length == 0) {
    $('#verifyPassword').focus();
    return;
  }

  $.ajax({
    type: "post",
    url: "./php/register.php",
    data: {
      email: email,
      lastname: lastname,
      firstname: firstname,
      phoneNumber: phoneNumber,
      password: password,
      verifyPassword: verifyPassword
    },
    dataType: "json",
    success: (data) => {
      
      switch (data.ReturnCode) {
        case 0:
          swal({
            title: "Inscription",
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
            window.location.href = './login.php'
          });
          break;

          case 1:
          case 2:
          case 3:
            swal({
              title: "Inscription",
              text: data.Error,
              icon: "error",
              button: {
                visible: false,
                closeModal: true
              },
              timer: 1500,
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