let editingRow = null;

const jurusanData = {
  Teknik: ["Informatika", "Elektro", "Sipil"],
  Ekonomi: ["Manajemen", "Akuntansi"],
  Kedokteran: ["Kedokteran Umum", "Kedokteran Gigi"]
};

function showForm() {
  document.getElementById('formSection').style.display = 'block';
  resetForm();
}

function resetForm() {
  document.getElementById('mahasiswaForm').reset();
  document.getElementById('jurusan').innerHTML = '<option value="">Pilih Jurusan</option>';
  editingRow = null;
}

function cancelForm() {
  document.getElementById('formSection').style.display = 'none';
  resetForm();
}

function editData(button) {
  const row = button.parentElement.parentElement;
  const nama = row.cells[0].textContent;
  const nim = row.cells[1].textContent;
  const fakultas = row.cells[2].textContent;
  const jurusan = row.cells[3].textContent;
  const jadwal = row.cells[4].textContent;

  document.getElementById('nama').value = nama;
  document.getElementById('npm').value = nim;
  document.getElementById('fakultas').value = fakultas;
  updateJurusan(fakultas);
  document.getElementById('jurusan').value = jurusan;
  document.getElementById('jadwal').value = jadwal;

  editingRow = row;
  showForm();
}

function updateJurusan(fakultas = "") {
  const fakultasSelect = document.getElementById("fakultas");
  const jurusanSelect = document.getElementById("jurusan");
  const selectedFakultas = fakultas || fakultasSelect.value;

  jurusanSelect.innerHTML = '<option value="">Pilih Jurusan</option>';

  if (selectedFakultas && jurusanData[selectedFakultas]) {
    jurusanData[selectedFakultas].forEach(jurusan => {
      const option = document.createElement("option");
      option.value = jurusan;
      option.textContent = jurusan;
      jurusanSelect.appendChild(option);
    });
  }
}

function submitForm(event) {
  event.preventDefault();

  const nama = document.getElementById('nama').value;
  const nim = document.getElementById('npm').value;
  const fakultas = document.getElementById('fakultas').value;
  const jurusan = document.getElementById('jurusan').value;
  const jadwal = document.getElementById('jadwal').value;

  if (editingRow) {
    editingRow.cells[0].textContent = nama;
    editingRow.cells[1].textContent = nim;
    editingRow.cells[2].textContent = fakultas;
    editingRow.cells[3].textContent = jurusan;
    editingRow.cells[4].textContent = jadwal;
  } else {
    const table = document.getElementById('dataMahasiswa');
    const newRow = table.insertRow();
    newRow.innerHTML = `
      <td>${nama}</td>
      <td>${nim}</td>
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
