function keluar() {
    alert('Keluar dari sistem');
  }
  
  function showForm() {
    document.getElementById('formSection').style.display = 'block';
  }
  
  function cancelForm() {
    document.getElementById('formSection').style.display = 'none';
    clearForm();
  }
  
  function deleteData(button) {
    const row = button.parentElement.parentElement;
    row.remove();
  }
  
  function clearForm() {
    document.getElementById('nama').value = '';
    document.getElementById('npm').value = '';
    document.getElementById('fakultas').value = '';
    document.getElementById('jurusan').innerHTML = '<option value="">Pilih Jurusan</option>';
    document.getElementById('jadwal').value = '';
  }
  
  function editData(button) {
    showForm();
  
    const row = button.parentElement.parentElement;
    const nama = row.children[0].textContent;
    const npm = row.children[1].textContent;
    const fakultas = row.children[2].textContent;
    const jurusan = row.children[3].textContent;
    const jadwal = row.children[4].textContent;
  
    document.getElementById('nama').value = nama;
    document.getElementById('npm').value = npm;
    document.getElementById('fakultas').value = fakultas;
    updateJurusan(jurusan); // Memperbarui jurusan berdasarkan fakultas yang dipilih
    document.getElementById('jadwal').value = jadwal;
  

    document.getElementById('mahasiswaForm').setAttribute('data-editing-row', row.rowIndex);
  }
  
  function submitForm(event) {
    event.preventDefault();
  
    const nama = document.getElementById('nama').value;
    const npm = document.getElementById('npm').value;
    const fakultas = document.getElementById('fakultas').value;
    const jurusan = document.getElementById('jurusan').value;
    const jadwal = document.getElementById('jadwal').value;
  
    const table = document.getElementById('dataMahasiswa');
    const editingRowIndex = document.getElementById('mahasiswaForm').getAttribute('data-editing-row');
  
    if (editingRowIndex) {
      const row = table.rows[editingRowIndex - 1];
      row.children[0].textContent = nama;
      row.children[1].textContent = npm;
      row.children[2].textContent = fakultas;
      row.children[3].textContent = jurusan;
      row.children[4].textContent = jadwal;
  
      document.getElementById('mahasiswaForm').removeAttribute('data-editing-row');
    } else {
      
      const newRow = table.insertRow();
      newRow.innerHTML = `
        <td>${nama}</td>
        <td>${npm}</td>
        <td>${fakultas}</td>
        <td>${jurusan}</td>
        <td>${jadwal}</td>
        <td>
          <button onclick="editData(this)">Edit</button>
          <button onclick="deleteData(this)">Delete</button>
        </td>
      `;
    }
  
    cancelForm();
  }
  
  function updateJurusan(selectedJurusan = '') {
    const fakultas = document.getElementById('fakultas').value;
    const jurusanSelect = document.getElementById('jurusan');
    jurusanSelect.innerHTML = '<option value="">Pilih Jurusan</option>';
  
    const jurusanOptions = {
      Teknik: ['Elektro', 'Informatika', 'Sipil'],
      Ekonomi: ['Manajemen', 'Akuntansi', 'Ekonomi Pembangunan'],
      Kedokteran: ['Kedokteran Umum', 'Kedokteran Gigi', 'Farmasi']
    };
  
    if (jurusanOptions[fakultas]) {
      jurusanOptions[fakultas].forEach(jur => {
        const option = document.createElement('option');
        option.value = jur;
        option.textContent = jur;
        jurusanSelect.appendChild(option);
      });
    }
  
    if (selectedJurusan) {
      jurusanSelect.value = selectedJurusan;
    }
  }
  