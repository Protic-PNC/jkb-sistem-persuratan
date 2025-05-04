$(document).ready(function () {
    let kelasTable,
        semuaKelasTable,
        dosenTable,
        dosenSecondTable,
        ketuaJurusanTable,
        ketuaJurusanSecondTable,
        mahasiswaTable,
        adminTable,
        keuanganTable,
        perpustakaanTable;

    $("#kelas-table").hide();
    $("#semua-kelas-table").hide();
    $("#dosen-table").hide();
    $("#dosen-second-table").hide();
    $("#ketua-jurusan-table").hide();
    $("#ketua-jurusan-second-table").hide();
    $("#mahasiswa-table").hide();
    $("#admin-table").hide();
    $("#keuangan-table").hide();
    $("#perpustakaan-table").hide();

    let filterValue = $("#filter-type").val();

    if (filterValue === "kelas") {
        $("#kelas-table").show();
        if (!$.fn.dataTable.isDataTable("#kelas-table")) {
            kelasTable = $("#kelas-table").DataTable();
        }
    } else if (filterValue === "semua_kelas") {
        $("#semua-kelas-table").show();
        if (!$.fn.dataTable.isDataTable("#semua-kelas-table")) {
            semuaKelasTable = $("#semua-kelas-table").DataTable();
        }
    } else if (filterValue === "dosen") {
        $("#dosen-table").show();
        if (!$.fn.dataTable.isDataTable("#dosen-table")) {
            dosenTable = $("#dosen-table").DataTable();
        }
    } else if (filterValue === "ketua-jurusan") {
        $("#ketua-jurusan-table").show();
        if (!$.fn.dataTable.isDataTable("#ketua-jurusan-table")) {
            ketuaJurusanTable = $("#ketua-jurusan-table").DataTable();
        }
    } else {
        $("#mahasiswa-table").show();
        if (!$.fn.dataTable.isDataTable("#mahasiswa-table")) {
            mahasiswaTable = $("#mahasiswa-table").DataTable();
        }
        $("#admin-table").show();
        if (!$.fn.dataTable.isDataTable("#admin-table")) {
            adminTable = $("#admin-table").DataTable();
        }
        $("#ketua-jurusan-second-table").show();
        if (!$.fn.dataTable.isDataTable("#ketua-jurusan-second-table")) {
            ketuaJurusanSecondTable = $(
                "#ketua-jurusan-second-table"
            ).DataTable();
        }
        $("#dosen-second-table").show();
        if (!$.fn.dataTable.isDataTable("#dosen-second-table")) {
            dosenSecondTable = $("#dosen-second-table").DataTable();
        }
        $("#keuangan-table").show();
        if (!$.fn.dataTable.isDataTable("#keuangan-table")) {
            keuanganTable = $("#keuangan-table").DataTable();
        }
        $("#perpustakaan-table").show();
        if (!$.fn.dataTable.isDataTable("#perpustakaan-table")) {
            perpustakaanTable = $("#perpustakaan-table").DataTable();
        }
    }

    $("#filter-type").on("change", function () {
        let selectedValue = $(this).val();

        $("#kelas-table").hide();
        $("#semua-kelas-table").hide();
        $("#dosen-table").hide();
        $("#ketua-jurusan-table").hide();

        if (kelasTable) {
            kelasTable.destroy();
            kelasTable = null;
        }
        if (semuaKelasTable) {
            semuaKelasTable.destroy();
            semuaKelasTable = null;
        }
        if (dosenTable) {
            dosenTable.destroy();
            dosenTable = null;
        }
        if (ketuaJurusanTable) {
            ketuaJurusanTable.destroy();
            ketuaJurusanTable = null;
        }

        if (selectedValue === "kelas") {
            $("#kelas-table").show();
            if (!$.fn.dataTable.isDataTable("#kelas-table")) {
                kelasTable = $("#kelas-table").DataTable();
            }
        } else if (selectedValue === "semua_kelas") {
            $("#semua-kelas-table").show();
            if (!$.fn.dataTable.isDataTable("#semua-kelas-table")) {
                semuaKelasTable = $("#semua-kelas-table").DataTable();
            }
        } else if (selectedValue === "dosen") {
            $("#dosen-table").show();
            if (!$.fn.dataTable.isDataTable("#dosen-table")) {
                dosenTable = $("#dosen-table").DataTable();
            }
        } else if (selectedValue === "ketua_jurusan") {
            $("#ketua-jurusan-table").show();
            if (!$.fn.dataTable.isDataTable("#ketua-jurusan-table")) {
                ketuaJurusanTable = $("#ketua-jurusan-table").DataTable();
            }
        }
    });
});

