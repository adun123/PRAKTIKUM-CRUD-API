<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-12 mb-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
        </div>

        <div class="row">
            {{-- Form Input Produk Makanan --}}
            <div class="col-12 mb-4">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Input Produk Makanan</h3>
                    <form id="produkForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="nama_produk" class="form-control" required />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Gambar Produk (URL saja dulu)</label>
                                <input type="text" name="gambar_produk" class="form-control" />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Pembuatan</label>
                                <input type="date" name="tgl_pembuatan" class="form-control" required />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Kadaluarsa</label>
                                <input type="date" name="tgl_kadaluarsa" class="form-control" required />
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kategori Produk</label>
                                <select name="kategori_produk" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="snack">Snack</option>
                                    <option value="minuman">Minuman</option>
                                    <option value="makanan_berat">Makanan Berat</option>
                                    <option value="lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nama Mitra</label>
                                <input type="text" name="nama_mitra" class="form-control" required />
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabel Data Produk --}}
            <div class="col-12">
                <div class="bg-white shadow-sm rounded-lg p-4">
                    <h3 class="text-lg font-semibold mb-4">Daftar Produk</h3>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Produk</th>
                                <th>Gambar</th>
                                <th>Tanggal Pembuatan</th>
                                <th>Tanggal Kadaluarsa</th>
                                <th>Kategori</th>
                                <th>Nama Mitra</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="produkTableBody">
                            {{-- Data akan ditampilkan lewat JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('produkForm');
        const tableBody = document.getElementById('produkTableBody');

        async function fetchProduk() {
            const response = await fetch('/api/produks');
            const data = await response.json();

            tableBody.innerHTML = '';
            data.forEach(produk => {
                const row = `
                    <tr>
                        <td>${produk.nama_produk}</td>
                        <td><img src="${produk.gambar_produk}" class="img-thumbnail" style="height: 50px;" alt="gambar"></td>
                        <td>${produk.tgl_pembuatan}</td>
                        <td>${produk.tgl_kadaluarsa}</td>
                        <td>${produk.kategori_produk}</td>
                        <td>${produk.nama_mitra}</td>
                        <td class="text-center">
                            <button onclick="hapusProduk(${produk.id})" class="btn btn-danger btn-sm">Hapus</button>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        }

        async function hapusProduk(id) {
            if (!confirm('Yakin ingin menghapus produk ini?')) return;

            const response = await fetch(`/api/produks/${id}`, {
                method: 'DELETE',
            });

            if (response.ok) {
                alert('Produk berhasil dihapus!');
                fetchProduk();
            } else {
                alert('Gagal menghapus produk.');
            }
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            const response = await fetch('/api/produks', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                alert('Produk berhasil ditambahkan!');
                form.reset();
                fetchProduk();
            } else {
                const err = await response.json();
                alert('Gagal: ' + JSON.stringify(err.errors));
            }
        });

        // Load data saat halaman dimuat
        fetchProduk();
    </script>

</body>
</html>
