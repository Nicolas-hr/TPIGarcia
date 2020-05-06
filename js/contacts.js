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
    <tr>
      <th scope="col">${index + 1}</th>
      <td id="email">${contact.email}</td>
      <td id="firstname">${contact.firstname}</td>
      <td id="lastname">${contact.lastname}</td>
      <td id="phoneNumber">${contact.phoneNumber}</td>`;

    if (userData.idRoles == 2) {
      html += `<td>
                <button type="button" class="btn btn-secondary">Modifier</button>
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
 * @brief Fonction qui supprime unn utilisateur via un call ajax
 * 
 * @param {*} event 
 * 
 * @version 1.0.0
 */
function DeleteUser(event) {
  let userEmail = event.target.parentNode.parentNode.children[1].textContent;

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
        data: {userEmail: userEmail},
        dataType: "json",
        success: (data) => {
          switch (data.ReturnCode) {
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