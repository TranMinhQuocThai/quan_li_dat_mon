@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      @if ($mode == 'create')
        Thêm
      @elseif ($mode == 'update')
        Sửa
      @else
        Xóa
      @endif
      loại món
    </h1>
  </div>
  <div class="card">
    <div class="card-body">

      @php
        $form;
        switch ($mode) {
          case 'update':
            $form = [
              'action' => route('food-types.update', $foodType),
              'method' => 'PATCH',
            ];
            break;
          default:
            $form = [
              'action' => route('food-types.store'),
              'method' => 'POST',
            ];
            break;
        }
      @endphp
      <!-- Form chỉnh sửa -->
      <form action="{{ $form['action'] }}" method="POST">
      @method($form['method'])
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">
          Tên loại món
        </label>
        <input type="text" name="name" id="name"
          class="form-control @error('name') is-invalid @enderror"
          @isset($foodType)
            value="{{ old('name', $foodType->name) }}"
          @endisset
          value="{{ old('name') }}">
        @error('name')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
        @enderror
      </div>

      <!-- Nút hành động -->
      <button type="submit" class="btn btn-primary">Ok</button>
      <a href="{{ route('food-types.index') }}" class="btn btn-secondary">
        Quay lại
      </a>
      </form>
    </div>
  </div>
@endsection

@section('script')
<script>
  @foreach ($errors->all() as $error)
    console.warn(`{{ $error }}`)
  @endforeach
</script>
@endsection
