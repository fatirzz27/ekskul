@extends('layouts.master')

@section('content')
<div class="container py-4">



    <!-- Filter tanggal -->
    <form method="GET" class="mb-3">
        <div class="d-flex justify-content-between align-items-center gap-2">
            <div class="mb ">
                <a href="{{ route('ekskul.show', $ekskul->id) }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="d-flex gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" style="max-width:200px;">
            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
            <a href="{{ route('pembina.laporan.index', $ekskul) }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    @forelse($laporan->groupBy('tanggal') as $tgl => $records)
    <div class="accordion mb-3" id="accordion-{{ $loop->index }}">
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                <button class="accordion-button bg-primary text-white collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapse-{{ $loop->index }}" aria-expanded="false"
                    aria-controls="collapse-{{ $loop->index }}">
                    {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
                    <span class="badge bg-warning text-light ms-3">{{ $records->count() }} data</span>
                </button>
            </h2>
            <div id="collapse-{{ $loop->index }}" class="accordion-collapse collapse"
                aria-labelledby="heading-{{ $loop->index }}">
                <div class="accordion-body p-0">
                    <table class="table table-bordered m-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>Nama</th>
                                <th class="text-center">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $absen)
                            <tr>
                                <td>{{ $absen->user->name }}</td>
                                <td class="text-center">
                                    @if($absen->status == 'hadir')
                                    <span class="text-success fw-bold">Hadir</span>
                                    @elseif($absen->status == 'izin')
                                    <span class="text-warning fw-bold">Izin</span>
                                    @elseif($absen->status == 'alfa')
                                    <span class="text-danger fw-bold">Alfa</span>
                                    @endif

                                    @if($absen->keterangan)
                                    <div class="small text-muted">{{ $absen->keterangan }}</div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @empty
    {{-- kalau kosong tampilkan card info --}}
    <div class="card shadow-sm border-0 text-center p-5">
        <div class="card-body">
            <i class="bi bi-clipboard-x text-danger" style="font-size: 3rem;"></i>
            <h5 class="mt-3 text-muted">Belum ada data absensi</h5>
            <p class="text-secondary">Silakan lakukan absensi terlebih dahulu agar laporan muncul di sini.</p>
        </div>
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="mt-3">
        {{ $laporan->links() }}
    </div>
</div>
@endsection