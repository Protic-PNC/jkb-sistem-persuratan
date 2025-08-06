<?php

namespace App\Http\Controllers\Dosen_Wali;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PelanggaranAkademik;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\PeringatanPelanggaranAkademikMail;
use App\Mail\StatusPelanggaranAkademikChangedMail;


class PelanggaranAkademikController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $pelanggaranSemuaKelasDosen = PelanggaranAkademik::where('nama_dosen_wali', $user->nama_pemilik)->latest()->get();
        $pelanggaranDosens = PelanggaranAkademik::where('nama_pelapor', $user->nama_pemilik)->latest()->get();
        $totalPelanggaranAkademikKelas = $pelanggaranSemuaKelasDosen->count();
        $totalPelanggaranAkademikDosen = $pelanggaranDosens->count();
        $rekap = $pelanggaranSemuaKelasDosen->merge($pelanggaranDosens);
        $totalPelanggaranAkademik = $rekap->count();
        $totalDisetujui = $rekap->where('status_surat', 'approved')->count();
        $totalDitolak = PelanggaranAkademik::where('rejected_by_admin', true)
            ->orWhere('rejected_by_dosen_wali', true)
            ->orWhere('rejected_by_ketua_jurusan', true)
            ->count();
        $totalDiproses = $rekap->whereNotIn('status_surat', ['approved', 'rejected'])->count();

        $kelasIds = Kelas::where('username_dosen_wali', $user->username)->pluck('id_kelas');
        $kelas = Kelas::find($kelasIds->first());

        return view('dashboard.dosen_wali.pelanggaran_akademiks.index', [
            'title' => 'Pelanggaran Akademik',
            'pelanggaranSemuaKelasDosen' => $pelanggaranSemuaKelasDosen,
            'pelanggaranDosens' => $pelanggaranDosens,
            'totalPelanggaranAkademikKelas' => $totalPelanggaranAkademikKelas,
            'totalPelanggaranAkademikDosen' => $totalPelanggaranAkademikDosen,
            'totalPelanggaranAkademik' => $totalPelanggaranAkademik,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
            'totalDiproses' => $totalDiproses,
            'kelas' => $kelas,
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = User::where('role_id', 3)->get()->first();
        return view('dashboard.dosen_wali.pelanggaran_akademiks.create', compact('kelas', 'user'), [
            'title' => 'Pelanggaran Akademik',
        ]);
    }

    public function show(PelanggaranAkademik $pelanggaranAkademik)
    {
        return view('dashboard.dosen_wali.pelanggaran_akademiks.show', [
            'title' => 'Pelanggaran Akademik',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function edit(PelanggaranAkademik $pelanggaranAkademik)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.dosen_wali.pelanggaran_akademiks.edit', compact('kelas', 'user'), [
            'title' => 'Edit',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function store(Request $request, PelanggaranAkademik $pelanggaranAkademik)
    {
        $validatedData = $request->validate([
            'nama_mhs' => 'required|string|max:255',
            'nama_pelapor' => 'required|string|max:255',
            'nama_dosen_wali' => 'required|string|max:255',
            'nama_ketua_jurusan' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'kelas_id' => 'required|string|max:255',
            'peringatan' => 'required|string|in:lisan,tertulis',
            'hari' => 'required|string|max:255',
            'tglSurat' => 'required|date',
            'pasal' => 'required|string|max:255',
            'isi_pasal' => 'required|string|max:255',
            'ttd_pelapor' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('ttd_pelapor')) {
            $file = $request->file('ttd_pelapor');
            $filename = 'ttd_pelapor_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('ttd_pelapor', $filename);
            $validatedData['ttd_pelapor'] = str_replace('public/', 'storage/', $path);
        }

        $existingRecord = $pelanggaranAkademik->where('nama_mhs', $request->nama_mhs)->first();
        $validatedData['jumlah_peringatan'] = $existingRecord ? $existingRecord->jumlah_peringatan + 1 : 1;
        $validatedData['status_surat'] = 'diproses';

        $pelanggaranAkademik = PelanggaranAkademik::create($validatedData);

        // Get all required users
        $student = User::where('username', $request->username)->first();
        $reporter = User::where('nama_pemilik', $request->nama_pelapor)->first();
        $academicAdvisor = User::where('role_id', 4)
            ->where('nama_pemilik', $request->nama_dosen_wali)
            ->first();
        $departmentHead = User::where('role_id', 3)
            ->where('nama_pemilik', $request->nama_ketua_jurusan)
            ->first();
        $admins = User::where('role_id', 1)->get();

        // Track which roles the current user has (they might have multiple)
        $currentUser = Auth::user();
        $currentUserRoles = [];
        
        // If current user is the reporter, add that role
        if ($reporter && $reporter->id === $currentUser->id) {
            $currentUserRoles[] = 'reporter';
        }
        
        // Send email to student
        if ($student) {
            Mail::to($student->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                ->with(["recipientName" => $student->nama_pemilik ?? $student->nama_mhs ?? $student->name]));
        }
        
        // Send email to reporter (skip if current user is the reporter)
        if ($reporter && !in_array('reporter', $currentUserRoles)) {
            Mail::to($reporter->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                ->with(["recipientName" => $reporter->nama_pemilik ?? $reporter->name]));
        }

        // Academic advisor should get the email even if they're also the current user
        // because they need to be notified in their capacity as a dosen wali
        if ($academicAdvisor) {
            Mail::to($academicAdvisor->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                ->with(["recipientName" => $academicAdvisor->nama_pemilik ?? $academicAdvisor->name]));
        }

        // Send email to department head
        if ($departmentHead) {
            Mail::to($departmentHead->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                ->with(["recipientName" => $departmentHead->nama_pemilik ?? $departmentHead->name]));
        }
        
        // Send email to all admins
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                ->with(["recipientName" => $admin->nama_pemilik ?? $admin->name]));
        }

        return redirect('/dashboard/dosen-wali/pelanggaran-akademik');
    }

    public function update(Request $request, PelanggaranAkademik $pelanggaranAkademik)
    {
        $rules = [
            'nama_mhs' => 'required|string|max:255',
            'nama_pelapor' => 'required|string|max:255',
            'nama_dosen_wali' => 'required|string|max:255',
            'nama_ketua_jurusan' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'semester' => 'required|string|max:255',
            'kelas_id' => 'required|string|max:255',
            'peringatan' => 'required|string|in:lisan,tertulis',
            'hari' => 'required|string|max:255',
            'tglSurat' => 'required|date',
            'pasal' => 'required|string|max:255',
            'isi_pasal' => 'required|string|max:255',
            'ttd_pelapor' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_dosen_wali' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $validatedData = $request->validate($rules);

        if ($request->hasFile('ttd_pelapor')) {
            if ($pelanggaranAkademik->ttd_pelapor) {
                Storage::disk('public')->delete($pelanggaranAkademik->ttd_pelapor);
            }
            $file = $request->file('ttd_pelapor');
            $filename = 'ttd_pelapor_' . time() . '.' . $file->getClientOriginalExtension();
            $validatedData['ttd_pelapor'] = $file->storeAs('ttd_pelapor', $filename);
        }

        if ($request->hasFile('ttd_dosen_wali')) {
            if ($pelanggaranAkademik->ttd_dosen_wali) {
                Storage::disk('public')->delete($pelanggaranAkademik->ttd_dosen_wali);
            }
            $file = $request->file('ttd_dosen_wali');
            $filename = 'ttd_dosen_wali_' . time() . '.' . $file->getClientOriginalExtension();
            $validatedData['ttd_dosen_wali'] = $file->storeAs('ttd_dosen_wali', $filename);
        }

        $validatedData['jumlah_peringatan'] = $request->nama_mhs === $pelanggaranAkademik->nama_mhs
            ? $pelanggaranAkademik->jumlah_peringatan
            : $pelanggaranAkademik->jumlah_peringatan + 1;

        // Store the original status
        $originalStatus = $pelanggaranAkademik->status_surat;
        
        // Reset all user-specific approval/rejection statuses
        $validatedData['approved_by_admin'] = false;
        $validatedData['approved_by_dosen_wali'] = false;
        $validatedData['approved_by_ketua_jurusan'] = false;
        $validatedData['rejected_by_admin'] = false;
        $validatedData['rejected_by_dosen_wali'] = false;
        $validatedData['rejected_by_ketua_jurusan'] = false;
        
        // Preserve the final status ('diproses', 'approved', 'rejected')
        // If status was 'approved' or 'rejected', set it back to 'diproses' since changes were made
        if ($originalStatus === 'approved' || $originalStatus === 'rejected') {
            $validatedData['status_surat'] = 'diproses';
        } else {
            $validatedData['status_surat'] = $originalStatus;
        }

        // Update the record
        $pelanggaranAkademik->update($validatedData);
        
        // Get all users to notify about the changes
        $student = User::where('username', $pelanggaranAkademik->username)->first();
        $reporter = User::where('nama_pemilik', $pelanggaranAkademik->nama_pelapor)->first();
        $academicAdvisor = User::where('role_id', 4)
            ->where('nama_pemilik', $pelanggaranAkademik->nama_dosen_wali)
            ->first();
        $departmentHead = User::where('role_id', 3)
            ->where('nama_pemilik', $pelanggaranAkademik->nama_ketua_jurusan)
            ->first();
        $admins = User::where('role_id', 1)->get();
        
        // Track which roles the current user has (they might have multiple)
        $currentUser = Auth::user();
        $currentUserRoles = [];
        
        // If current user is the reporter, add that role
        if ($reporter && $reporter->id === $currentUser->id) {
            $currentUserRoles[] = 'reporter';
        }
        
        // Send notification emails about the changes
        $changeMessage = "Perubahan telah dibuat pada dokumen pelanggaran akademik. Status persetujuan/penolakan telah direset.";
        if ($originalStatus === 'approved' || $originalStatus === 'rejected') {
            $changeMessage .= " Status dokumen telah dikembalikan ke 'diproses'.";
        }
        
        // Send to student
        if ($student) {
            Mail::to($student->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaranAkademik, 'data_changed', [
                'recipientName' => $student->nama_pemilik ?? $student->nama_mhs ?? $student->name,
                'recipientRole' => 'mahasiswa',
                'changeMessage' => $changeMessage
            ]));
        }
        
        // Send to reporter (skip if current user is the reporter)
        if ($reporter && !in_array('reporter', $currentUserRoles)) {
            Mail::to($reporter->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaranAkademik, 'data_changed', [
                'recipientName' => $reporter->nama_pemilik ?? $reporter->name,
                'recipientRole' => 'pelapor',
                'changeMessage' => $changeMessage
            ]));
        }
        
        // Academic advisor should get the email even if they're also the current user
        // because they need to be notified in their capacity as a dosen wali
        if ($academicAdvisor) {
            Mail::to($academicAdvisor->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaranAkademik, 'data_changed', [
                'recipientName' => $academicAdvisor->nama_pemilik ?? $academicAdvisor->name,
                'recipientRole' => 'dosen wali',
                'changeMessage' => $changeMessage
            ]));
        }
        
        // Send to department head
        if ($departmentHead) {
            Mail::to($departmentHead->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaranAkademik, 'data_changed', [
                'recipientName' => $departmentHead->nama_pemilik ?? $departmentHead->name,
                'recipientRole' => 'ketua jurusan',
                'changeMessage' => $changeMessage
            ]));
        }
        
        // Send to all admins
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaranAkademik, 'data_changed', [
                'recipientName' => $admin->nama_pemilik ?? $admin->name,
                'recipientRole' => 'admin',
                'changeMessage' => $changeMessage
            ]));
        }

        return redirect('/dashboard/dosen-wali/pelanggaran-akademik');
    }

    public function destroy(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pelanggaranAkademik->delete();

        return redirect('/dashboard/dosen-wali/pelanggaran-akademik');
    }

    public function cetak(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pdf = Pdf::loadview('dashboard.dosen_wali.pelanggaran_akademiks.cetak', [
            'title' => 'Cetak',
            'pelanggarans' => $pelanggaranAkademik,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Surat Peringatan karena Pelanggaran Peraturan Akademik_' . $pelanggaranAkademik->nama_mhs . '_' . $pelanggaranAkademik->username . '_' . '.pdf');
    }

    public function setujui($id)
    {
        $pelanggaran = PelanggaranAkademik::findOrFail($id);
        $user = Auth::user();
        $role = $user->role->nama_role ?? null;

        if (!$role) {
            return response()->json(['message' => 'Role tidak valid.'], 403);
        }

        if (strtolower($role) === 'dosen wali') {
            $pelanggaran->approved_by_dosen_wali = true;
            $pelanggaran->rejected_by_dosen_wali = false;
            
            // Remove only dosen wali's rejection reasons when approved
            if ($pelanggaran->alasan) {
                $roleLabel = 'Dosen wali';
                $alasanLines = explode("\n", $pelanggaran->alasan);
                $filteredAlasanLines = [];
                
                // Keep only reasons from other roles
                foreach ($alasanLines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    
                    // Skip lines that start with Dosen wali: (case insensitive)
                    if (!preg_match('/^' . preg_quote($roleLabel, '/') . '\s*:/i', $line)) {
                        $filteredAlasanLines[] = $line;
                    }
                }
                
                // Update the alasan field with filtered reasons
                $pelanggaran->alasan = !empty($filteredAlasanLines) ? implode("\n", $filteredAlasanLines) : null;
            }
        } else {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menyetujui dokumen ini.'], 403);
        }

        // Check if all roles have approved
        if (
            $pelanggaran->approved_by_admin &&
            $pelanggaran->approved_by_dosen_wali &&
            $pelanggaran->approved_by_ketua_jurusan
        ) {
            $pelanggaran->status_surat = 'approved';
            // Don't reset all reasons when fully approved
            // $pelanggaran->alasan = null;

            // Get all relevant users for notifications
            $student = User::where('username', $pelanggaran->username)->first();
            $reporter = User::where('nama_pemilik', $pelanggaran->nama_pelapor)->first();
            $academicAdvisor = User::where('role_id', 4)
                ->where('nama_pemilik', $pelanggaran->nama_dosen_wali)
                ->first();
            $departmentHead = User::where('role_id', 3)
                ->where('nama_pemilik', $pelanggaran->nama_ketua_jurusan)
                ->first();
            $admins = User::where('role_id', 1)->get();

            // Track which roles the current user has (they might have multiple)
            $currentUser = Auth::user();
            $currentUserRoles = [];
            
            // If current user is the reporter, add that role
            if ($reporter && $reporter->id === $currentUser->id) {
                $currentUserRoles[] = 'reporter';
            }

            // Send approval emails to all users
            // Send to student
            if ($student) {
                Mail::to($student->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $student->nama_pemilik ?? $student->nama_mhs ?? $student->name,
                    'recipientRole' => 'mahasiswa'
                ]));
            }
            
            // Send to reporter (skip if current user is the reporter)
            if ($reporter && !in_array('reporter', $currentUserRoles)) {
                Mail::to($reporter->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $reporter->nama_pemilik ?? $reporter->name,
                    'recipientRole' => 'pelapor'
                ]));
            }
            
            // We don't send to academic advisor since that's the dosen wali who's approving
            // But we only skip this if the current user is specifically the academic advisor
            
            // Send to department head
            if ($departmentHead) {
                Mail::to($departmentHead->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $departmentHead->nama_pemilik ?? $departmentHead->name,
                    'recipientRole' => 'ketua jurusan'
                ]));
            }
            
            // Send to all admins
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $admin->nama_pemilik ?? $admin->name,
                    'recipientRole' => 'admin'
                ]));
            }
        } 
        // If status was rejected but now someone approved, change to diproses
        else if ($pelanggaran->status_surat === 'rejected') {
            $pelanggaran->status_surat = 'diproses';
        }

        $pelanggaran->save();

        return response()->json(['message' => 'Persetujuan telah dicatat.']);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate(['alasan' => 'required|string']);

        $pelanggaran = PelanggaranAkademik::findOrFail($id);
        $user = Auth::user();
        $role = strtolower($user->role->nama_role ?? 'user');
        $roleLabel = ucfirst($role);

        if ($role !== 'dosen wali') {
            return response()->json(['message' => 'Anda tidak memiliki izin untuk menolak dokumen ini.'], 403);
        }

        // Check if document was previously approved
        $wasApproved = $pelanggaran->approved_by_dosen_wali;
        
        // Set the reject flag to true and approval flag to false
        $pelanggaran->rejected_by_dosen_wali = true;
        $pelanggaran->approved_by_dosen_wali = false;

        // Format the new reason
        $alasanBaru = trim($request->alasan);
        if (!str_starts_with(strtolower($alasanBaru), strtolower($roleLabel . ':'))) {
            $alasanBaru = $roleLabel . ': ' . $alasanBaru;
        }
        
        // If it was previously approved, add note about approval revocation
        if ($wasApproved) {
            $alasanBaru = $roleLabel . ': [PERSETUJUAN DIBATALKAN] ' . $alasanBaru;
        }

        // Remove previous reasons from this same user/role and add the new one
        $alasanLama = $pelanggaran->alasan ?? '';
        $alasanLines = explode("\n", $alasanLama);
        $filteredAlasanLines = [];
        
        // Keep only reasons from other roles
        foreach ($alasanLines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Skip lines that start with this role's label (case insensitive)
            if (!preg_match('/^' . preg_quote($roleLabel, '/') . '\s*:/i', $line)) {
                $filteredAlasanLines[] = $line;
            }
        }
        
        // Add the new reason
        $filteredAlasanLines[] = $alasanBaru;
        
        // Combine all reasons with line breaks
        $pelanggaran->alasan = implode("\n", $filteredAlasanLines);

        // Update status to rejected only if all parties have rejected
        if (
            $pelanggaran->rejected_by_admin &&
            $pelanggaran->rejected_by_dosen_wali &&
            $pelanggaran->rejected_by_ketua_jurusan
        ) {
            $pelanggaran->status_surat = 'rejected';
        } else {
            // Otherwise, keep it in processing state
            $pelanggaran->status_surat = 'diproses';
        }
        
        $pelanggaran->save();

        // Get all relevant users to notify
        $student = User::where('username', $pelanggaran->username)->first();
        $reporter = User::where('nama_pemilik', $pelanggaran->nama_pelapor)->first();
        $academicAdvisor = User::where('role_id', 4)
            ->where('nama_pemilik', $pelanggaran->nama_dosen_wali)
            ->first();
        $departmentHead = User::where('role_id', 3)
            ->where('nama_pemilik', $pelanggaran->nama_ketua_jurusan)
            ->first();
        $admins = User::where('role_id', 1)->get();

        // Track which roles the current user has (they might have multiple)
        $currentUser = Auth::user();
        $currentUserRoles = [];
        
        // If current user is the reporter, add that role
        if ($reporter && $reporter->id === $currentUser->id) {
            $currentUserRoles[] = 'reporter';
        }

        // Send email to student
        if ($student) {
            Mail::to($student->email)->send(
                new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected', [
                    'rejectedBy' => $roleLabel,
                    'alasan' => $alasanBaru
                ])
            );
        }
        
        // Send email to reporter (skip if current user is the reporter)
        if ($reporter && !in_array('reporter', $currentUserRoles)) {
            Mail::to($reporter->email)->send(
                new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected', [
                    'rejectedBy' => $roleLabel,
                    'alasan' => $alasanBaru
                ])
            );
        }

        // We don't send to academic advisor since that's the dosen wali who's rejecting

        // Send email to department head
        if ($departmentHead) {
            Mail::to($departmentHead->email)->send(
                new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected', [
                    'rejectedBy' => $roleLabel,
                    'alasan' => $alasanBaru
                ])
            );
        }

        // Send email to all admins
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(
                new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected', [
                    'rejectedBy' => $roleLabel,
                    'alasan' => $alasanBaru
                ])
            );
        }

        $message = $wasApproved ? 
            'Persetujuan dibatalkan, pelanggaran ditolak dan alasan telah dicatat.' : 
            'Pelanggaran ditolak dan alasan telah dicatat.';

        return response()->json(['message' => $message]);
    }

    public function updateAlasan(Request $request, $noSurat)
    {
        $request->validate([
            'alasan' => 'nullable|string',
        ]);

        $pelanggaran = PelanggaranAkademik::where('noSurat', $noSurat)->firstOrFail();
        $user = Auth::user();
        $role = ucfirst(strtolower($user->role->nama_role ?? 'User'));

        // Format the alasan with the role prefix if not already present
        $alasanBaru = trim($request->alasan);
        if (!empty($alasanBaru) && !str_starts_with(strtolower($alasanBaru), strtolower($role . ':'))) {
            $alasanBaru = $role . ': ' . $alasanBaru;
        }
        
        // Remove previous reasons from this same user/role and add the new one
        $alasanLama = $pelanggaran->alasan ?? '';
        $alasanLines = explode("\n", $alasanLama);
        $filteredAlasanLines = [];
        
        // Keep only reasons from other roles
        foreach ($alasanLines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            
            // Skip lines that start with this role's label (case insensitive)
            if (!preg_match('/^' . preg_quote($role, '/') . '\s*:/i', $line)) {
                $filteredAlasanLines[] = $line;
            }
        }
        
        // Add the new reason if not empty
        if (!empty($alasanBaru)) {
            $filteredAlasanLines[] = $alasanBaru;
        }
        
        // Combine all reasons with line breaks
        $newAlasan = !empty($filteredAlasanLines) ? implode("\n", $filteredAlasanLines) : null;
        
        // Check if there's any change in the alasan
        if ($pelanggaran->alasan === $newAlasan) {
            return response()->json([
                'status' => 'info',
                'message' => 'Tidak ada perubahan yang dilakukan pada alasan.'
            ]);
        }
        
        $pelanggaran->alasan = $newAlasan;
        $pelanggaran->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Alasan penolakan berhasil diperbarui.'
        ]);
    }
}
