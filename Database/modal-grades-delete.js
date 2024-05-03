function openDeleteModal(username) {
  fetch('delete-grade.php?username=' + username)
      .then(response => response.text())
      .then(data => {
          document.getElementById('deleteModal').innerHTML = data;
          // Show modal
          document.getElementById('deleteModal').style.display = 'block';
      });
}
function closeModal() {
  document.querySelectorAll('.modal').forEach(modal => {
      modal.style.display = 'none';
  });
}
window.onclick = function(event) {
  if (event.target.classList.contains('modal')) {
      closeModal();
  }
}