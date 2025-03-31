@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('statistics.index') }}">
        Thống kê
      </a>
    </h1>
  </div>

  @php
    $types = ['tuần', 'tháng', 'năm'];
  @endphp
  <form method="GET" class="mb-4">
    {{-- <label>Select Date:</label> --}}
    <input type="date" name="date" onchange="this.form.submit()"
      value="{{ request('date') ?? now()->toDateString() }}"
      class="form-control form-control-sm d-inline" style="width: fit-content"
      >
    <select name="type" onchange="this.form.submit()"
      class="form-control form-control-sm d-inline mb-1" style="width: fit-content;">
        @foreach($types as $type)
            <option value="{{ urlencode($type) }}"
              @if (urldecode(request('type')) == $type)
                selected
              @endif
              >
                {{ Str::ucfirst($type) }}
            </option>
        @endforeach
    </select>
    <select name="foodTypeId" onchange="this.form.submit()"
      class="form-control form-control-sm d-inline mb-1" style="width: fit-content;">
      <option value="">Tất cả món</option>
        @foreach($foodTypes as $foodType)
            <option value="{{ $foodType->id}}"
              @if (urldecode(request('foodTypeId')) == $foodType->id)
                selected
              @endif
              >
                {{ $foodType->name }}
            </option>
        @endforeach
    </select>
    {{-- <button type="submit" class="btn btn-primary btn-sm">Ok</button> --}}
  </form>

  <canvas id="weeklyChart"></canvas>

  <table class="table">
    <thead>
        <tr>
            <th>Hình</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Cửa hàng</th>
            <th>Online</th>
            <th>Tổng SL</th>
            <th>Tổng tiền</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($topItems as $item)
            <tr>
                <td>
                  <img src="{{ asset('storage/' . $item->image) }}" alt=""
                    style="width: 2em; aspect-ratio: 1; object-fit:contain">
                </td>
                <td>{{ $item->name }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                <td>{{ $item->sold_in_store }}</td>
                <td>{{ $item->sold_online }}</td>
                <td>{{ $item->total_sold }}</td>
                <td>{{ number_format($item->total_revenue, 0, ',', '.') }}₫</td>
            </tr>
        @endforeach
    </tbody>
</table>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var ctx = document.getElementById('weeklyChart').getContext('2d');
      ctx.canvas.height = 120; // Set height before creating the chart
      new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [
                {
                  label: 'Doanh thu tại cửa hàng',
                  data: @json($inStoreRevenueData),
                  backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue
                  borderWidth: 1
                },
                {
                  label: 'Doanh thu online',
                  data: @json($onlineRevenueData),
                  backgroundColor: 'rgba(255, 99, 132, 0.6)', // Red
                  borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
              xAxes: [{
                stacked: true
              }],
              yAxes: [{
                stacked: true,
                ticks: {
                  beginAtZero: true,
                  callback: function(value, index, values) {
                    return value.toLocaleString('vi-VN') + ' đ';
                  }
                },
              }]
            },
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                  var datasetLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                  var value = tooltipItem.yLabel.toLocaleString('vi-VN') + ' đ';
                  return datasetLabel + ': ' + value;
                }
              }
            }
        }
      });
    });
  </script>
@endsection

@section('script')
  @include('components.scripts.alert-script')
  <script>
    function confirmSweet(elem) {
      elem.setAttribute('new', elem.value)
      elem.value = elem.getAttribute('old')
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
          elem.value = elem.getAttribute('new');
          elem.form.submit();
        }
      });
      return false;
    }
  </script>
@endsection
