@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('food-items.index') }}">
        @if ($mode == 'create')
          Thêm
        @elseif ($mode == 'update')
          Sửa
        @else
          Xóa
        @endif
        món ăn
      </a>
    </h1>
  </div>
  @include('food_items.form-content', [
    'mode' => $mode,
  ])
@endsection

@section('script')
<script>
  @foreach ($errors->all() as $error)
    console.warn(`{{ $error }}`)
  @endforeach
</script>
@endsection
