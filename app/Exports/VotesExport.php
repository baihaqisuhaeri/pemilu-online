<?php

namespace App\Exports;

use App\Models\Candidate;
use App\Models\DprCandidate;
use App\Models\DprMember;
use App\Models\ElectionType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VotesExport implements FromCollection, WithHeadings, WithStyles, WithTitle
{
    protected string $type;

    public function __construct(string $type = 'presiden')
    {
        $this->type = $type;
    }

    public function title(): string
    {
        return strtoupper($this->type);
    }

    public function collection()
{
    if ($this->type === 'dpr') {
        $candidates = DprCandidate::with(['members' => function($q) {
            $q->withCount('votes');
        }])->withCount('votes')->orderBy('nomor_urut')->get();

        $rows = collect();

        foreach ($candidates as $partai) {
            // Baris header partai
            $rows->push([
                'No. Urut'     => $partai->nomor_urut,
                'Nama'         => '[ PARTAI ] ' . $partai->nama_partai,
                'Jenis'        => 'Partai',
                'Jumlah Suara' => $partai->votes_count,
                'Persentase'   => '',
            ]);

            // Baris per calon
            foreach ($partai->members as $member) {
                $rows->push([
                    'No. Urut'     => $partai->nomor_urut . '.' . $member->nomor_urut,
                    'Nama'         => '      ' . $member->nama,
                    'Jenis'        => 'Calon',
                    'Jumlah Suara' => $member->votes_count,
                    'Persentase'   => '',
                ]);
            }

            // Baris total per partai
            $totalPartai = $partai->votes_count + $partai->members->sum('votes_count');
            $rows->push([
                'No. Urut'     => '',
                'Nama'         => 'Total Suara ' . $partai->nama_partai,
                'Jenis'        => '',
                'Jumlah Suara' => $totalPartai,
                'Persentase'   => '',
            ]);

            // Baris kosong pemisah
            $rows->push([
                'No. Urut' => '', 'Nama' => '', 'Jenis' => '', 'Jumlah Suara' => '', 'Persentase' => ''
            ]);
        }

        // Baris grand total
        $grandTotal = $candidates->sum('votes_count') + $candidates->sum(fn($p) => $p->members->sum('votes_count'));
        $rows->push([
            'No. Urut'     => '',
            'Nama'         => 'GRAND TOTAL',
            'Jenis'        => '',
            'Jumlah Suara' => $grandTotal,
            'Persentase'   => '',
        ]);

        return $rows;
    }

    // Presiden & DPD
    $electionType = ElectionType::where('slug', $this->type)->first();
    $candidates = Candidate::where('election_type_id', $electionType->id)->withCount('votes')->orderBy('nomor_urut')->get();
    $total = $candidates->sum('votes_count');

    $rows = $candidates->map(fn($c) => [
        'No. Urut'     => $c->nomor_urut,
        'Nama'         => $c->name . ($c->wakil_name ? ' & ' . $c->wakil_name : ''),
        'Jenis'        => '',
        'Jumlah Suara' => $c->votes_count,
        'Persentase'   => $total > 0 ? number_format(($c->votes_count / $total) * 100, 1) . '%' : '0%',
    ]);

    $rows->push([
        'No. Urut' => '', 'Nama' => 'TOTAL', 'Jenis' => '', 'Jumlah Suara' => $total, 'Persentase' => '100%'
    ]);

    return $rows;
}

public function headings(): array
{
    if ($this->type === 'dpr') {
        return ['No. Urut', 'Nama', 'Jenis', 'Jumlah Suara', 'Persentase'];
    }
    return ['No. Urut', 'Nama Calon', 'Jenis', 'Jumlah Suara', 'Persentase'];
}

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}