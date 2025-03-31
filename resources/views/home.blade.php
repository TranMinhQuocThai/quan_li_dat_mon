<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt món</title>
    <link rel="stylesheet" href="{{ asset('css/nunito.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
</head>

<style>
    a {
        text-decoration: none;
        margin: 0 5px;
    }

    /* button:hover {
        background: pink;
        color: black;
    } */

    * {
        transition: 0.5s;
    }

    .col-lg-3>.card:hover {
        translate: 0 -0.6em;
        filter: none;
        box-shadow: 0 0 1em gray;
    }
</style>

<body>
    
@include('components.navbar')
    <!-- Hero Section -->
    <header class="">
        <div id="carouselExampleCaptions" class="carousel slide">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('img/spaces/space1.jpg') }}" class="d-block w-100" alt="..."
                        style="height: 630px; object-fit: cover; object-position: bottom; filter: brightness(85%);">
                    <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                        <h1 class="text-center">Welcome to Our Restaurant</h1>
                        <p class="lead text-center">Trải nghiệm không gian ấm cúng <br> Hương vị khó quên!</p>
                        <a href="/menu" class="btn btn-outline-light mt-3">Đặt món ngay</a>
                    </div>
                </div>
                @foreach ($slogans as $slogan)
                    <div class="carousel-item">
                        <img src="{{ asset($slogan['image']) }}" class="d-block w-100" alt="..."
                            style="height: 630px; object-fit: cover; object-position: bottom; filter: brightness(70%);">
                        <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                            <h1 class="text-center">{!! $slogan['slogan'] !!}</h1>
                            <!-- <a href="/" class="btn btn-outline-light mt-3">Đặt bàn ngay</a> -->
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </header>
        <!-- Welcome -->
        <div class="container-fluid">
    <section class="row pt-5">
        <div class="col-md-5 pb-5">
            <div class="content m-2 col-10 ms-auto">
                <h1 class="">Welcome to <br><span class="text-danger">Delima Restaurant</span></h1>
                <p>Chào mừng bạn đến với không gian ấm cúng và gần gũi của chúng tôi! Ở đây, bạn sẽ tìm thấy những món
                    ăn thơm ngon, đậm đà hương vị với mức giá phải chăng. Chúng tôi luôn chọn nguyên liệu tươi sạch
                    nhất, chế biến với cả tâm huyết để mang đến cho bạn những bữa ăn không chỉ ngon miệng mà còn đầy đủ
                    dinh dưỡng.
                    <br> Dù bạn đến để thưởng thức một bữa ăn nhanh gọn hay muốn quây quần cùng gia đình, bạn bè, chúng
                    tôi luôn sẵn sàng phục vụ với sự tận tâm và nụ cười thân thiện. Hãy thư giãn, tận hưởng không gian
                    thoải mái và để chúng tôi mang đến cho bạn những trải nghiệm ẩm thực thật trọn vẹn!
                </p>
                <p class="text-dark-emphasis" style="text-align:right; font-family:'Dancing Script',cursive"> from Bui Thi Chi Thai</p>

                <!-- <a href="" class="btn btn-danger rounded-1">RESERVATION</a> -->
            </div>
        </div>
        <div class="col-md-7 pb-5 text-center my-auto">
            <img class="me-3" width="35%" style="box-shadow: -10px -5px 0 gray;" src="{{asset('img/celeb/celeb.jpeg')}}" alt="">
            <img class="ms-3" width="35%" style="box-shadow: -10px -5px 0 gray;" src="{{asset('img/celeb/celeb1.jpeg')}}" alt="">
        </div>
    </section>
    </div>
    <section>
        <img src="{{asset('img/spaces/space4.jpg')}}" height="300px" width="100%" style="object-fit:cover;" alt="">
    </section>

    <!-- Menu Section -->
    <section id="menu" class="container py-5">
        <h2 class="text-center mb-4">Special Dishes</h2>
        <div class="row">
            @foreach ($foodItems as $foodItem)           
                <div class="col-lg-3 col-md-4 mb-3">
                    <div class="card">
                        <img src="{{asset('storage/' . $foodItem->image)}}" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">{{$foodItem->name}}</h5>
                            <p class="card-text">Creamy Alfredo sauce with fresh herbs.</p>
                            <p class="text-primary">{{$foodItem->price}}đ</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5" 
            style="background-image: url('{{ asset('img/spaces/space3.jpg') }}');
           background-position:center;
           background-size:cover;
           color:white;">
        <div class="container">
            <h2 class="text-center">VỀ CHÚNG TÔI</h2>
            <div class="row">
                <div class="col-md-6">
                    <h3>Team</h3>
                    <ul>
                        <li><strong>Đầu bếp</strong>: Huỳnh Chí Hào</li>
                        <li><strong>Phục vụ</strong>:
                            <ol>- Trần Minh Quốc Thái</ol>
                            <ol>- Bùi Thảo Nhi</ol>
                        </li>
                        <li><strong>Thu ngân</strong>: Nguyễn Thị Mỹ Hiền</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3>Delima Restaurant</h3>
                    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Enim aperiam repellat accusantium
                        mollitia, aspernatur impedit nam, sunt hic dolore amet consequuntur qui aliquam tempora delectus
                        alias fuga possimus doloribus nisi?</p>
                </div>
            </div>
            <!-- <p>We bring the finest ingredients and recipes from around the world to your plate.</p> -->
        </div>
    </section>


    <!-- Contact Section -->
    <section id="contact" class="bg-dark text-white py-5 text-center">
        <h2>Contact & Reservations</h2>
        <p>📍 123 Main Street, Your City | 📞 (123) 456-7890</p>
        <a href="tel:1234567890" class="btn btn-warning">Call Now</a>
    </section>

    <!-- Footer -->
    <footer class="bg-black text-white text-center py-3">
        <p>&copy; 2025 DELIMA. All Rights Reserved.</p>
    </footer>

</body>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{asset('vendor/sweetalert2/sweetalert2.all.min.js')}}"></script>
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

</html>