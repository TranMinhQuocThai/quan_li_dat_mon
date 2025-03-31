@extends('layouts.dash')

@section('head')
  <title>Table</title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      Danh sách bàn
    </h1>
    <div>
      <a href="{{ route('tables.create') }}"
        class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i>
        Tạo
      </a>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="card shadow mb-4">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%"
              cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tên</th>
                  <th>Số chỗ</th>
                  <th>Trạng thái</th>
                  <th>Thao tác</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($tables as $table)
                  <tr>
                    <td>{{ $table->id }}</td>
                    <td>{{ $table->name }}</td>
                    <td>{{ $table->seats }}</td>
                    <td>{{ $table->status }}</td>
                    <td>
                      <a href="{{ route('tables.edit', $table->id) }}"
                        class="btn btn-sm btn-warning">Sửa</a>
                        <form
                          action="{{ route('tables.destroy', $table->id) }}"
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
            {{ $tables->appends(request()->query())->links() }}
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @if (session('err'))
    <script>
      Swal.fire({
        icon: 'warning',
        title: 'Lỗi',
        text: '{{ session('err') }}',
        confirmButtonColor: '#4e73df',
      })
    </script>
  @endif
@endsection
