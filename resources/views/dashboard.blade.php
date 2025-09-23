@extends('layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <img src="https://picsum.photos/400/200?random=1" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Ekskul Basket</h5>
                    <p class="card-text">Informasi tentang ekskul basket dan jadwal kegiatan.</p>
                    <a href="#" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <img src="https://picsum.photos/400/200?random=2" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Ekskul Musik</h5>
                    <p class="card-text">Ekskul musik untuk mengasah bakat seni siswa.</p>
                    <a href="#" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <img src="https://picsum.photos/400/200?random=3" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Ekskul Pramuka</h5>
                    <p class="card-text">Kegiatan pramuka untuk membentuk kedisiplinan siswa.</p>
                    <a href="#" class="btn btn-primary">Lihat Detail</a>
                </div>
            </div>
        </div>
    </div>
@endsection
