

  
  function tampilkanJadwal() {
    const nama = document.getElementById('nama').value;
    const nim = document.getElementById('nim').value;
    const fakultas = document.getElementById('fakultas').value;
    const jurusan = document.getElementById('jurusan').value;
  
    if (!nama || !nim || !fakultas || !jurusan) {
      alert('Harap lengkapi semua data!');
      return;
    }
  
    const jadwalPerFakultas = {
      'Fakultas Teknik': ['Senin - 20 November 2024'],
      'Fakultas Ekonomi': ['Selasa - 21 November 2024'],
      'Fakultas Hukum': ['Rabu - 22 November 2024'],
      'Fakultas Kedokteran': ['Kamis - 23 November 2024']
    };
  
    const jadwal = jadwalPerFakultas[fakultas] || [];
  
    if (jadwal.length === 0) {
      alert('Jadwal untuk fakultas ini belum tersedia.');
      return;
    }
  
    document.getElementById('fakultas').addEventListener('change', function () {
      const fakultas = this.value;
      fetch(`getJurusan.php?fakultas=${fakultas}`)
          .then(response => response.json())
          .then(data => {
              const jurusanDropdown = document.getElementById('jurusan');
              jurusanDropdown.innerHTML = '';
              data.forEach(jurusan => {
                  jurusanDropdown.innerHTML += `<option value="${jurusan.id}">${jurusan.nama}</option>`;
              });
          });
  });
  
  
    document.getElementById('formContainer').style.display = 'none';
    document.getElementById('jadwalOutput').style.display = 'block';
  }
  
  function kembali() {
    document.getElementById('jadwalOutput').style.display = 'none';
    document.getElementById('formContainer').style.display = 'block';
  }
  
  function kirimData(event) {
    event.preventDefault();
    alert('Data berhasil dikirim!');
  }
  
  function keluar() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
      window.location.href = 'login.html';
    }
  }


  
  