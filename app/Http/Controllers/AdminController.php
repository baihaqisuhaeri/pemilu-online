<?php

namespace App\Http\Controllers;

use App\Exports\VotesExport;
use App\Models\Candidate;
use App\Models\DprCandidate;
use App\Models\DprMember;
use App\Models\DprMemberVote;
use App\Models\Vote;
use App\Models\ElectionType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    // =====================
    // DASHBOARD
    // =====================
    public function index()
{
    $candidates = Candidate::with('electionType')->withCount('votes')->orderBy('nomor_urut')->get();
    $dprCandidates = DprCandidate::with(['members' => function($q) {
        $q->withCount('votes');
    }])->withCount(['votes', 'memberVotes'])->orderBy('nomor_urut')->get();

    return view('admin.dashboard', compact('candidates', 'dprCandidates'));
}

    public function export(Request $request)
{
    $format = $request->query('format', 'xlsx');
    $type = $request->query('type', 'presiden');
    $filename = 'hasil-pemilu-' . $type . '-' . now()->format('Y-m-d');

    if ($format === 'csv') {
        return Excel::download(new VotesExport($type), $filename . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    return Excel::download(new VotesExport($type), $filename . '.xlsx');
}

    // =====================
    // KELOLA CALON PRESIDEN & DPD
    // =====================
    public function candidates()
    {
        $electionTypes = ElectionType::whereIn('slug', ['presiden', 'dpd'])->get();
        $candidates = Candidate::with('electionType')->orderBy('election_type_id')->orderBy('nomor_urut')->get();
        return view('admin.candidates', compact('candidates', 'electionTypes'));
    }

    public function storeCandidate(Request $request)
    {
        $rules = [
            'election_type_id' => 'required|exists:election_types,id',
            'nomor_urut'       => 'required|integer',
            'name'             => 'required|string|max:255',
            'photo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'visi_misi'        => 'nullable|string',
        ];

        $electionType = ElectionType::find($request->election_type_id);
        if ($electionType && $electionType->slug === 'presiden') {
            $rules['wakil_name'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $photoPath = null;
            if ($request->hasFile('photo')) {
                $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
                $result = $cloudinary->uploadApi()->upload(
                    $request->file('photo')->getRealPath(),
                    ['folder' => 'candidates']
                    );
                $photoPath = $result['secure_url'];
                }

        Candidate::create([
            'election_type_id' => $request->election_type_id,
            'name'             => $request->name,
            'wakil_name'       => $request->wakil_name ?? null,
            'nomor_urut'       => $request->nomor_urut,
            'visi_misi'        => $request->visi_misi,
            'photo'            => $photoPath,
        ]);

        return redirect()->route('admin.candidates')->with('success', 'Calon berhasil ditambahkan!');
    }

    public function destroyCandidate(Candidate $candidate)
    {
        // if ($candidate->photo) {
        //     Storage::disk('public')->delete($candidate->photo);
        // }
        $candidate->delete();
        return redirect()->route('admin.candidates')->with('success', 'Calon berhasil dihapus!');
    }

    // =====================
    // KELOLA DPR
    // =====================
    public function dpr()
    {
        $dprCandidates = DprCandidate::with('members')->orderBy('nomor_urut')->get();
        return view('admin.dpr', compact('dprCandidates'));
    }

    public function storeDpr(Request $request)
    {
        $request->validate([
            'nomor_urut'  => 'required|integer|unique:dpr_candidates,nomor_urut',
            'nama_partai' => 'required|string|max:255',
            'logo_partai' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'members'     => 'required|array|min:1|max:10',
            'members.*'   => 'required|string|max:255',
        ]);

        $logoPath = null;
                if ($request->hasFile('logo_partai')) {
                    $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
                    $result = $cloudinary->uploadApi()->upload(
                        $request->file('logo_partai')->getRealPath(),
                        ['folder' => 'partai']
                    );
                    $logoPath = $result['secure_url'];
                }

        $dprCandidate = DprCandidate::create([
            'nomor_urut'  => $request->nomor_urut,
            'nama_partai' => $request->nama_partai,
            'logo_partai' => $logoPath,
        ]);

        foreach ($request->members as $index => $nama) {
            if (!empty($nama)) {
                DprMember::create([
                    'dpr_candidate_id' => $dprCandidate->id,
                    'nomor_urut'       => $index + 1,
                    'nama'             => $nama,
                ]);
            }
        }

        return redirect()->route('admin.dpr')->with('success', 'Partai berhasil ditambahkan!');
    }

    public function destroyDpr(DprCandidate $dprCandidate)
    {
        // if ($dprCandidate->logo_partai) {
        //     Storage::disk('public')->delete($dprCandidate->logo_partai);
        // }
        $dprCandidate->delete();
        return redirect()->route('admin.dpr')->with('success', 'Partai berhasil dihapus!');
    }

    // =====================
    // REGISTER USER
    // =====================
    public function registerForm()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.register', compact('users'));
    }

    public function registerStore(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'nik'      => 'required|digits:16|unique:users,nik',
        'password' => 'required|string|min:6|confirmed',
        'role'     => 'required|in:user,admin',
    ]);

    User::create([
        'name'           => $request->name,
        'nik'            => $request->nik,
        'email'          => $request->nik . '@pemilu.com',
        'password'       => Hash::make($request->password),
        'role'           => $request->role,
        'voted_presiden' => false,
        'voted_dpr'      => false,
        'voted_dpd'      => false,
    ]);

    return redirect()->route('admin.register')->with('success', 'User berhasil ditambahkan!');
}

public function resetVoting()
{
    // Reset semua suara
    Vote::truncate();
    DprMemberVote::truncate();

    // Reset status voting semua user
    \App\Models\User::where('role', 'user')->update([
        'voted_presiden' => false,
        'voted_dpr'      => false,
        'voted_dpd'      => false,
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'Semua data voting berhasil direset!');
}

public function resetVotingByType(string $type)
{
    $electionType = ElectionType::where('slug', $type)->first();

    if ($type === 'dpr') {
        // Reset suara DPR
        Vote::where('election_type_id', $electionType->id)->delete();
        DprMemberVote::truncate();
        \App\Models\User::where('role', 'user')->update(['voted_dpr' => false]);
    } elseif ($type === 'presiden') {
        Vote::where('election_type_id', $electionType->id)->delete();
        \App\Models\User::where('role', 'user')->update(['voted_presiden' => false]);
    } elseif ($type === 'dpd') {
        Vote::where('election_type_id', $electionType->id)->delete();
        \App\Models\User::where('role', 'user')->update(['voted_dpd' => false]);
    }

    return redirect()->route('admin.dashboard')->with('success', 'Data voting ' . strtoupper($type) . ' berhasil direset!');
}
}