$(document).ready(function () {
    let namaKelas = $("#table-title").data("kelas");

    $("#filter-type").on("change", function () {
        let selectedValue = $(this).val();

        if (selectedValue === "kelas") {
            $("#table-title").text(
                "Daftar Surat Peringatan karena Pelanggaran Akademik Kelas " +
                    (namaKelas || "...")
            );
        } else if (selectedValue === "semua_kelas") {
            $("#table-title").text(
                "Daftar Surat Peringatan karena Pelanggaran Akademik Semua Kelas"
            );
        } else if (selectedValue === "dosen") {
            $("#table-title").text(
                "Daftar Surat Peringatan karena Pelanggaran Akademik Dosen Wali"
            );
        } else if (selectedValue === "ketua_jurusan") {
            $("#table-title").text(
                "Daftar Surat Peringatan karena Pelanggaran Akademik Ketua Jurusan"
            );
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const loginErrorElement = document.getElementById("loginError");
    if (loginErrorElement) {
        const message = loginErrorElement.getAttribute("data-message");

        Swal.fire({
            icon: "error",
            title: "Login Gagal",
            text: message,
            confirmButtonText: "Ok",
        });
    }

    const togglePassword = document.getElementById("toggle-password");
    const passwordInput = document.getElementById("password");

    togglePassword.addEventListener("click", function () {
        const icon = togglePassword.querySelector("i");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
});

// Menghilangkan dropdown kelas jika role_id == "2"
document.addEventListener("DOMContentLoaded", function () {
    const roleSelect = document.getElementById("role_id");
    const kelasWrapper = document.getElementById("kelas-wrapper");

    function toggleKelas() {
        if (roleSelect.value !== "2") {
            kelasWrapper.style.display = "none";
            const kelasSelect = document.getElementById("kelas_id");
            kelasSelect.value = "";
            kelasSelect.disabled = true;
        } else {
            kelasWrapper.style.display = "";
            document.getElementById("kelas_id").disabled = false;
        }
    }

    toggleKelas();
    roleSelect.addEventListener("change", toggleKelas);
});

document.getElementById("username").addEventListener("input", function () {
    let npm = this.value;

    if (npm) {
        fetch(`/dashboard/admin/user/get-mahasiswa-by-npm?npm=${npm}`)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("nama_mhs").value = data.nama_mhs;
            })
            .catch((error) => console.error("Error:", error));
    }
});

document.getElementById("username").addEventListener("input", function () {
    let npm = this.value;

    if (npm) {
        fetch(`/dashboard/mahasiswa/user/get-mahasiswa-by-npm?npm=${npm}`)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("nama_mhs").value = data.nama_mhs;
            })
            .catch((error) => console.error("Error:", error));
    }
});

document.getElementById("tglSurat").addEventListener("change", function () {
    const tanggal = new Date(this.value);
    const options = {
        weekday: "long",
    };
    const hari = new Intl.DateTimeFormat("id-ID", options).format(tanggal);
    document.getElementById("hari").value = hari;
});

document.getElementById("kelas_id").addEventListener("change", function () {
    let kelasId = this.value;

    if (kelasId) {
        fetch(`/dashboard/admin/user/get-dosen-wali?kelas_id=${kelasId}`)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("nama_dosen_wali").value =
                    data.nama_dosen_wali;
            })
            .catch((error) => console.error("Error:", error));
    }
});

document.getElementById("kelas_id").addEventListener("change", function () {
    let kelasId = this.value;

    if (kelasId) {
        fetch(`/dashboard/dosen-wali/user/get-dosen-wali?kelas_id=${kelasId}`)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("nama_dosen_wali").value =
                    data.nama_dosen_wali;
            })
            .catch((error) => console.error("Error:", error));
    }
});

document.getElementById("username").addEventListener("input", function () {
    let npm = this.value;

    if (npm) {
        fetch(`/dashboard/dosen-wali/user/get-mahasiswa-by-npm?npm=${npm}`)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("nama_mhs").value = data.nama_mhs;
            })
            .catch((error) => console.error("Error:", error));
    }
});

document.getElementById("kelas_id").addEventListener("change", function () {
    let kelasId = this.value;

    if (kelasId) {
        fetch(
            `/dashboard/ketua-jurusan/user/get-dosen-wali?kelas_id=${kelasId}`
        )
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("nama_dosen_wali").value =
                    data.nama_dosen_wali;
            })
            .catch((error) => console.error("Error:", error));
    }
});

document.getElementById("username").addEventListener("input", function () {
    let npm = this.value;

    if (npm) {
        fetch(`/dashboard/ketua-jurusan/user/get-mahasiswa-by-npm?npm=${npm}`)
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("nama_mhs").value = data.nama_mhs;
            })
            .catch((error) => console.error("Error:", error));
    }
});

async function confirmDelete(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });

    swalWithBootstrapButtons
        .fire({
            title: "Apakah ingin menghapus data?",
            text: "Anda tidak dapat memulihkannya setelah ini!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus data!",
            cancelButtonText: "Tidak!",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                swalWithBootstrapButtons
                    .fire({
                        title: "Terhapus!",
                        text: "Data anda telah dihapus.",
                        icon: "success",
                        timer: 3000,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        document.getElementById("delete-form-" + id).submit();
                    });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Dibatalkan",
                    text: "Tidak ada perubahan yang dilakukan.",
                    icon: "error",
                    timer: 3000,
                    showConfirmButton: false,
                });
            }
        });

    const cancelButton = document.querySelector(".swal2-cancel");
    if (cancelButton) {
        cancelButton.style.marginRight = "10px";
    }
}

