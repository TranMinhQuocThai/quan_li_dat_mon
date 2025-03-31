@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('food-items.index') }}">
        Món ăn
      </a>
    </h1>
    <div>
      <a href="{{ route('food-items.create') }}"
        class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-upload fa-sm text-white-50"></i>
        Tạo
      </a>
    </div>
  </div>
  <form method="GET" action="{{ route('food-items.index') }}" class="mb-1">
    <input type="hidden" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm món ăn">

    <select name="sort_by" onchange="this.form.submit()" 
      class="form-control form-control-sm d-inline" style="width: fit-content;">
        <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>
          Ngày tạo
        </option>
        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>
          Giá
        </option>
    </select>

    <select name="sort_order" onchange="this.form.submit()" 
      class="form-control form-control-sm d-inline" style="width: fit-content;">
        <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>
          Giảm dần
        </option>
        <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>
          Tăng dần
        </option>
    </select>

    <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
</form>
  <div class="table-responsive">
    <table class="table table-bordered" id="dataTable" width="100%"
      cellspacing="0">
      <thead>
        <tr>
          <th>ID</th>
          <th>Hình</th>
          <th>Tên</th>
          <th>Giá</th>
          <th>Loại món</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($foodItems as $foodItem)
          <tr>
            <td>{{ $foodItem->id }}</td>
            <td class="text-center">
              @if ($foodItem->image)
                <img src="{{ asset('storage/' . $foodItem->image) }}" alt=""
                  style="width: 2em; aspect-ratio: 1; object-fit:contain">
              @else
                Ko hình
              @endif
            </td>
            <td>{{ $foodItem->name }}</td>
            <td>{{ number_format($foodItem->price, 0, '', ' ') }}</td>
            <td>{{ $foodItem->foodType->name }}</td>
            <td>
              <a href="{{ route('food-items.show', $foodItem) }}"
                class="btn btn-sm btn-warning">Sửa</a>
              <form
                action="{{ route('food-items.destroy', $foodItem) }}"
                method="POST"
                class="d-inline"
                >
                @method('DELETE')
                @csrf
                <button class="btn btn-sm btn-danger">Xóa</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
      {{ $foodItems->appends(request()->query())->links() }}
    </div>
  </div>
@endsection

@section('script')
  @if (session('error'))
    <script>
      Swal.fire({
        icon: 'warning',
        title: 'Lỗi',
        text: '{{ session('error') }}',
        confirmButtonColor: '#4e73df',
      })
    </script>
  @endif
@endsection
