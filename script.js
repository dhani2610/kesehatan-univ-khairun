function toggleHealthCheck(checkbox) {
    const row = checkbox.closest('tr');
    if (checkbox.checked) {
      row.style.backgroundColor = '#d4edda'; 
    } else {
      row.style.backgroundColor = ''; 
    }
  }
  
  function keluar() {
    window.location.href = 'login.html'; // Arahkan ke halaman login
  }
  
  function showFacultyData() {
    const fakultasData = document.getElementById('fakultasData');
    fakultasData.style.display = fakultasData.style.display === 'none' ? 'block' : 'none';
  }
  