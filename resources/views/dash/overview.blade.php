@extends('layouts.dash')

@section('head')
  <title>Tổng quan</title>
@endsection

@section('content')
  <!-- Page Heading -->
  <h1 class="h3 mb-0 text-gray-800">Tổng Quan</h1>


  <div class="card shadow mb-4">
    <div class="card-body">
      <div class="container-fluid1">
        <div class="row">
          <div class="col-md-3">
              <div class="card bg-primary text-white shadow">
                  <div class="card-body d-flex align-items-center">
                      <i class="fas fa-users fa-3x mr-3"></i>
                      <div>
                          <h5 class="card-title">Người Dùng</h5>
                          <p class="card-text">{{ $userCount }}</p>
                      </div>
                  </div>
              </div>
          </div>
  
          <div class="col-md-3">
              <div class="card bg-success text-white shadow">
                  <div class="card-body d-flex align-items-center">
                      <i class="fas fa-chair fa-3x mr-3"></i>
                      <div>
                          <h5 class="card-title">Bàn Ăn</h5>
                          <p class="card-text">{{ $tableCount }}</p>
                      </div>
                  </div>
              </div>
          </div>
  
          <div class="col-md-3">
              <div class="card bg-warning text-white shadow">
                  <div class="card-body d-flex align-items-center">
                      <i class="fas fa-utensils fa-3x mr-3"></i>
                      <div>
                          <h5 class="card-title">Loại Món Ăn</h5>
                          <p class="card-text">{{ $foodTypeCount }}</p>
                      </div>
                  </div>
              </div>
          </div>
  
          <div class="col-md-3">
              <div class="card bg-danger text-white shadow">
                  <div class="card-body d-flex align-items-center">
                      <i class="fas fa-hamburger fa-3x mr-3"></i>
                      <div>
                          <h5 class="card-title">Món Ăn</h5>
                          <p class="card-text">{{ $foodItemCount }}</p>
                      </div>
                  </div>
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
