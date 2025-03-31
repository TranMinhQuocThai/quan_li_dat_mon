@extends('layouts.dash')

@section('head')
@endsection
@section('head')
  <title>Customer</title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      @if ($mode == 'create')
        Thêm
      @elseif ($mode == 'update')
        Sửa
      @else
        Xóa
      @endif
      bàn
    </h1>
  </div>
  <div class="card">
    <div class="card-body">

      <!-- Form chỉnh sửa -->
      @if ($mode == 'create')
        <form action="{{ route('tables.store') }}" method="POST">
      @elseif ($mode == 'update')
        <form action="{{ route('tables.update', $table->id) }}"
          method="POST">
        @method('PUT')
      @else
        <form action="{{ route('tables.destroy', $table->id) }}"
          method="POST">
        @method('DELETE')
      @endif
      @csrf

      <div class="mb-3">
        <label for="name" class="form-label">
          Tên bàn
        </label>
        <input type="text" name="name" id="name"
          class="form-control @error('name') is-invalid @enderror"
          @isset($table)
            value="{{ old('name', $table->name) }}"
          @endisset
          value="{{ old('name') }}"
          {{ $mode != 'delete' ?: 'readonly' }}>
        @error('name')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="seats" class="form-label">
          Số chỗ
        </label>
        <input type="number" name="seats" id="seats"
          class="form-control @error('seats') is-invalid @enderror"
          @isset($table)
            value="{{ old('seats', $table->seats) }}"
          @endisset
          value="{{ old('seats') }}"
          {{ $mode != 'delete' ?: 'readonly' }}>
        @error('seats')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Nút hành động -->
      <button type="submit" class="btn btn-primary">Ok</button>
      <a href="{{ route('tables.index') }}" class="btn btn-secondary">
        Quay lại
      </a>
      </form>
    </div>
  </div>
@endsection

@section('script')
<script>
  @foreach ($errors->all() as $error)
    console.warn(`{{ $error }}`)
  @endforeach
</script>
@endsection
