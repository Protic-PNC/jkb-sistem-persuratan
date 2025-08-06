<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PelanggaranAkademik;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\StatusPelanggaranAkademikChangedMail;
use App\Mail\PeringatanPelanggaranAkademikMail;

class PelanggaranAkademikController extends Controller
{
    public function index(Request $request)
    {
        $pelanggarans = PelanggaranAkademik::all();
        $totalPelanggaran = PelanggaranAkademik::count();
        $totalDiproses = PelanggaranAkademik::whereNull('status_surat')->orWhere('status_surat', 'diproses')->count();
        $totalDisetujui = PelanggaranAkademik::where('status_surat', 'approved')->count();
        
        // Count documents that have been rejected by at least one user (admin, dosen wali, or ketua jurusan)
        $totalDitolak = PelanggaranAkademik::where('rejected_by_admin', true)
            ->orWhere('rejected_by_dosen_wali', true)
            ->orWhere('rejected_by_ketua_jurusan', true)
            ->count();

        if ($request->ajax()) {
            return view('dashboard.admin.pelanggaran_akademiks.table', compact('pelanggarans'))->render();
        }

        return view('dashboard.admin.pelanggaran_akademiks.index', [
            'title' => 'Pelanggaran Akademik',
            'pelanggarans' => $pelanggarans,
            'totalPelanggaran' => $totalPelanggaran,
            'totalDiproses' => $totalDiproses,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
        ]);
    }

    public function create()
    {
        $kelas = Kelas::all();
        $user = User::where('role_id', 3)->get()->first();
        return view('dashboard.admin.pelanggaran_akademiks.create', compact('kelas', 'user'), [
            'title' => 'Pelanggaran Akademik',
        ]);
    }

    public function store(Request $request)
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
            'ttd_mahasiswa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_pelapor' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_dosen_wali' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $ttdFields = ['ttd_mahasiswa', 'ttd_pelapor', 'ttd_dosen_wali', 'ttd_ketua_jurusan'];
        foreach ($ttdFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($field, $filename, 'public');
                $validatedData[$field] = $path;
            }
        }

        $validatedData['status_surat'] = 'diproses';
        $existingRecord = PelanggaranAkademik::where('nama_mhs', $request->nama_mhs)->first();
        $validatedData['jumlah_peringatan'] = $existingRecord ? $existingRecord->jumlah_peringatan + 1 : 1;

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
        
        // If current user is admin, add that role
        if ($currentUser->role_id == 1) {
            $currentUserRoles[] = 'admin';
        }
        
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

        // Send email to academic advisor
        if ($academicAdvisor) {
            Mail::to($academicAdvisor->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                ->with(["recipientName" => $academicAdvisor->nama_pemilik ?? $academicAdvisor->name]));
        }

        // Send email to department head
        if ($departmentHead) {
            Mail::to($departmentHead->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                ->with(["recipientName" => $departmentHead->nama_pemilik ?? $departmentHead->name]));
        }
        
        // Send email to all admins (skip if current user is an admin)
        foreach ($admins as $admin) {
            if (!in_array('admin', $currentUserRoles) || $admin->id !== $currentUser->id) {
                Mail::to($admin->email)->send((new PeringatanPelanggaranAkademikMail($pelanggaranAkademik))
                    ->with(["recipientName" => $admin->nama_pemilik ?? $admin->name]));
            }
        }

        return redirect('/dashboard/admin/pelanggaran-akademik');
    }


    public function show(PelanggaranAkademik $pelanggaranAkademik)
    {
        return view('dashboard.admin.pelanggaran_akademiks.show', [
            'title' => 'Detail Pelanggaran',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function edit(PelanggaranAkademik $pelanggaranAkademik)
    {
        $kelas = Kelas::all();
        $user = Auth::user();
        return view('dashboard.admin.pelanggaran_akademiks.edit', compact('kelas', 'user'), [
            'title' => 'Edit',
            'pelanggarans' => $pelanggaranAkademik,
        ]);
    }

    public function update(Request $request, PelanggaranAkademik $pelanggaranAkademik)
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
            'ttd_mahasiswa' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_pelapor' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_dosen_wali' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'ttd_ketua_jurusan' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $ttdFields = ['ttd_mahasiswa', 'ttd_pelapor', 'ttd_dosen_wali', 'ttd_ketua_jurusan'];
        foreach ($ttdFields as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = $field . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($field, $filename, 'public');
                $validatedData[$field] = $path;
            } else {
                $validatedData[$field] = $pelanggaranAkademik->$field;
            }
        }

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
        
        // If current user is admin, add that role
        if ($currentUser->role_id == 1) {
            $currentUserRoles[] = 'admin';
        }
        
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
        
        // Send to academic advisor
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
        
        // Send to all admins (skip if current user is an admin)
        foreach ($admins as $admin) {
            if (!in_array('admin', $currentUserRoles) || $admin->id !== $currentUser->id) {
                Mail::to($admin->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaranAkademik, 'data_changed', [
                    'recipientName' => $admin->nama_pemilik ?? $admin->name,
                    'recipientRole' => 'admin',
                    'changeMessage' => $changeMessage
                ]));
            }
        }

        return redirect('/dashboard/admin/pelanggaran-akademik');
    }


    public function destroy(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pelanggaranAkademik->delete();

        return redirect('/dashboard/admin/pelanggaran-akademik');
    }

    public function cetak(PelanggaranAkademik $pelanggaranAkademik)
    {
        $pdf = Pdf::loadview('dashboard.admin.pelanggaran_akademiks.cetak', [
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

        switch (strtolower($role)) {
            case 'admin':
                $pelanggaran->approved_by_admin = true;
                $pelanggaran->rejected_by_admin = false;
                
                // Remove admin's rejection reasons when approved
                if ($pelanggaran->alasan) {
                    $roleLabel = 'Admin';
                    $alasanLines = explode("\n", $pelanggaran->alasan);
                    $filteredAlasanLines = [];
                    
                    // Keep only reasons from other roles
                    foreach ($alasanLines as $line) {
                        $line = trim($line);
                        if (empty($line)) continue;
                        
                        // Skip lines that start with Admin: (case insensitive)
                        if (!preg_match('/^' . preg_quote($roleLabel, '/') . '\s*:/i', $line)) {
                            $filteredAlasanLines[] = $line;
                        }
                    }
                    
                    // Update the alasan field with filtered reasons
                    $pelanggaran->alasan = !empty($filteredAlasanLines) ? implode("\n", $filteredAlasanLines) : null;
                }
                break;
            case 'dosen wali':
                $pelanggaran->approved_by_dosen_wali = true;
                $pelanggaran->rejected_by_dosen_wali = false;
                
                // Remove dosen wali's rejection reasons when approved
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
                break;
            case 'ketua jurusan':
                $pelanggaran->approved_by_ketua_jurusan = true;
                $pelanggaran->rejected_by_ketua_jurusan = false;
                
                // Remove ketua jurusan's rejection reasons when approved
                if ($pelanggaran->alasan) {
                    $roleLabel = 'Ketua jurusan';
                    $alasanLines = explode("\n", $pelanggaran->alasan);
                    $filteredAlasanLines = [];
                    
                    // Keep only reasons from other roles
                    foreach ($alasanLines as $line) {
                        $line = trim($line);
                        if (empty($line)) continue;
                        
                        // Skip lines that start with Ketua jurusan: (case insensitive)
                        if (!preg_match('/^' . preg_quote($roleLabel, '/') . '\s*:/i', $line)) {
                            $filteredAlasanLines[] = $line;
                        }
                    }
                    
                    // Update the alasan field with filtered reasons
                    $pelanggaran->alasan = !empty($filteredAlasanLines) ? implode("\n", $filteredAlasanLines) : null;
                }
                break;
            default:
                return response()->json(['message' => 'Role tidak dikenali.'], 403);
        }

        // Check if all roles have approved
        if (
            $pelanggaran->approved_by_admin &&
            $pelanggaran->approved_by_dosen_wali &&
            $pelanggaran->approved_by_ketua_jurusan
        ) {
            $pelanggaran->status_surat = 'approved';
            $pelanggaran->alasan = null;

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

            // Send approval emails to all users
            // Send to student
            if ($student) {
                Mail::to($student->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $student->nama_pemilik ?? $student->nama_mhs ?? $student->name,
                    'recipientRole' => 'mahasiswa'
                ]));
            }
            
            // Send to reporter
            if ($reporter) {
                Mail::to($reporter->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $reporter->nama_pemilik ?? $reporter->name,
                    'recipientRole' => 'pelapor'
                ]));
            }
            
            // Send to academic advisor
            if ($academicAdvisor) {
                Mail::to($academicAdvisor->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $academicAdvisor->nama_pemilik ?? $academicAdvisor->name,
                    'recipientRole' => 'dosen wali'
                ]));
            }
            
            // Send to department head
            if ($departmentHead) {
                Mail::to($departmentHead->email)->send(new StatusPelanggaranAkademikChangedMail($pelanggaran, 'approved', [
                    'recipientName' => $departmentHead->nama_pemilik ?? $departmentHead->name,
                    'recipientRole' => 'ketua jurusan'
                ]));
            }
            
            // Send to all admins
            foreach ($admins as $admin) {
                // Skip sending email to the current admin
                if ($admin->id === Auth::id()) {
                    continue;
                }
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

        $fieldReject = match ($role) {
            'admin' => 'rejected_by_admin',
            'dosen wali' => 'rejected_by_dosen_wali',
            'ketua jurusan' => 'rejected_by_ketua_jurusan',
            default => null,
        };

        $fieldApprove = match ($role) {
            'admin' => 'approved_by_admin',
            'dosen wali' => 'approved_by_dosen_wali',
            'ketua jurusan' => 'approved_by_ketua_jurusan',
            default => null,
        };

        if (!$fieldReject || !$fieldApprove) {
            return response()->json(['message' => 'Role tidak dikenali.'], 403);
        }

        // Check if document was previously approved
        $wasApproved = $pelanggaran->$fieldApprove;
        
        // Set the reject flag to true and approval flag to false
        $pelanggaran->$fieldReject = true;
        $pelanggaran->$fieldApprove = false;

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

        // Send email to student
        if ($student) {
            Mail::to($student->email)->send(
                new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected', [
                    'rejectedBy' => $roleLabel,
                    'alasan' => $alasanBaru
                ])
            );
        }
        
        // Send email to reporter
        if ($reporter) {
            Mail::to($reporter->email)->send(
                new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected', [
                    'rejectedBy' => $roleLabel,
                    'alasan' => $alasanBaru
                ])
            );
        }

        // Send email to academic advisor (if not the one who rejected)
        if ($academicAdvisor && $role != 'dosen wali') {
            Mail::to($academicAdvisor->email)->send(
                new StatusPelanggaranAkademikChangedMail($pelanggaran, 'rejected', [
                    'rejectedBy' => $roleLabel,
                    'alasan' => $alasanBaru
                ])
            );
        }

        // Send email to department head (if not the one who rejected)
        if ($departmentHead && $role != 'ketua jurusan') {
            Mail::to($departmentHead->email)->send(
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

    public function reminderTandaTangan($noSurat)
    {
        $pelanggaran = PelanggaranAkademik::where('noSurat', $noSurat)->firstOrFail();

        if ($pelanggaran->status_surat !== 'diproses') {
            return response()->json(['message' => 'Surat tidak dalam status diproses.']);
        }

        $penerima = [];

        // Reminder for signatures
        // Pelapor
        if (is_null($pelanggaran->ttd_pelapor)) {
            $user = User::where('nama_pemilik', $pelanggaran->nama_pelapor)->first();
            if ($user) {
                Mail::to($user->email)->send(
                    new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_ttd_pelanggaran', [
                        'nama' => $pelanggaran->nama_pelapor
                    ])
                );
                $penerima[] = 'pelapor (ttd)';
            }
        }

        // Dosen wali
        if (is_null($pelanggaran->ttd_dosen_wali)) {
            $user = User::where('role_id', 4)
                ->where('nama_pemilik', $pelanggaran->nama_dosen_wali)
                ->first();
            if ($user) {
                Mail::to($user->email)->send(
                    new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_ttd_pelanggaran', [
                        'nama' => $pelanggaran->nama_dosen_wali
                    ])
                );
                $penerima[] = 'dosen wali (ttd)';
            }
        }

        // Ketua jurusan
        if (is_null($pelanggaran->ttd_ketua_jurusan)) {
            $user = User::where('role_id', 3)
                ->where('nama_pemilik', $pelanggaran->nama_ketua_jurusan)
                ->first();
            if ($user) {
                Mail::to($user->email)->send(
                    new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_ttd_pelanggaran', [
                        'nama' => $pelanggaran->nama_ketua_jurusan
                    ])
                );
                $penerima[] = 'ketua jurusan (ttd)';
            }
        }

        // Mahasiswa
        if (is_null($pelanggaran->ttd_mahasiswa)) {
            $user = User::where('role_id', 2)
                ->where('nama_pemilik', $pelanggaran->nama_mhs)
                ->first();
            if ($user) {
                Mail::to($user->email)->send(
                    new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_ttd_pelanggaran', [
                        'nama' => $pelanggaran->nama_mhs,
                        'isMahasiswa' => true
                    ])
                );
                $penerima[] = 'mahasiswa (ttd)';
            }
        }

        // Reminder for approval/rejection
        // Admin
        if (!$pelanggaran->approved_by_admin && !$pelanggaran->rejected_by_admin) {
            $admins = User::where('role_id', 1)->get();
            foreach ($admins as $admin) {
                // Skip sending email to the current admin
                if ($admin->id === Auth::id()) {
                    continue;
                }
                Mail::to($admin->email)->send(
                    new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_approval', [
                        'nama' => $admin->nama_pemilik ?? $admin->name,
                        'role' => 'admin'
                    ])
                );
            }
            $penerima[] = 'admin (approval)';
        }

        // Dosen Wali
        if (!$pelanggaran->approved_by_dosen_wali && !$pelanggaran->rejected_by_dosen_wali) {
            $dosenWali = User::where('role_id', 4)
                ->where('nama_pemilik', $pelanggaran->nama_dosen_wali)
                ->first();
            if ($dosenWali) {
                Mail::to($dosenWali->email)->send(
                    new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_approval', [
                        'nama' => $dosenWali->nama_pemilik,
                        'role' => 'dosen wali'
                    ])
                );
                $penerima[] = 'dosen wali (approval)';
            }
        }

        // Ketua Jurusan
        if (!$pelanggaran->approved_by_ketua_jurusan && !$pelanggaran->rejected_by_ketua_jurusan) {
            $ketuaJurusan = User::where('role_id', 3)
                ->where('nama_pemilik', $pelanggaran->nama_ketua_jurusan)
                ->first();
            if ($ketuaJurusan) {
                Mail::to($ketuaJurusan->email)->send(
                    new StatusPelanggaranAkademikChangedMail($pelanggaran, 'reminder_approval', [
                        'nama' => $ketuaJurusan->nama_pemilik,
                        'role' => 'ketua jurusan'
                    ])
                );
                $penerima[] = 'ketua jurusan (approval)';
            }
        }

        if (!empty($penerima)) {
            return response()->json([
                'message' => 'Reminder telah dikirim ke: ' . implode(', ', $penerima)
            ]);
        }

        return response()->json([
            'message' => 'Tidak ada penerima yang valid untuk pengingat tanda tangan atau approval.'
        ]);
    }



    public function resetAll()
    {
        $count = PelanggaranAkademik::count();
        PelanggaranAkademik::truncate();
        if ($count > 0) {
            return response()->json(['success' => true, 'message' => 'Semua data surat Peringatan karena Pelanggaran Akademik berhasil dihapus']);
        } else {
            return response()->json(['success' => false, 'message' => 'Data surat sudah kosong']);
        }
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
