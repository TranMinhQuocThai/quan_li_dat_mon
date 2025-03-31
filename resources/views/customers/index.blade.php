@extends('layouts.dash')

@section('head')
  <title>Khách hàng</title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      Danh sách khách hàng
    </h1>
    <div>
      <a href="{{ route('customers.create') }}"
        class="btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-download fa-sm text-white-50"></i>
        Tạo
      </a>
    </div>
  </div>
  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%"
          cellspacing="0">
          <thead>
            <tr>
              <th>STT</th>
              <th>Tên</th>
              <th>Email</th>
              <th>SDT</th>
              <th>Tổng đơn</th>
              {{-- <th>Chức vụ</th> --}}
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($customers as $idx => $customer)
              <tr>
                <td>{{ $idx + 1 }}</td>
                <td>{{ $customer['name'] }}</td>
                <td>{{ $customer['email'] }}</td>
                <td>{{ $customer['phone'] }}</td>
                {{-- <td>{{ $customer->role == 'staff' ? 'Bồi bàn' : 'Đầu bếp' }}</td> --}}
                <td>
                  {{ number_format($customer['total_spent']) }}
                </td>
                <td>
                  <a href="{{ route('customers.edit', $customer['id']) }}"
                    class="btn btn-sm btn-warning">Sửa</a>
                  <form action="{{ route('customers.destroy', $customer['id']) }}"
                    method="POST" class="d-inline">
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
          {{ $customers->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  @include('components.scripts.alert-script')
@endsection
