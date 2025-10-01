@extends('layouts.master')

@section('content')
<div class="container">

    <a href="{{ route('kelola-pengumuman.create') }}" class="btn btn-primary mb-4">CREATE NEW</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($pengumumans->isEmpty())
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h5>Belum ada data pengumuman</h5>
                <p>Klik tombol "CREATE NEW" untuk menambahkan pengumuman pertama.</p>
            </div>
        </div>
    @else
        <div class="row">
            @foreach($pengumumans as $p)
            {{-- Kartu Pengumuman --}}
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 d-flex flex-column">
                    <div class="card-body pb-0"> {{-- Tambahkan pb-0 untuk menghilangkan padding bawah card-body --}}
                        <h5 class="card-title fw-bold">{{ $p->judul }}</h5>
                        {{-- Batasi isi dan berikan contoh teks --}}
                        <p class="card-text text-secondary" style="min-height: 70px;">{{ Str::limit($p->isi, 100, '...') }}</p>
                    </div>

                    {{-- Bagian Tombol Utama READ MORE --}}
                    {{-- Ganti rute sesuai kebutuhan Anda untuk melihat detail pengumuman --}}
                    <a href="{{ route('pengumuman.show', $p->id) }}" class="btn btn-primary rounded-0 rounded-top-0 d-block py-2 fw-semibold">READ MORE</a>

                    {{-- Baris Tombol Aksi di bagian bawah --}}
                    <div class="d-flex w-100">
                        {{-- Tombol Hapus (Merah) --}}
                        <button type="button" class="btn btn-danger flex-fill rounded-0" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $p->id }}" title="Hapus">
                            <i class="bi bi-trash"></i> {{-- Icon Tong Sampah (pastikan Anda menggunakan Bootstrap Icons) --}}
                        </button>
                        
                        {{-- Tombol Edit (Kuning) --}}
                        <a href="{{ route('kelola-pengumuman.edit', $p->id) }}" class="btn btn-warning flex-fill rounded-0" title="Edit">
                            <i class="bi bi-pencil-square"></i> {{-- Icon Pensil/Edit (pastikan Anda menggunakan Bootstrap Icons) --}}
                        </a>
                    </div>
                </div>
            </div>

            {{-- Modal Hapus (tidak berubah) --}}
            <div class="modal fade" id="deleteModal{{ $p->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $p->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-body text-center p-5">
                            <div class="mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 80px; height: 80px;">
                                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <h4 class="modal-title fw-bold mb-3" id="deleteModalLabel{{ $p->id }}">Are you sure to delete this?</h4>
                            <p class="text-muted mb-4">This action cannot be undone. The pengumuman "<strong>{{ $p->judul }}</strong>" will be permanently deleted.</p>
                            <div class="d-flex gap-3 justify-content-center">
                                <form action="{{ route('kelola-pengumuman.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger px-4 py-2 fw-semibold">
                                        <i class="bi bi-trash me-2"></i>Delete
                                    </button>
                                </form>
                                <button type="button" class="btn btn-secondary px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                                    <i class="bi bi-arrow-left me-2"></i>Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection