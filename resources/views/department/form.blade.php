@extends('layouts.dash')

@section('head')
  <title>Department</title>
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
      Phòng Ban
    </h1>
  </div>
  <div class="card">
    <div class="card-body">

      <!-- Form chỉnh sửa -->
      @if ($mode == 'create')
        <form action="{{ route('department.store') }}" method="POST">
      @elseif ($mode == 'update')
        <form action="{{ route('department.update', $department->id) }}"
          method="POST">
        @method('PUT')
      @else
        <form action="{{ route('department.destroy', $department->id) }}"
          method="POST">
        @method('DELETE')
      @endif
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">
          Tên Phòng Ban
        </label>
        <input type="text" name="name" id="name"
          class="form-control @error('name') is-invalid @enderror"
          @isset($department)
            value="{{ old('name', $department->name) }}"
          @endisset
          value="{{ old('name') }}">
        @error('name')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Nút hành động -->
      <button type="submit" class="btn btn-primary">Ok</button>
      <a href="{{ route('department.index') }}" class="btn btn-secondary">
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
