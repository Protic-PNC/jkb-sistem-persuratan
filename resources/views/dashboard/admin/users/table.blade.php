<table id="admin-table" class="table text-start align-middle table-bordered table-hover mb-0">
    <thead>
        <tr class="text-dark">
            <th scope="col" style="white-space: nowrap; text-align: center;">No.</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Nama Pemilik</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Username</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Email</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Role</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Kelas</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Jurusan</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Perguruan Tinggi</th>
            <th scope="col" style="white-space: nowrap; text-align: center;">Action</th>
        </tr>
    </thead>
    <tbody id="results-body">
        @foreach ($users as $user)
            <tr>
                <td style="white-space: nowrap; text-align: center;">{{ $user->id }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $user->nama_pemilik }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $user->username }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $user->email }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ optional($user->role)->nama_role }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ optional($user->kelas)->nama_kelas }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $user->jurusan }}</td>
                <td style="white-space: nowrap; text-align: center;">{{ $user->perguruan_tinggi }}</td>
                <td>
                    <div class="d-flex justify-content-start">
                        <a class="btn btn-sm btn-warning me-2"
                            href="/dashboard/admin/user/{{ $user->id }}/edit">Ubah</a>
                        <form action="/dashboard/admin/user/{{ $user->id }}" method="post" class="d-inline"
                            id="delete-form-{{ $user->id }}">
                            @method('delete')
                            @csrf
                            <button type="button" class="btn btn-sm btn-danger me-2 border-0"
                                onclick="confirmDelete('{{ $user->id }}')">Hapus</button>
                        </form>
                    </div>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>
