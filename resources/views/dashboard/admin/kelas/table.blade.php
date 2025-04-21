<table id="admin-table" class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col" style="white-space: nowrap; text-align: center;">No.</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Kelas</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Username Dosen Wali</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody id="results-body">
        @foreach ($kelas as $kls)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $kls->id_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $kls->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $kls->username_dosen_wali }}</td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-warning me-2"
                            href="/dashboard/admin/kelas/{{ $kls->id_kelas }}/edit">Edit</a>
                        <form action="/dashboard/admin/kelas/{{ $kls->id_kelas }}" method="post"
                            class="d-inline" id="delete-form-{{ $kls->id_kelas }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-2 border-0"
                                onclick="confirmDelete('{{ $kls->id_kelas }}')">Hapus</button>
                        </form>
                    </div>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
