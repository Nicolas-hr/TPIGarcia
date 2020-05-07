const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
});

$(document).ready(() => {
  GetContactsData();
});

/**
 * @author Hoarau Nicolas
 * @date 06.05.20
 * 
 * @brief Fonction qui récupère les contacts via un call ajax
 * 
 * @version 1.0.0
 */
function GetContactsData() {
  $.ajax({
    type: "post",
    url: "./php/getContacts.php",
    dataType: "json",
    success: (data) => {
      ShowContactsData(data);
    },
    error: (error) => {
      console.log(error);
    }
  });
}

/**
 * @author Hoarau Nicolas
 * @date 06.05.20
 * 
 * @brief Fonction qui récupère les contacts via un call ajax
 * 
 * @version 1.0.0
 */
function ShowContactsData(data) {
  let contactsData = data.contactsData;
  let userData = data.userData;
  let html = `
  <table class="table">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Email</th>
      <th scope="col">Prénom</th>
      <th scope="col">Nom</th>
      <th scope="col">Numéro de téléphone</th>
      <th>Modifications</th>
    </tr>`;

  $.each(contactsData, (index, contact) => {
    html += `
    <tr id="userInfos">
      <th scope="col">${index + 1}</th>
      <td id="email">${contact.email}</td>
      <td id="firstname">${contact.firstname}</td>
      <td id="lastname">${contact.lastname}</td>
      <td id="phoneNumber">${contact.phoneNumber}</td>`;

    if (userData.idRoles == 2) {
      html += `<td>
                <button type="button" class="btn btn-secondary" onclick="ModifyUser(event)">Modifier</button>
                <button type="button" class="btn btn-danger" onclick="DeleteUser(event)">Supprimer</button>
              </td>`;
    } else {
      if (userData.idUsers == contact.idUsers) {
        html += `<td><button type="button" class="btn btn-secondary">Modifier</button></td>`;
      } else {
        html += `<td></td>`;
      }
    }

    html += `</tr>`;
  });

  html += `</table>`;

  $('#contacts').html(html);
}

/**
 * @author Hoarau Nicolas
 * @date 06.05.20
 * 
 * @brief Fonction qui supprime un utilisateur via un call ajax
 * 
 * @param {*} event 
 * 
 * @version 1.0.0
 */
function DeleteUser(event) {
  let userEmail = event.target.closest("#userInfos").children[1].textContent;

  swal({
    title: "Êtes vous sûr(e) ?",
    text: `Supprimer ${userEmail} ?`,
    icon: "warning",
    buttons: true,
    dangerMode: true,
  })
    .then((willDelete) => {
      if (willDelete) {
        $.ajax({
          type: "post",
          url: "./php/deleteUser.php",
          data: { userEmail: userEmail },
          dataType: "json",
          success: (response) => {
            switch (response.ReturnCode) {
              case 0:
                swal({
                  text: "L'utilisateur à bien été supprimé !",
                  icon: "success",
                  button: {
                    visible: false,
                    closeModal: true
                  },
                  timer: 1300,
                  closeOnClickOutside: false,
                  closeOnEsc: false,
                  dangerMode: false,
                });

                GetContactsData();
                break;
            }
          }
        });
      }
    });
}

/**
 * @author Hoarau Nicolas
 * @date 06.05.20
 * 
 * @brief Fonction qui modifie un utilisateur via un call ajax
 * 
 * @param {*} event 
 * 
 * @version 1.0.0
 */
function ModifyUser(event) {
  let email = event.target.closest("#userInfos").children[1].textContent;
  let firstname = event.target.closest("#userInfos").children[2].textContent;
  let lastname = event.target.closest("#userInfos").children[3].textContent;
  let phoneNumber = event.target.closest("#userInfos").children[4].textContent;

  Swal.fire({
    title: 'Modifier',
    icon: 'warning',
    html:
      `<input id="userEmail" class="swal2-input" type="email" value="${email}">
      <input id="userFirstname" class="swal2-input" type="text" value="${firstname}">
      <input id="userLastname" class="swal2-input" type="text" value="${lastname}">
      <input id="userPhoneNumber" class="swal2-input" type="tel" value="${phoneNumber}">`,
    showCloseButton: true,
    showCancelButton: true,
    onOpen: function () {
      $('#userEmail').focus()
    }
  }).then((willModify) => {
    if (willModify) {
      $.ajax({
        type: "post",
        url: "./php/modifyUser.php",
        data: {
          oldEmail: email,
          userEmail: $('#userEmail').val(),
          userFirstname: $('#userFirstname').val(),
          userLastname: $('#userLastname').val(),
          userPhoneNumber: $('#userPhoneNumber').val(),
        },
        dataType: "json",
        success: (response) => {
          switch (response.ReturnCode) {
            case 0:
              Toast.fire({
                icon: "success",
                title: "L'utilisateur à bien été modifié !",
                timer: 3000
              });

              GetContactsData();
              break;
            case 1:
            case 2:
            case 3:
              Toast.fire({
                icon: "error",
                title: response.Error,
                timer: 3000
              });
              break;
          }
        },
        error: (error) => {
          console.log(error);
        }
      });
    }
  });
}