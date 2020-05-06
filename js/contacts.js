$(document).ready(() => {
  GetData();
});

/**
 * @author Hoarau Nicolas
 * @date 06.05.20
 * 
 * @brief Fonction qui récupère les contacts via un call ajax
 * 
 * @version 1.0.0
 */
function GetData() {
  $.ajax({
    type: "post",
    url: "./php/getContacts.php",
    dataType: "json",
    success: (data) => {
      ShowData(data);
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
function ShowData(data) {
  console.log();
  let contactsData = data.contactsData;
  let html = `
  <table class="table">
    <tr>
      <th scope="col">#</th>
      <th scope="col">Email</th>
      <th scope="col">Prénom</th>
      <th scope="col">Nom</th>
      <th scope="col">Numéro de téléphone</th>
    </tr>`;
    
  $.each(contactsData, (index, contact) => { 
    html+= `
    <tr>
      <th scope="col">${index+1}</th>
      <td>${contact.email}</td>
      <td>${contact.firstname}</td>
      <td>${contact.lastname}</td>
      <td>${contact.phoneNumber}</td>
    </tr>`;
     
  });

  html += `</table>`;

  $('#contacts').html(html);
}