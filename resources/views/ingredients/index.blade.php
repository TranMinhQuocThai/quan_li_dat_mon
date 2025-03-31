@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('ingredients.index') }}">
        Nguyên liệu
      </a>
    </h1>
    <div>
      <a href="{{ route('ingredients.create') }}"
        class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-upload fa-sm text-white-50"></i>
        Tạo
      </a>
    </div>
  </div>
  <form method="GET" action="" class="mb-1">
    <input type="hidden" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm món ăn">

    <select name="sort_by" onchange="this.form.submit()" 
      class="form-control form-control-sm d-inline" style="width: fit-content;">
        <option value="updated_at" {{ request('sort_by', 'updated_at') == 'updated_at' ? 'selected' : '' }}>
          Ngày nhập
        </option>
        <option value="quantity" {{ request('sort_by') == 'quantity' ? 'selected' : '' }}>
          Số lượng
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
  <div class="card shadow mb-3">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%"
          cellspacing="0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Tên Nguyên Liệu</th>
              <th>Số Lượng</th>
              <th>Đơn Vị</th>
              <th>Ngày Nhập</th>
              <th>Thao Tác</th>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <th>ID</th>
              <th>Tên Nguyên Liệu</th>
              <th>Số Lượng</th>
              <th>Đơn Vị</th>
              <th>Ngày Nhập</th>
              <th>Thao Tác</th>
            </tr>
          </tfoot>
          <tbody id="ingredient-list">
            @foreach ($ingredients as $ingredient)
              <tr>
                <td>{{ $ingredient->id }}</td>
                <td>{{ $ingredient->name }}</td>
                <td>{{ $ingredient->quantity }}</td>
                <td>{{ $ingredient->unit }}</td>
                <td>{{ $ingredient->updated_at }}</td>
                <td>
                  <a href="{{ route('ingredients.edit', $ingredient->id) }}"
                    class="btn btn-sm btn-warning">Sửa</a>
                  <form
                    action="{{ route('ingredients.destroy', $ingredient->id) }}"
                    method="POST" class="d-inline"
                    >
                    @csrf
                    @method('DELETE')
                    <button onclick="confirmSweet(this)"
                      class="btn btn-danger btn-sm"
                      >Xóa</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
          {{ $ingredients->appends(request()->query())->links() }}
        </div>
      </div>
    </div>

    <!-- Pusher để cập nhật real-time -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
      Pusher.logToConsole = true; // Debug

      var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        forceTLS: true
      });

      var channel = pusher.subscribe("ingredients");

      channel.bind("IngredientCreated", function(data) {
        console.log("Nhận sự kiện IngredientCreated:", data);

        // Lấy thẻ tbody chứa danh sách nguyên liệu
        var ingredientList = document.getElementById("ingredient-list");

        // Tạo một dòng mới
        var newRow = document.createElement("tr");
        newRow.innerHTML = `
                <td>${data.id}</td>
                <td>${data.name}</td>
                <td>${data.quantity}</td>
                <td>${data.unit}</td>
                <td>${data.created_at}</td>
                <td>
                    <a href="/ingredients/${data.id}/edit" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="/ingredients/${data.id}" method="POST" class="d-inline">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button onclick="confirmSweet(this)" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            `;

        // Thêm vào bảng
        ingredientList.appendChild(newRow);
      });
    </script>
  </div>
@endsection

@section('script')
  @if (session('success'))
    <script>
      Swal.fire({
        icon: 'success',
        title: 'Thành công',
        text: '{{ session('success') }}',
        confirmButtonColor: '#4e73df',
      })
    </script>
  @endif
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
  <script>
    function confirmSweet(elem) {
      Swal.fire({
        title: "Xác nhận?",
        text: "Thực hiện hành động này",
        icon: "warning",
        reverseButtons: true,
        showCancelButton: true,
        cancelButtonText: 'Hủy',
        confirmButtonColor: "#4e73df",
        cancelButtonColor: "#d33",
        confirmButtonText: "Ok",
      }).then((result) => {
        if (result.isConfirmed) {
          elem.checked = !elem.checked;
          elem.form.submit();
        }
      });
      return false;
    }
    @foreach ($errors->all() as $error)
      console.warn(`{{ $error }}`)
    @endforeach
  </script>
@endsection
