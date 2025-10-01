@extends('layouts.master')

@section('content')
<div class="container py-4">
  <h3 class="mb-4 fw-bold">Absensi Ekskul: {{ $ekskul->nama_ekskul }}</h3>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form action="{{ route('pembina.absensi.store', $ekskul) }}" method="POST">
    @csrf
    <div class="mb-3">
      <label for="tanggal" class="form-label fw-bold">Tanggal</label>
      <input type="date" name="tanggal" id="tanggal" class="form-control" required>
    </div>

    <div class="table-responsive">
      <table class="table table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>Anggota</th>
            <th>Hadir</th>
            <th>Izin</th>
            <th>Alfa</th>

          </tr>
        </thead>
        <tbody>
          @forelse($anggota as $user)
            <tr>
              <td class="text-start">
                <div class="d-flex align-items-center">
                  @if(!empty($user->profile->foto))
                    <img src="{{ asset('images/profile/' . $user->profile->foto) }}"
                         alt="Foto {{ $user->name }}"
                         class="rounded-circle me-2"
                         style="width: 35px; height: 35px; object-fit: cover;">
                  @else
                    <span class="me-2" style="font-size: 1.5rem;">👤</span>
                  @endif
                  <span>{{ $user->name }}</span>
                </div>
              </td>
              <td>
                <input type="checkbox" name="absensi[{{ $user->id }}]" 
                       value="hadir" class="form-check-input status-checkbox"
                       data-user="{{ $user->id }}">
              </td>
              <td>
                <input type="text" name="keterangan[{{ $user->id }}]" 
                       class="form-control form-control-sm"
                       placeholder="Alasan Izin">
              </td>
              <td>
                <input type="checkbox" name="absensi[{{ $user->id }}]" 
                       value="alfa" class="form-check-input status-checkbox"
                       data-user="{{ $user->id }}">
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Belum ada anggota.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <button type="submit" class="btn btn-success mt-3">Simpan Absensi</button>
  </form>
</div>

{{-- Script: hanya boleh pilih 1 checkbox per anggota --}}

<style>
  /* style tampilan abu-abu (JANGAN pakai pointer-events:none; supaya tetap bisa diklik) */
  .disabled-option {
    opacity: 0.45;
  }
  .status-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
  }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // ambil semua user id dari checkbox
  const userIds = new Set([...document.querySelectorAll('.status-checkbox')].map(cb => cb.dataset.user));

  userIds.forEach(userId => {
    const hadir = document.querySelector(`.status-checkbox[data-user="${userId}"][value="hadir"]`);
    const alfa  = document.querySelector(`.status-checkbox[data-user="${userId}"][value="alfa"]`);
    const izin  = document.querySelector(`input[name="keterangan[${userId}]"]`);

    if (!hadir || !alfa || !izin) return;

    // fungsi untuk update tampilan sesuai state
    function updateVisuals() {
      const izinHasText = izin.value.trim() !== '';

      // bersihkan kelas dulu
      hadir.classList.remove('disabled-option');
      alfa.classList.remove('disabled-option');
      izin.classList.remove('disabled-option');

      if (hadir.checked) {
        // hadir aktif -> alfa dan izin "terlihat" non-active (abu)
        alfa.classList.add('disabled-option');
        izin.classList.add('disabled-option');
        // kosongkan izin (opsional)
        // izin.value = ''; // jangan auto-clear kalau mau tetap lihat teks sebelumnya
      } else if (alfa.checked) {
        // alfa aktif -> hadir dan izin abu
        hadir.classList.add('disabled-option');
        izin.classList.add('disabled-option');
      } else if (izinHasText) {
        // izin ada teks -> kedua checkbox abu dan ter-uncheck
        hadir.classList.add('disabled-option');
        alfa.classList.add('disabled-option');
        hadir.checked = false;
        alfa.checked = false;
      } else {
        // default: semua tampak normal
      }
    }

    // klik pada checkbox (gunakan click agar switching instan)
    [hadir, alfa].forEach(cb => {
      cb.addEventListener('click', function (e) {
        // lakukan switch langsung: uncheck semua, lalu set yang diklik
        hadir.checked = false;
        alfa.checked = false;
        this.checked = true;

        // jika klik -> kosongkan input izin agar tidak bentrok
        izin.value = '';
        updateVisuals();
      });
    });

    // fokus di kolom izin: langsung uncheck checkbox supaya terlihat
    izin.addEventListener('focus', function () {
      hadir.checked = false;
      alfa.checked = false;
      updateVisuals();
    });

    // saat mengetik di izin: uncheck checkbox dan beri style abu
    izin.addEventListener('input', function () {
      updateVisuals();
    });

    // inisialisasi tampilan saat load
    updateVisuals();
  });
});
</script>

@endsection
