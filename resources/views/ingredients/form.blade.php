@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('ingredients.index') }}">
        @if ($mode == 'create')
          Thêm
        @elseif ($mode == 'update')
          Sửa
        @else
          Xóa
        @endif
        nguyên liệu
      </a>
    </h1>
  </div>

  <div class="card mb-3">
    <div class="card-body">
      @php
        $form;
        switch ($mode) {
          case 'update':
            $form = [
              'action' => route('ingredients.update', $ingredient),
              'method' => 'PATCH',
            ];
            break;
          default:
            $form = [
              'action' => route('ingredients.store'),
              'method' => 'POST',
            ];
            break;
        }
      @endphp
      <!-- Form chỉnh sửa -->
      <form action="{{ $form['action'] }}" method="POST">
        @method($form['method'])
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Tên</label>
          <input
            type="text" name="name" id="name"
            value="{{ old('name', isset($ingredient) ? $ingredient->name : '') }}"
            placeholder="Nhập tên"
            class="form-control @error('name') is-invalid @enderror"
          >
          @error('name')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="quantity" class="form-label">Số lượng</label>
          <input
            type="number" name="quantity" id="quantity"
            value="{{ old('quantity', isset($ingredient) ? $ingredient->quantity : '') }}"
            step="0.001"
            placeholder="Nhập tên"
            class="form-control @error('quantity') is-invalid @enderror"
          >
          @error('quantity')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
          @enderror
        </div>
  
        <div class="mb-3">
          <label for="unit" class="form-label">
            Đơn vị
          </label>
          <input type="text" name="unit" id="unit"
            value="{{ old('unit', isset($ingredient) ? $ingredient->unit : '') }}"
            placeholder="Nhập tên"
            class="form-control @error('unit') is-invalid @enderror"
            >
          @error('unit')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>
  
        <!-- Nút hành động -->
        <button type="submit" class="btn btn-primary">Ok</button>
      </form>
    </div>
  </div>

@endsection

@section('script')
  <script>
    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Lỗi',
        text: '{{ session('error') }}',
        confirmButtonColor: '#4e73df',
      })
    @endif
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Thành công',
        text: '{{ session('success') }}',
        confirmButtonColor: '#4e73df',
      })
    @endif
    @foreach ($errors->all() as $error)
      console.warn(`{{ $error }}`)
    @endforeach
  </script>
@endsection
