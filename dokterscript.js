function toggleHealthCheck(checkbox) {
  const row = checkbox.closest('tr');
  if (checkbox.checked) {
    row.style.backgroundColor = '#d4edda';  // Green background for checked
  } else {
    row.style.backgroundColor = '';  // Reset background for unchecked
  }
}

// Fungsi Keluar
function keluar() {
  window.location.href = 'login.html'; // Arahkan ke halaman login
}

// Show Faculty Data (Triggered by button)
function showFacultyData() {
  const fakultasData = document.getElementById('fakultasData');
  fakultasData.style.display = fakultasData.style.display === 'none' ? 'block' : 'none';
}