async function checkusernameExists(username) {
    try {
        let response = await fetch("/check-username", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ username: username }),
        });
        if (!response.ok) {
            throw new Error("Network response was not ok.");
        }
        let data = await response.json();
        return data.exists;
    } catch (error) {
        console.error("Error checking username:", error);
        Swal.fire({
            title: "Error!",
            text: "Gagal memeriksa username. Silakan coba lagi.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return false;
    }
}

async function updateAdminPernyataanMagang() {
    const form = document.getElementById("update-form-magang");
    const formData = new FormData(form);

    const requiredFields = [
        "nama_ortu",
        "alamat",
        "no_telp",
        "nama_mhs",
        "username",
        "jurusan",
        "perguruan_tinggi",
        "tglSurat",
    ];

    for (let field of requiredFields) {
        if (!formData.get(field).trim()) {
            Swal.fire({
                title: "Gagal!",
                text: "Harap isi semua data yang diperlukan.",
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }
    }

    const originalFields = {
        nama_ortu: document.getElementById("original_nama_ortu").value.trim(),
        alamat: document.getElementById("original_alamat").value.trim(),
        no_telp: document.getElementById("original_no_telp").value.trim(),
        nama_mhs: document.getElementById("original_nama_mhs").value.trim(),
        username: document.getElementById("original_username").value.trim(),
        jurusan: document.getElementById("original_jurusan").value.trim(),
        perguruan_tinggi: document
            .getElementById("original_perguruan_tinggi")
            .value.trim(),
        tglSurat: document.getElementById("original_tglSurat").value.trim(),
    };

    let isChanged = false;
    for (let field of requiredFields) {
        const current = formData.get(field).trim();
        const original = originalFields[field];
        if (current !== original) {
            isChanged = true;
            break;
        }
    }

    if (!isChanged) {
        Swal.fire({
            title: "Informasi",
            text: "Tidak ada perubahan data yang dilakukan.",
            icon: "info",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: formData,
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                title: "Data berhasil diubah.",
                icon: "success",
                timer: 3000,
                showConfirmButton: false,
            }).then(() => {
                window.location.href = "/dashboard/admin/pernyataan-magang";
            });
        } else {
            Swal.fire({
                title: "Gagal!",
                text: data.message,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Terjadi Kesalahan!",
            text: "Silakan coba lagi.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        console.error(error);
    }
}

async function updateMahasiswaPernyataanMagang() {
    const form = document.getElementById("update-form-magang");
    const formData = new FormData(form);

    const requiredFields = [
        "nama_ortu",
        "alamat",
        "no_telp",
        "nama_mhs",
        "username",
        "jurusan",
        "perguruan_tinggi",
        "tglSurat",
    ];

    for (let field of requiredFields) {
        if (!formData.get(field).trim()) {
            Swal.fire({
                title: "Gagal!",
                text: "Harap isi semua data yang diperlukan.",
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }
    }

    const originalFields = {
        nama_ortu: document.getElementById("original_nama_ortu").value.trim(),
        alamat: document.getElementById("original_alamat").value.trim(),
        no_telp: document.getElementById("original_no_telp").value.trim(),
        nama_mhs: document.getElementById("original_nama_mhs").value.trim(),
        username: document.getElementById("original_username").value.trim(),
        jurusan: document.getElementById("original_jurusan").value.trim(),
        perguruan_tinggi: document
            .getElementById("original_perguruan_tinggi")
            .value.trim(),
        tglSurat: document.getElementById("original_tglSurat").value.trim(),
    };

    let isChanged = false;
    for (let field of requiredFields) {
        const current = formData.get(field).trim();
        const original = originalFields[field];
        if (current !== original) {
            isChanged = true;
            break;
        }
    }

    if (!isChanged) {
        Swal.fire({
            title: "Informasi",
            text: "Tidak ada perubahan data yang dilakukan.",
            icon: "info",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: formData,
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                title: "Data berhasil diubah.",
                icon: "success",
                timer: 3000,
                showConfirmButton: false,
            }).then(() => {
                window.location.href = "/dashboard/mahasiswa/pernyataan-magang";
            });
        } else {
            Swal.fire({
                title: "Gagal!",
                text: data.message,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Terjadi Kesalahan!",
            text: "Silakan coba lagi.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        console.error(error);
    }
}

async function saveAdminPernyataanMagang() {
    const form = document.getElementById("create-form-magang");
    const formData = new FormData(form);

    const requiredFields = [
        "nama_ortu",
        "alamat",
        "no_telp",
        "nama_mhs",
        "username",
        "jurusan",
        "perguruan_tinggi",
        "tglSurat",
    ];
    for (let field of requiredFields) {
        if (!formData.get(field).trim()) {
            Swal.fire({
                title: "Gagal!",
                text: "Harap isi semua data yang diperlukan.",
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }
    }

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: formData,
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                title: "Berhasil!",
                text: data.message,
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/pernyataan-magang";
            });
        } else {
            Swal.fire({
                title: "Gagal!",
                text: data.message,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Terjadi Kesalahan!",
            text: "Silakan coba lagi.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        console.error(error);
    }
}

async function saveMahasiswaPernyataanMagang() {
    const form = document.getElementById("create-form-magang");
    const formData = new FormData(form);

    const requiredFields = [
        "nama_ortu",
        "alamat",
        "no_telp",
        "nama_mhs",
        "username",
        "jurusan",
        "perguruan_tinggi",
        "tglSurat",
    ];
    for (let field of requiredFields) {
        if (!formData.get(field).trim()) {
            Swal.fire({
                title: "Gagal!",
                text: "Harap isi semua data yang diperlukan.",
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }
    }

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: formData,
        });

        const data = await response.json();

        if (response.ok) {
            Swal.fire({
                title: "Berhasil!",
                text: data.message,
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/pernyataan-magang";
            });
        } else {
            Swal.fire({
                title: "Gagal!",
                text: data.message,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        Swal.fire({
            title: "Terjadi Kesalahan!",
            text: "Silakan coba lagi.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        console.error(error);
    }
}

// Function updateProfile
async function updateProfile() {
    const originalPassword = document
        .getElementById("original_password")
        .value.trim();
    const originalEmail = document
        .getElementById("original_email")
        .value.trim();
    const originalProfilePicture = document
        .getElementById("original_profile_picture")
        .value.trim();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const passwordConfirmation = document
        .getElementById("password_confirmation")
        .value.trim();
    const profilePicture = document.getElementById("profile_picture").files[0];
    const profilePictureName = profilePicture ? profilePicture.name : "";

    if (!email) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    let isDataChanged = false;
    let hasPasswordMismatch = false;
    let samePasswordAsBefore = false;

    if (email !== originalEmail) {
        isDataChanged = true;
    }

    const isProfilePictureChanged =
        profilePicture && profilePictureName !== originalProfilePicture;
    if (isProfilePictureChanged) {
        isDataChanged = true;
    }

    if (password || passwordConfirmation) {
        if (password !== passwordConfirmation) {
            hasPasswordMismatch = true;
        } else if (password !== originalPassword) {
            isDataChanged = true;
        } else {
            samePasswordAsBefore = true;
        }
    }

    if (hasPasswordMismatch) {
        Swal.fire({
            title: "Gagal!",
            text: "Password dan Konfirmasi Password tidak cocok.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    if (!isDataChanged) {
        Swal.fire({
            title: "Informasi",
            text: "Tidak ada perubahan data yang dilakukan.",
            icon: "info",
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    if (
        samePasswordAsBefore &&
        !isProfilePictureChanged &&
        email === originalEmail
    ) {
        Swal.fire({
            title: "Informasi",
            text: "Password baru tidak berbeda dari password lama.",
            icon: "info",
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    const form = document.getElementById("update-form-user");
    const formData = new FormData(form);
    const updateUrl = form.getAttribute("action");

    formData.append("ajax_submit", "true");

    try {
        const response = await fetch(updateUrl, {
            method: "POST",
            body: formData,
        });

        if (response.status === 204) {
            Swal.fire({
                title: "Informasi",
                text: "Tidak ada perubahan data yang dilakukan.",
                icon: "info",
                showConfirmButton: false,
                timer: 3000,
            });
            return;
        }

        if (response.ok) {
            const data = await response.json();
            Swal.fire({
                title: "Data berhasil diubah.",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/profile";
            });
        } else if (response.status === 422) {
            const data = await response.json();
            let errorMessages = "";

            if (data.errors) {
                Object.keys(data.errors).forEach((field) => {
                    if (Array.isArray(data.errors[field])) {
                        data.errors[field].forEach((error) => {
                            errorMessages += `- ${error}<br>`;
                        });
                    }
                });
            } else if (data.message) {
                errorMessages = data.message;
            } else {
                errorMessages =
                    "Terjadi kesalahan validasi yang tidak spesifik.";
            }

            Swal.fire({
                title: "Gagal!",
                html: errorMessages,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                title: "Error Server!",
                text: `Server merespons dengan status: ${response.status}`,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        console.error("Error detail:", error);
        Swal.fire({
            title: "Error Jaringan!",
            text: "Terjadi kesalahan koneksi: " + error.message,
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

// Function akun
async function updateUser() {
    const originalNamaPemilik = document
        .getElementById("original_nama_pemilik")
        .value.trim();
    const originalUsername = document
        .getElementById("original_username")
        .value.trim();
    const originalEmail = document
        .getElementById("original_email")
        .value.trim();
    const originalPassword = document
        .getElementById("original_password")
        .value.trim();
    const originalRole = document.getElementById("original_role").value.trim();
    const originalKelas = document
        .getElementById("original_kelas")
        .value.trim();
    const originalJurusan = document
        .getElementById("original_jurusan")
        .value.trim();
    const originalPerguruanTinggi = document
        .getElementById("original_perguruan_tinggi")
        .value.trim();

    const namaPemilik = document.getElementById("nama_pemilik").value.trim();
    const username = document.getElementById("username").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const passwordConfirm = document
        .getElementById("password_confirmation")
        .value.trim();
    const role = document.getElementById("role_id").value.trim();
    const kelas = document.getElementById("kelas_id").value.trim();
    const jurusan = document.getElementById("jurusan").value.trim();
    const perguruanTinggi = document
        .getElementById("perguruan_tinggi")
        .value.trim();

    const specialRoles = ["1", "5", "6"];
    const isSpecialRole = specialRoles.includes(role);

    if (
        !namaPemilik ||
        !username ||
        !email ||
        !role ||
        (!isSpecialRole && !kelas) ||
        !jurusan ||
        !perguruanTinggi
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    let isDataChanged =
        namaPemilik !== originalNamaPemilik ||
        username !== originalUsername ||
        email !== originalEmail ||
        role !== originalRole ||
        (!isSpecialRole && kelas !== originalKelas) ||
        jurusan !== originalJurusan ||
        perguruanTinggi !== originalPerguruanTinggi;

    let passwordChanged = false;
    if (password || passwordConfirm) {
        if (password && passwordConfirm) {
            if (password !== passwordConfirm) {
                Swal.fire({
                    title: "Gagal!",
                    text: "Password dan Konfirmasi Password tidak cocok.",
                    icon: "error",
                    timer: 3000,
                    showConfirmButton: false,
                });
                return;
            }
            if (password.length < 8) {
                Swal.fire({
                    title: "Gagal!",
                    text: "Password harus terdiri dari minimal 8 karakter.",
                    icon: "error",
                    timer: 3000,
                    showConfirmButton: false,
                });
                return;
            }
            passwordChanged = true;
            isDataChanged = true;
        } else {
            Swal.fire({
                title: "Gagal!",
                text: "Harap isi kedua field password.",
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }
    }

    if (!isDataChanged && !passwordChanged) {
        Swal.fire({
            title: "Informasi",
            text: "Tidak ada perubahan data yang dilakukan.",
            icon: "info",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    const form = document.getElementById("update-form-user");
    const formData = new FormData(form);
    const updateUrl = form.getAttribute("action");

    formData.append("ajax_submit", "true");

    try {
        const response = await fetch(updateUrl, {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            const data = await response.json();
            Swal.fire({
                title: "Data berhasil diubah.",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/user";
            });
        } else if (response.status === 422) {
            const data = await response.json();

            let errorMessages = "";

            if (data.errors && data.errors.username && data.errors.email) {
                Object.keys(data.errors).forEach((field) => {
                    if (Array.isArray(data.errors[field])) {
                        data.errors[field].forEach((error) => {
                            errorMessages += `- ${error}<br>`;
                        });
                    }
                });
            } else if (data.message) {
                errorMessages = data.message;
            } else {
                errorMessages =
                    "Terjadi kesalahan validasi yang tidak spesifik.";
            }

            Swal.fire({
                title: "Gagal!",
                html: errorMessages,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                title: "Error Server!",
                text: `Server merespons dengan status: ${response.status}`,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        console.error("Error detail:", error);
        Swal.fire({
            title: "Error Jaringan!",
            text: "Terjadi kesalahan koneksi: " + error.message,
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

async function saveUser() {
    let namaPemilik = document.getElementById("nama_pemilik").value.trim();
    let username = document.getElementById("username").value.trim();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let confirmPassword = document
        .getElementById("password_confirmation")
        .value.trim();

    if (!namaPemilik || !username || !email || !password || !confirmPassword) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    const form = document.getElementById("create-form-user");
    const formData = new FormData(form);
    const updateUrl = form.getAttribute("action");

    formData.append("ajax_submit", "true");

    try {
        const response = await fetch(updateUrl, {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            const data = await response.json();
            Swal.fire({
                title: "Data berhasil disimpan.",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/user";
            });
        } else if (response.status === 422) {
            const data = await response.json();

            let errorMessages = "";

            if (data.errors && data.errors.username && data.errors.email) {
                Object.keys(data.errors).forEach((field) => {
                    if (Array.isArray(data.errors[field])) {
                        data.errors[field].forEach((error) => {
                            errorMessages += `- ${error}<br>`;
                        });
                    }
                });
            } else if (data.message) {
                errorMessages = data.message;
            } else {
                errorMessages =
                    "Terjadi kesalahan validasi yang tidak spesifik.";
            }

            Swal.fire({
                title: "Gagal!",
                html: errorMessages,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                title: "Error Server!",
                text: `Server merespons dengan status: ${response.status}`,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        console.error("Error detail:", error);
        Swal.fire({
            title: "Error Jaringan!",
            text: "Terjadi kesalahan koneksi: " + error.message,
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

// Function kelas
async function updateKelas() {
    let originalNamaKelas = document
        .getElementById("original_nama_kelas")
        .value.trim();
    let originalUsernameDosenWali = document
        .getElementById("original_username_dosen_wali")
        .value.trim();

    let namaKelas = document.getElementById("nama_kelas").value.trim();
    let usernameDosenWali = document
        .getElementById("username_dosen_wali")
        .value.trim();

    if (!namaKelas || !usernameDosenWali) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    if (
        namaKelas === originalNamaKelas &&
        usernameDosenWali === originalUsernameDosenWali
    ) {
        Swal.fire({
            title: "Informasi",
            text: "Tidak ada perubahan data yang dilakukan.",
            icon: "info",
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    const form = document.getElementById("update-form-kelas");
    const formData = new FormData(form);
    const updateUrl = form.getAttribute("action");

    formData.append("ajax_submit", "true");

    try {
        const response = await fetch(updateUrl, {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            const data = await response.json();
            Swal.fire({
                title: "Data berhasil diubah.",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/kelas";
            });
        } else if (response.status === 422) {
            const data = await response.json();

            let errorMessages = "";

            if (
                data.errors &&
                data.errors.namaKelas &&
                data.errors.usernameDosenWali
            ) {
                Object.keys(data.errors).forEach((field) => {
                    if (Array.isArray(data.errors[field])) {
                        data.errors[field].forEach((error) => {
                            errorMessages += `- ${error}<br>`;
                        });
                    }
                });
            } else if (data.message) {
                errorMessages = data.message;
            } else {
                errorMessages =
                    "Terjadi kesalahan validasi yang tidak spesifik.";
            }

            Swal.fire({
                title: "Gagal!",
                html: errorMessages,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                title: "Error Server!",
                text: `Server merespons dengan status: ${response.status}`,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        console.error("Error detail:", error);
        Swal.fire({
            title: "Error Jaringan!",
            text: "Terjadi kesalahan koneksi: " + error.message,
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

async function saveKelas() {
    let namaKelas = document.getElementById("nama_kelas").value.trim();
    let usernameDosenWali = document
        .getElementById("username_dosen_wali")
        .value.trim();

    if (!namaKelas || !usernameDosenWali) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    const form = document.getElementById("create-form-kelas");
    const formData = new FormData(form);
    const updateUrl = form.getAttribute("action");

    formData.append("ajax_submit", "true");

    try {
        const response = await fetch(updateUrl, {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            const data = await response.json();
            Swal.fire({
                title: "Data berhasil disimpan.",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/kelas";
            });
        } else if (response.status === 422) {
            const data = await response.json();

            let errorMessages = "";

            if (
                data.errors &&
                data.errors.namaKelas &&
                data.errors.usernameDosenWali
            ) {
                Object.keys(data.errors).forEach((field) => {
                    if (Array.isArray(data.errors[field])) {
                        data.errors[field].forEach((error) => {
                            errorMessages += `- ${error}<br>`;
                        });
                    }
                });
            } else if (data.message) {
                errorMessages = data.message;
            } else {
                errorMessages =
                    "Terjadi kesalahan validasi yang tidak spesifik.";
            }

            Swal.fire({
                title: "Gagal!",
                html: errorMessages,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                title: "Error Server!",
                text: `Server merespons dengan status: ${response.status}`,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        console.error("Error detail:", error);
        Swal.fire({
            title: "Error Jaringan!",
            text: "Terjadi kesalahan koneksi: " + error.message,
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

async function updatePelanggaranAkademik(role_id, nama_pemilik) {
    let originalNamaMhs = document
        .getElementById("original_nama_mhs")
        .value.trim();
    let originalusername = document
        .getElementById("original_username")
        .value.trim();
    let originalSemester = document
        .getElementById("original_semester")
        .value.trim();
    let originalKelas = document.getElementById("original_kelas").value.trim();
    let originalPeringatan = document
        .getElementById("original_peringatan")
        .value.trim();
    let originalHari = document.getElementById("original_hari").value.trim();
    let originalTglSurat = document
        .getElementById("original_tglSurat")
        .value.trim();
    let originalPasal = document.getElementById("original_pasal").value.trim();
    let originalIsiPasal = document
        .getElementById("original_isi_pasal")
        .value.trim();

    let namaMhs = document.getElementById("nama_mhs").value.trim();
    let username = document.getElementById("username").value.trim();
    let semester = document.getElementById("semester").value.trim();
    let kelas = document.getElementById("kelas_id").value.trim();
    let peringatan = document.getElementById("peringatan").value.trim();
    let hari = document.getElementById("hari").value.trim();
    let tglSurat = document.getElementById("tglSurat").value.trim();
    let pasal = document.getElementById("pasal").value.trim();
    let isiPasal = document.getElementById("isi_pasal").value.trim();
    let namaPelapor = document.getElementById("nama_pelapor").value.trim();
    let namaDosenWali = document.getElementById("nama_dosen_wali").value.trim();

    let ttdMahasiswa, ttdPelapor, ttdDosenWali, ttdKetuaJurusan;

    if (namaPelapor === nama_pemilik) {
        ttdPelapor = document.getElementById("ttd_pelapor").files[0];
    }
    if (namaDosenWali === nama_pemilik) {
        ttdDosenWali = document.getElementById("ttd_dosen_wali").files[0];
    } else {
        ttdKetuaJurusan =
            document.getElementById("ttd_ketua_jurusan")?.files[0] || null;
    }
    if (role_id !== 2 && role_id !== 3 && role_id !== 4) {
        ttdPelapor = document.getElementById("ttd_pelapor").files[0];
        ttdDosenWali = document.getElementById("ttd_dosen_wali").files[0];
        ttdKetuaJurusan = document.getElementById("ttd_ketua_jurusan").files[0];
    }

    if (role_id !== 3 && role_id !== 4) {
        ttdMahasiswa = document.getElementById("ttd_mahasiswa").files[0];
    }

    if (
        !namaMhs ||
        !username ||
        !semester ||
        !kelas ||
        !peringatan ||
        !hari ||
        !tglSurat ||
        !pasal ||
        !isiPasal
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    if (
        namaMhs === originalNamaMhs &&
        username === originalusername &&
        semester === originalSemester &&
        kelas === originalKelas &&
        peringatan === originalPeringatan &&
        hari === originalHari &&
        tglSurat === originalTglSurat &&
        pasal === originalPasal &&
        isiPasal === originalIsiPasal &&
        !ttdMahasiswa &&
        (role_id === 2 || (!ttdKetuaJurusan && !ttdPelapor && !ttdDosenWali))
    ) {
        Swal.fire({
            title: "Informasi",
            text: "Tidak ada perubahan data yang dilakukan.",
            icon: "info",
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    if (
        (ttdMahasiswa && !ttdMahasiswa.type.startsWith("image/")) ||
        (ttdDosenWali && !ttdDosenWali.type.startsWith("image/")) ||
        (ttdKetuaJurusan && !ttdKetuaJurusan.type.startsWith("image/")) ||
        (ttdPelapor && !ttdPelapor.type.startsWith("image/"))
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Tanda tangan harus berupa gambar.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    Swal.fire({
        title: "Data berhasil diubah.",
        icon: "success",
        showConfirmButton: false,
        timer: 3000,
    }).then(() => {
        document.getElementById("update-form-pelanggaran").submit();
    });
}

async function savePelanggaranAkademik(role_id) {
    let kepada = document.getElementById("nama_mhs").value.trim();
    let username = document.getElementById("username").value.trim();
    let semester = document.getElementById("semester").value.trim();
    let kelas = document.getElementById("kelas_id").value.trim();
    let peringatan = document.getElementById("peringatan").value.trim();
    let hari = document.getElementById("hari").value.trim();
    let tglSurat = document.getElementById("tglSurat").value.trim();
    let pasal = document.getElementById("pasal").value.trim();
    let isi_pasal = document.getElementById("isi_pasal").value.trim();
    let ttdPelapor = document.getElementById("ttd_pelapor").files[0];
    let ttdMahasiswa, ttdKajur, ttdDosen;

    if (role_id !== 3 && role_id !== 4) {
        ttdMahasiswa = document.getElementById("ttd_mahasiswa").files[0];
        ttdKajur = document.getElementById("ttd_ketua_jurusan").files[0];
        ttdDosen = document.getElementById("ttd_dosen_wali").files[0];
    }

    if (
        !kepada ||
        !username ||
        !semester ||
        !kelas ||
        !peringatan ||
        !hari ||
        !tglSurat ||
        !pasal ||
        !isi_pasal
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    if (
        (ttdMahasiswa && !ttdMahasiswa.type.startsWith("image/")) ||
        (ttdPelapor && !ttdPelapor.type.startsWith("image/")) ||
        (ttdDosen && !ttdDosen.type.startsWith("image/")) ||
        (ttdKajur && !ttdKajur.type.startsWith("image/"))
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Tanda tangan harus berupa gambar.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    Swal.fire({
        title: "Data berhasil disimpan.!",
        icon: "success",
        showConfirmButton: false,
        timer: 3000,
    }).then(() => {
        document.getElementById("create-form-pelanggaran").submit();
    });
}

async function savePengunduranDiri(role_id) {
    let nama = document.getElementById("nama_mhs").value.trim();
    let username = document.getElementById("username").value.trim();
    let semester = document.getElementById("semester").value.trim();
    let kelas = document.getElementById("kelas_id").value.trim();
    let jurusan = document.getElementById("jurusan").value.trim();
    let no_telp = document.getElementById("no_telp").value.trim();
    let alamat = document.getElementById("alamat").value.trim();
    let alasan = document.getElementById("alasan").value.trim();
    let tglSurat = document.getElementById("tglSurat").value.trim();
    let ttdMahasiswa, ttdKajur, ttdDosen, ttdKeuangan;

    if (role_id !== 3 && role_id !== 4) {
        ttdMahasiswa = document.getElementById("ttd_mahasiswa").files[0];
        ttdKajur = document.getElementById("ttd_ketua_jurusan").files[0];
        ttdDosen = document.getElementById("ttd_dosen_wali").files[0];
        ttdKeuangan = document.getElementById("ttd_bagian_keuangan").files[0];
    }

    if (
        !nama ||
        !username ||
        !semester ||
        !kelas ||
        !jurusan ||
        !no_telp ||
        !alamat ||
        !alasan ||
        !tglSurat
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    if (
        (ttdMahasiswa && !ttdMahasiswa.type.startsWith("image/")) ||
        (ttdDosen && !ttdDosen.type.startsWith("image/")) ||
        (ttdKajur && !ttdKajur.type.startsWith("image/")) ||
        (ttdKeuangan && !ttdKeuangan.type.startsWith("image/"))
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Tanda tangan harus berupa gambar.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    Swal.fire({
        title: "Data berhasil disimpan.!",
        icon: "success",
        showConfirmButton: false,
        timer: 3000,
    }).then(() => {
        document.getElementById("create-form-pengunduran").submit();
    });
}

async function updatePengunduranDiri(role_id) {
    let originalNamaMhs = document
        .getElementById("original_nama_mhs")
        .value.trim();
    let originalNamaDosen = document
        .getElementById("original_nama_dosen_wali")
        .value.trim();
    let originalNamaKajur = document
        .getElementById("original_nama_ketua_jurusan")
        .value.trim();
    let originalUsername = document
        .getElementById("original_username")
        .value.trim();
    let originalSemester = document
        .getElementById("original_semester")
        .value.trim();
    let originalNoTelp = document
        .getElementById("original_no_telp")
        .value.trim();
    let originalKelas = document.getElementById("original_kelas").value.trim();
    let originalTglSurat = document
        .getElementById("original_tglSurat")
        .value.trim();
    let originalAlasan = document
        .getElementById("original_alasan")
        .value.trim();
    let originalJurusan = document
        .getElementById("original_jurusan")
        .value.trim();
    let originalAlamat = document
        .getElementById("original_alamat")
        .value.trim();

    let namaMhs = document.getElementById("nama_mhs").value.trim();
    let namaDosen = document.getElementById("nama_dosen_wali").value.trim();
    let namaKajur = document.getElementById("nama_ketua_jurusan").value.trim();
    let username = document.getElementById("username").value.trim();
    let semester = document.getElementById("semester").value.trim();
    let kelas = document.getElementById("kelas_id").value.trim();
    let jurusan = document.getElementById("jurusan").value.trim();
    let no_telp = document.getElementById("no_telp").value.trim();
    let alamat = document.getElementById("alamat").value.trim();
    let alasan = document.getElementById("alasan").value.trim();
    let tglSurat = document.getElementById("tglSurat").value.trim();
    let ttdMahasiswa, ttdKajur, ttdDosen;

    if (
        !namaMhs ||
        !namaDosen ||
        !namaKajur ||
        !username ||
        !semester ||
        !kelas ||
        !jurusan ||
        !no_telp ||
        !alamat ||
        !alasan ||
        !tglSurat
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Harap isi semua data yang diperlukan.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }
    if (role_id !== 2 && role_id !== 3 && role_id !== 4) {
        ttdDosen = document.getElementById("ttd_dosen_wali").files[0];
        ttdKajur = document.getElementById("ttd_ketua_jurusan").files[0];
    }
    if (role_id !== 3 && role_id !== 4) {
        ttdMahasiswa = document.getElementById("ttd_mahasiswa").files[0];
    } else if (role_id === 3) {
        ttdKajur = document.getElementById("ttd_ketua_jurusan").files[0];
    } else if (role_id === 4) {
        ttdDosen = document.getElementById("ttd_dosen_wali").files[0];
    }

    if (username !== originalUsername) {
        let usernameExists = await checkusernameExists(username);
        if (usernameExists) {
            Swal.fire({
                title: "Gagal!",
                text: "Harap cek kembali data yang dimasukkan.",
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
            return;
        }
    }

    if (
        namaMhs === originalNamaMhs &&
        namaDosen === originalNamaDosen &&
        namaKajur === originalNamaKajur &&
        username === originalUsername &&
        semester === originalSemester &&
        kelas === originalKelas &&
        jurusan === originalJurusan &&
        no_telp === originalNoTelp &&
        alamat === originalAlamat &&
        alasan === originalAlasan &&
        tglSurat === originalTglSurat &&
        !ttdMahasiswa &&
        (role_id === 2 || (!ttdKajur && !ttdDosen))
    ) {
        Swal.fire({
            title: "Informasi",
            text: "Tidak ada perubahan data yang dilakukan.",
            icon: "info",
            showConfirmButton: false,
            timer: 3000,
        });
        return;
    }

    if (
        (ttdMahasiswa && !ttdMahasiswa.type.startsWith("image/")) ||
        (ttdDosen && !ttdDosen.type.startsWith("image/")) ||
        (ttdKajur && !ttdKajur.type.startsWith("image/"))
    ) {
        Swal.fire({
            title: "Gagal!",
            text: "Tanda tangan harus berupa gambar.",
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
        return;
    }

    Swal.fire({
        title: "Data berhasil diubah.",
        icon: "success",
        showConfirmButton: false,
        timer: 3000,
    }).then(() => {
        document.getElementById("update-form-pengunduran").submit();
    });
}

async function confirmTolak(noSurat) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger",
        },
        buttonsStyling: false,
    });
    swalWithBootstrapButtons
        .fire({
            title: "Apakah surat ini ingin ditolak?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, tolak surat!",
            cancelButtonText: "Tidak!",
            reverseButtons: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                swalWithBootstrapButtons
                    .fire({
                        title: "Ditolak!",
                        text: "Surat telah ditolak.",
                        icon: "success",
                        timer: 3000,
                        showConfirmButton: false,
                    })
                    .then(() => {
                        document
                            .getElementById("tolak-form-" + noSurat)
                            .submit();
                    });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                swalWithBootstrapButtons.fire({
                    title: "Dibatalkan",
                    text: "Tidak ada perubahan yang dilakukan.",
                    icon: "error",
                    timer: 3000,
                    showConfirmButton: false,
                });
            }
        });

    const cancelButton = document.querySelector(".swal2-cancel");
    if (cancelButton) {
        cancelButton.style.marginRight = "10px";
    }
}

// Function import csv
async function importAkunCSV() {
    const form = document.getElementById("import-form-user");
    const formData = new FormData(form);
    const updateUrl = form.getAttribute("action");

    formData.append("ajax_submit", "true");

    try {
        const response = await fetch(updateUrl, {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            const data = await response.json();
            Swal.fire({
                title: "Data berhasil diimport",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/user";
            });
        } else if (response.status === 422) {
            const data = await response.json();

            let errorMessages = "";

            if (data.errors && data.errors.username && data.errors.email) {
                Object.keys(data.errors).forEach((field) => {
                    if (Array.isArray(data.errors[field])) {
                        data.errors[field].forEach((error) => {
                            errorMessages += `- ${error}<br>`;
                        });
                    }
                });
            } else if (data.message) {
                errorMessages = data.message;
            } else {
                errorMessages =
                    "Terjadi kesalahan validasi yang tidak spesifik.";
            }

            Swal.fire({
                title: "Gagal!",
                html: errorMessages,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                title: "Error Server!",
                text: `Server merespons dengan status: ${response.status}`,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        console.error("Error detail:", error);
        Swal.fire({
            title: "Error Jaringan!",
            text: "Terjadi kesalahan koneksi: " + error.message,
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

async function importKelasCSV() {
    const form = document.getElementById("import-form-kelas");
    const formData = new FormData(form);
    const updateUrl = form.getAttribute("action");

    formData.append("ajax_submit", "true");

    try {
        const response = await fetch(updateUrl, {
            method: "POST",
            body: formData,
        });

        if (response.ok) {
            const data = await response.json();
            Swal.fire({
                title: "Data berhasil diimport",
                icon: "success",
                showConfirmButton: false,
                timer: 3000,
            }).then(() => {
                window.location.href = "/dashboard/admin/kelas";
            });
        } else if (response.status === 422) {
            const data = await response.json();

            let errorMessages = "";

            if (
                data.errors &&
                data.errors.usernameDosenWali &&
                data.errors.namaKelas
            ) {
                Object.keys(data.errors).forEach((field) => {
                    if (Array.isArray(data.errors[field])) {
                        data.errors[field].forEach((error) => {
                            errorMessages += `- ${error}<br>`;
                        });
                    }
                });
            } else if (data.message) {
                errorMessages = data.message;
            } else {
                errorMessages =
                    "Terjadi kesalahan validasi yang tidak spesifik.";
            }

            Swal.fire({
                title: "Gagal!",
                html: errorMessages,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        } else {
            Swal.fire({
                title: "Error Server!",
                text: `Server merespons dengan status: ${response.status}`,
                icon: "error",
                timer: 3000,
                showConfirmButton: false,
            });
        }
    } catch (error) {
        console.error("Error detail:", error);
        Swal.fire({
            title: "Error Jaringan!",
            text: "Terjadi kesalahan koneksi: " + error.message,
            icon: "error",
            timer: 3000,
            showConfirmButton: false,
        });
    }
}

async function approveSurat(id) {
    try {
        const response = await fetch(
            `/dashboard/admin/pernyataan-magang/${id}/setujui`,
            {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                    Accept: "application/json",
                },
            }
        );

        const data = await response.json();

        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            timer: 3000,
            text: data.message,
            showConfirmButton: false,
        }).then(() => {
            location.reload();
        });
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Gagal!",
            text: "Terjadi kesalahan saat menyetujui surat.",
        });
    }
}

async function rejectSurat(id) {
    const { value: alasan } = await Swal.fire({
        title: "Tolak Surat",
        input: "text",
        inputLabel: "Alasan Penolakan",
        inputPlaceholder: "Masukkan alasan penolakan",
        showCancelButton: true,
        confirmButtonText: "Tolak",
        cancelButtonText: "Batal",
        inputValidator: (value) => {
            if (!value) {
                return "Alasan wajib diisi!";
            }
        },
    });

    if (alasan) {
        try {
            const response = await fetch(
                `/dashboard/admin/pernyataan-magang/${id}/tolak`,
                {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content,
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify({ alasan }),
                }
            );

            const data = await response.json();

            Swal.fire({
                icon: "success",
                title: "Ditolak!",
                timer: 3000,
                text: data.message,
                showConfirmButton: false,
            }).then(() => {
                location.reload();
            });
        } catch (error) {
            Swal.fire({
                icon: "error",
                title: "Gagal!",
                timer: 3000,
                text: "Terjadi kesalahan saat menolak surat.",
                showConfirmButton: false,
            });
        }
    }
}

async function uploadAdminSuratMagang() {
    const form = document.getElementById("upload-magang-form");

    if (!form) {
        console.error("Form upload tidak ditemukan.");
        return;
    }

    const formData = new FormData(form);
    const action = form.getAttribute("action");

    fetch(action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                .value,
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            Swal.fire({
                icon:
                    data.status === "success"
                        ? "success"
                        : data.status === "info"
                        ? "info"
                        : "error",
                title:
                    data.status === "success"
                        ? "Berhasil"
                        : data.status === "info"
                        ? "Info"
                        : "Gagal",
                text: data.message,
                timer: 3000,
                showConfirmButton: false,
            }).then(() => {
                window.location.href = "/dashboard/admin/pernyataan-magang";
            });
        })
        .catch((error) => {
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Gagal mengupload surat.",
                timer: 3000,
                showConfirmButton: false,
            });
        });
}
async function uploadMahasiswaSuratMagang() {
    const form = document.getElementById("upload-magang-form");

    if (!form) {
        console.error("Form upload tidak ditemukan.");
        return;
    }

    const formData = new FormData(form);
    const action = form.getAttribute("action");

    fetch(action, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                .value,
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            Swal.fire({
                icon:
                    data.status === "success"
                        ? "success"
                        : data.status === "info"
                        ? "info"
                        : "error",
                title:
                    data.status === "success"
                        ? "Berhasil"
                        : data.status === "info"
                        ? "Info"
                        : "Gagal",
                text: data.message,
                timer: 3000,
                showConfirmButton: false,
            }).then(() => {
                window.location.href = "/dashboard/mahasiswa/pernyataan-magang";
            });
        })
        .catch((error) => {
            Swal.fire({
                icon: "error",
                title: "Terjadi Kesalahan",
                text: "Gagal mengupload surat.",
                timer: 3000,
                showConfirmButton: false,
            });
        });
}
