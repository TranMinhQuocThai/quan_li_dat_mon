@extends('layout.app')
@section('title')
    Danh sách nhân viên
@endsection

@section('content')
    <div class="card shadow mb-4">
    <div>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
        <a href="{{route("invoice.create")}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i>Thêm đơn hàng</a>
    </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày thanh toán</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày thanh toán</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->order_id }}</td>
                                <td>{{ $invoice->invoice_date }}</td>
                                <td>{{ $invoice->total_amount }}</td>
                                <td>{{ $invoice->status }}</td>
                                <td>
                                    <a href="{{route("invoice.edit", $invoice->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
