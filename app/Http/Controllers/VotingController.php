<?php

namespace App\Http\Controllers;



use App\Models\Candidate;
use App\Models\DprCandidate;
use App\Models\DprMemberVote;
use App\Models\ElectionType;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class VotingController extends Controller
{
    // Halaman utama - pilih jenis pemilihan
    public function index()
    {
        $user = Auth::user();
        return view('voting.index', compact('user'));
    }

    // Halaman coblos Presiden
    public function presiden()
    {
        $user = Auth::user();
        if ($user->voted_presiden) {
            return redirect()->route('voting')->with('info', 'Anda sudah memilih Presiden.');
        }
        $electionType = ElectionType::where('slug', 'presiden')->first();
        $candidates = Candidate::where('election_type_id', $electionType->id)->orderBy('nomor_urut')->get();
        return view('voting.presiden', compact('candidates'));
    }

    public function storePresiden(Request $request)
    {
        $request->validate(['candidate_id' => 'required|exists:candidates,id']);
        $user = Auth::user();

        if ($user->voted_presiden) {
            return redirect()->route('voting');
        }

        $electionType = ElectionType::where('slug', 'presiden')->first();

        Vote::create([
            'user_id'          => $user->id,
            'candidate_id'     => $request->candidate_id,
            'election_type_id' => $electionType->id,
        ]);

        \App\Models\User::where('id', $user->id)->update(['voted_presiden' => true]);

        return redirect()->route('voting')->with('success', 'Suara Presiden berhasil dicatat!');
    }

    // Halaman coblos DPR
    public function dpr()
    {
        $user = Auth::user();
        if ($user->voted_dpr) {
            return redirect()->route('voting')->with('info', 'Anda sudah memilih DPR.');
        }
        $candidates = DprCandidate::with('members')->orderBy('nomor_urut')->get();
        return view('voting.dpr', compact('candidates'));
    }

    public function storeDpr(Request $request)
{
    $request->validate([
        'vote_type'        => 'required|in:partai,calon',
        'dpr_candidate_id' => 'required|exists:dpr_candidates,id',
        'dpr_member_id'    => 'nullable|exists:dpr_members,id',
    ]);

    $user = Auth::user();

    if ($user->voted_dpr) {
        return redirect()->route('voting');
    }

    $electionType = ElectionType::where('slug', 'dpr')->first();

    if ($request->vote_type === 'calon' && $request->dpr_member_id) {
        // Coblos nama calon
        DprMemberVote::create([
            'user_id'          => $user->id,
            'dpr_candidate_id' => $request->dpr_candidate_id,
            'dpr_member_id'    => $request->dpr_member_id,
        ]);
    } else {
        // Coblos partai
        Vote::create([
            'user_id'          => $user->id,
            'dpr_candidate_id' => $request->dpr_candidate_id,
            'election_type_id' => $electionType->id,
        ]);
    }

    \App\Models\User::where('id', $user->id)->update(['voted_dpr' => true]);

    return redirect()->route('voting')->with('success', 'Suara DPR berhasil dicatat!');
}

    // Halaman coblos DPD
    public function dpd()
    {
        $user = Auth::user();
        if ($user->voted_dpd) {
            return redirect()->route('voting')->with('info', 'Anda sudah memilih DPD.');
        }
        $electionType = ElectionType::where('slug', 'dpd')->first();
        $candidates = Candidate::where('election_type_id', $electionType->id)->orderBy('nomor_urut')->get();
        return view('voting.dpd', compact('candidates'));
    }

    public function storeDpd(Request $request)
    {
        $request->validate(['candidate_id' => 'required|exists:candidates,id']);
        $user = Auth::user();

        if ($user->voted_dpd) {
            return redirect()->route('voting');
        }

        $electionType = ElectionType::where('slug', 'dpd')->first();

        Vote::create([
            'user_id'          => $user->id,
            'candidate_id'     => $request->candidate_id,
            'election_type_id' => $electionType->id,
        ]);

        \App\Models\User::where('id', $user->id)->update(['voted_dpd' => true]);

        return redirect()->route('voting')->with('success', 'Suara DPD berhasil dicatat!');
    }

    public function success()
    {
        return view('voting.success');
    }
}