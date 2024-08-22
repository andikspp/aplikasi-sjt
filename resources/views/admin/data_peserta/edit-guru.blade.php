@extends('layout.admin.admin-layout')

@section('title', 'Edit Guru')

@section('content')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Edit Data Guru</h2>

        <form action="{{ route('guru.update', $guru->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $guru->name }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $guru->email }}" required>
            </div>

            <div class="form-group">
                <label for="telepon">Telepon</label>
                <input type="text" class="form-control" id="telepon" name="telepon" value="{{ $guru->telepon }}" required>
            </div>

            <div class="form-group">
                <label for="instansi">Instansi</label>
                <input type="text" class="form-control" id="instansi" name="instansi" value="{{ $guru->instansi }}" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <input type="text" class="form-control" id="role" name="role" value="{{ $guru->role }}" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" class="form-control" id="status" name="status" value="{{ $guru->status }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('guru.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection