@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h3>Edit Ekskul untuk {{ $user->name }}</h3>

    <form action="{{ route('user.updateEkskul', $user->id) }}" method="POST">
        @csrf
        {{-- karena route pakai POST (lihat web.php) --}}
        
        <div class="mb-3">
            <label class="form-label">Pilih Ekskul</label><br>
            @foreach($ekskuls as $ekskul)
                <div class="form-check">
                    <label class="form-check-label">
                         {{ $ekskul->nama_ekskul }}
                    </label>

                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        name="ekskul_id[]" 
                        value="{{ $ekskul->id }}"
                        {{ $user->ekskul->contains($ekskul->id) ? 'checked' : '' }}
                    >
                    <label class="form-check-label">
                        {{ $ekskul->nama }}
                    </label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('kelola-user') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
