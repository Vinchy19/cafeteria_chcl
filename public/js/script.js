// Total Orders Chart (Utilisateurs par rôle)
// Assurez-vous que `rolesData` a été injecté par PHP
const totalOrdersChart = new Chart(document.getElementById('totalOrdersChart'), {
  type: 'doughnut',
  data: {
      labels: ['Admin', 'Super', 'User'],
      datasets: [
          {
              label: 'Roles',
              data: [rolesData.admin, rolesData.super, rolesData.user], // Données dynamiques
              backgroundColor: ['#4caf50', '#ff9800', '#f44336'], // Couleurs pour chaque rôle
          },
      ],
  },
  options: {
      responsive: true,
      plugins: {
          legend: {
              display: true,
              position: 'bottom',
          },
      },
  },
});

  
  // Customer Growth Chart
 // Assurez-vous que `roleData` a été injecté par PHP
const customerGrowthChart = new Chart(document.getElementById('customerGrowthChart'), {
  type: 'pie',
  data: {
      labels: ['Etudiants', 'Professeur', 'Personnel admin', 'Invite'],
      datasets: [
          {
              label: 'Clients par Type',
              data: [roleData.etudiant, roleData.professeur, roleData.personnel_admin, roleData.inviter], // Données dynamiques
              backgroundColor: ['#2196f3', '#4caf50', '#ffc107', '#f44336'], // Couleurs pour chaque roleData
          },
      ],
  },
  options: {
      responsive: true,
      plugins: {
          legend: {
              display: true,
              position: 'bottom',
          },
      },
  },
});

  
  // Total Revenue Chart
// Assurez-vous que `ventesAujourdhui` et `totalClients` sont injectés par PHP
const totalRevenueChart = new Chart(document.getElementById('totalRevenueChart'), {
  type: 'doughnut',
  data: {
      labels: ['Ventes Aujourd\'hui', 'Clients Inscrits'],
      datasets: [
          {
              label: 'Quantité',
              data: [ventesAujourdhui, totalClients], // Données dynamiques
              backgroundColor: ['#4caf50', '#00bcd4'], // Couleurs
          },
      ],
  },
  options: {
      responsive: true,
      plugins: {
          legend: {
              display: true,
              position: 'bottom',
          },
      },
  },
});


  
   // Get the modal
   var modal = document.getElementById("myModal");

   // Get the button that opens the modal
   var btn = document.getElementById("myBtn");

   // Get the <span> element that closes the modal
   var span = document.getElementsByClassName("close")[0];

   // When the user clicks the button, open the modal
   btn.onclick = function() {
       modal.style.display = "block";
   };

   // When the user clicks on <span> (x), close the modal
   span.onclick = function() {
       modal.style.display = "none";
   };

   // When the user clicks anywhere outside of the modal, close it
   window.onclick = function(event) {
       if (event.target == modal) {
           modal.style.display = "none";
       }
   };
    // ajoutmodal
      // Get the modal




  // Obtenir les éléments
const openModalBtn = document.getElementById('openModalBtn');
const closeModalBtn = document.getElementById('closeModalBtn');
const formModal = document.getElementById('formModal');

// Ouvrir le modal
openModalBtn.addEventListener('click', () => {
    formModal.classList.remove('d-none');
});

// Fermer le modal
closeModalBtn.addEventListener('click', () => {
    formModal.classList.add('d-none');
});

// Fermer le modal lorsqu'on clique en dehors du contenu
formModal.addEventListener('click', (e) => {
    if (e.target === formModal) {
        formModal.classList.add('d-none');
    }
});

const supprimerUser = document.getElementById('btn-supprimeUser');
form.addEventListener('submit', (e) => {
    e.preventDefault();
    if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
        supprimerUser.submit();
    }
});