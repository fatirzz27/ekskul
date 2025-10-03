@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="text-dark">Laporan Absensi - {{ $ekskul->nama_ekskul }}</h2>
            <p class="text-muted">Kelola dan export laporan absensi ekstrakurikuler</p>
            @if(isset($pagination) && !$tanggal)
                <small class="text-info">
                    <i class="bi bi-info-circle"></i> 
                    Menampilkan {{ $pagination['per_page'] }} laporan per halaman
                </small>
            @endif
        </div>
    </div>

    <!-- Error Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif



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
            <div class="d-flex align-items-center bg-primary text-white p-3 rounded-top">
                <div class="flex-grow-1 d-flex align-items-center">
                    <button class="btn btn-link text-white p-0 me-3 border-0 accordion-toggle" 
                            type="button" 
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse-{{ $loop->index }}" 
                            aria-expanded="false"
                            aria-controls="collapse-{{ $loop->index }}">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    <span class="fw-bold">{{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}</span>
                    <span class="badge bg-warning text-dark ms-3">{{ $records->count() }} data</span>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <!-- Tombol Export Excel - hanya untuk tanggal ini -->
                    <a href="{{ route('pembina.laporan.export', ['ekskul' => $ekskul->id, 'tanggal' => $tgl, 'format' => 'excel']) }}" 
                       class="btn btn-success btn-sm text-white fw-bold"
                       title="Export data absensi tanggal {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}"
                       onclick="event.stopPropagation();">
                        EXPORT EXCEL
                    </a>
                </div>
            </div>
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

    <!-- Custom Pagination -->
    @if(isset($pagination) && $pagination['last_page'] > 1)
    <div class="mt-4">
        <nav aria-label="Navigasi halaman laporan">
            <ul class="pagination justify-content-center">
                <!-- Previous Page Link -->
                @if($pagination['current_page'] > 1)
                    <li class="page-item">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] - 1]) }}" aria-label="Sebelumnya">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" aria-label="Sebelumnya">
                            <span aria-hidden="true">&laquo;</span>
                        </span>
                    </li>
                @endif

                <!-- Page Numbers -->
                @for($i = 1; $i <= $pagination['last_page']; $i++)
                    @if($i == $pagination['current_page'])
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $i]) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                <!-- Next Page Link -->
                @if($pagination['current_page'] < $pagination['last_page'])
                    <li class="page-item">
                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['page' => $pagination['current_page'] + 1]) }}" aria-label="Berikutnya">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <span class="page-link" aria-label="Berikutnya">
                            <span aria-hidden="true">&raquo;</span>
                        </span>
                    </li>
                @endif
            </ul>
        </nav>
        
        <!-- Pagination Info -->
        <div class="text-center text-muted mt-2">
            <small>
                Menampilkan {{ $pagination['from'] ?? 0 }} sampai {{ $pagination['to'] ?? 0 }} 
                dari {{ $pagination['total'] ?? 0 }} laporan tanggal
            </small>
        </div>
    </div>
    @endif
</div>

<style>
    /* Custom accordion styling */
    .accordion-toggle {
        background: none !important;
        border: none !important;
        color: white !important;
        font-size: 1.2rem;
        transition: transform 0.2s ease;
    }
    
    .accordion-toggle:hover,
    .accordion-toggle:focus {
        color: white !important;
        box-shadow: none !important;
    }
    
    .accordion-toggle[aria-expanded="true"] i {
        transform: rotate(180deg);
    }
    
    /* Style untuk tombol export */
    .btn-success {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        font-weight: bold;
        letter-spacing: 0.5px;
        padding: 0.25rem 0.75rem !important;
        font-size: 0.8rem !important;
        text-decoration: none !important;
    }
    
    .btn-success:hover {
        background-color: #218838 !important;
        border-color: #1e7e34 !important;
        box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
        text-decoration: none !important;
    }
    
    /* Badge styling */
    .badge.bg-warning.text-dark {
        background-color: #ffc107 !important;
        color: #000 !important;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
    }
    
    /* Accordion header custom styling */
    .accordion-item {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    .bg-primary {
        background-color: #0d6efd !important;
    }
    
    /* Smooth transition for accordion content */
    .accordion-collapse {
        transition: height 0.35s ease;
    }
    
    /* Custom Pagination Styling */
    .pagination {
        margin-bottom: 0;
    }
    
    .pagination .page-link {
        color: #0d6efd;
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        margin: 0 0.125rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }
    
    .pagination .page-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
        color: #0a58ca;
        transform: translateY(-1px);
    }
    
    .pagination .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
        font-weight: bold;
    }
    
    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    /* Pagination responsive */
    @media (max-width: 576px) {
        .pagination .page-link {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>

@endsection