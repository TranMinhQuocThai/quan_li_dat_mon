<nav class="navbar navbar-expand-lg px-2 rounded-bottom-5 
            {{ request()->is('/') || request()->is('menu') ? 'navbar-dark' : 'navbar-light' }}" 
    style="background-color: {{ request()->is('/') || request()->is('menu') ? 'rgba(0, 0, 0, 0.5)' : 'white' }};
           {{ request()->is('/') || request()->is('menu') ? 'position: fixed;' : 'position: relative;' }} 
           width: 100%; z-index: 1000; transition: background-color 0.5s;">
    <div class="container-fluid p-0 m-0">
      <a class="navbar-brand" href="/">
        <img src="{{ asset('/img/logo.png') }}" alt="" srcset="" width="50px">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Trang chủ</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('menu') }}">Món ăn</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="">Giỏ hàng</a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="{{ route('cart.checkout') }}">Đơn mua</a>
          </li>
          @guest
          @endguest
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              {{ auth()->user()->name }}
              </a>
              <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Hồ sơ</a></li>
              <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button class="dropdown-item">Đăng xuất
                </button>
              </form>
              </ul>
            </li>
            @if(auth()->user() && in_array(auth()->user()->role, ['admin', 'staff']))
              <li class="nav-item">
              <a class="nav-link" href="{{ route('manage.dashboard') }}">Quản lý</a>
              </li>
            @endif
          @endauth
        </ul>
        <!-- Giỏ hàng -->
        <a href="{{ route('cart.index') }}" class="me-3">
          <div class="rounded-circle bg-body-secondary d-flex justify-content-center align-items-center"
            style="width: 50px;height:50px;">
            <i class="fas fa-shopping-cart fa-2xl"></i>
          </div>
        </a>
        <!-- Tài khoản -->
        <a href="{{ route('login') }}" class="me-3">
          <div class="rounded-circle bg-body-secondary d-flex justify-content-center align-items-center"
            style="width: 50px;height:50px;">
            <i class="fas fa-user fa-2xl"></i>
          </div>
        </a>
      </div>
    </div>
  </nav>