@extends('layouts.master')

@section('content')
<div class="container py-4">

    <!-- Filter tanggal -->
    <form method="GET" class="mb-3">
        <div class="d-flex justify-content-end gap-2">
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" style="max-width:200px;">
            <button class="btn btn-primary fa fa-find"><i class="bi bi-search"></i></button>
            <a href="{{ route('pembina.laporan.index', $ekskul) }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    @forelse($laporan->groupBy('tanggal') as $tgl => $records)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                Date: {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
            </div>
            <div class="card-body p-0">
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
