@extends('layouts.dash')

@section('head')
  <title></title>
@endsection

@section('content')
  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">
      <a href="{{ route('food-items.index') }}">
        {{ $foodItem->name }}
      </a>
    </h1>
  </div>

  @include('food_items.form-content', [
      'mode' => 'update',
  ])

  <div class="card mb-3">
    <div class="card-body">
      <form action="{{ route('food_ingredients.store') }}" method="POST">
        @csrf

        <!-- Food Item Selection -->
        <div>
          <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}"
            class="form-label @error('food_item_id') is-invalid @enderror"
            >
          @error('food_item_id')
            <div class="invalid-feedback mb-3">
              {{ $message }}
            </div>
          @enderror
        </div>

        <div class="mb-3">
          <label for="ingredient_id" class="form-label">
            Nguyên liệu
          </label>
          <select name="ingredient_id" id="ingredient_id"
            class="form-control @error('ingredient_id') is-invalid @enderror"
            >
            <option value="">Chọn nguyên liệu</option>
            @foreach ($ingredients as $item)
              <option value="{{ $item->id }}"
                @if (old('ingredient_id') == $item->id) 
                  selected
                @endif
                >
                {{ $item->name }} - {{ $item->unit }}
              </option>
            @endforeach
          </select>
          @error('ingredient_id')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div>

        <!-- Quantity Input -->
        <div class="mb-3">
          <label for="quantity" class="form-label">Số lượng</label>
          <input
            type="number" name="quantity" id="quantity"
            value="{{ old('quantity', 1) }}"
            step="0.001"
            placeholder="Nhập tên"
            class="form-control @error('quantity') is-invalid @enderror"
          >
          @error('quantity')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
          @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Add</button>
      </form>
    </div>
  </div>

  <!-- Ingredients of food -->
  <div class="card mb-3">
    <div class="card-body">
      <h5 class="card-title">Nguyên liệu</h5>
      <table class="table">
        <thead>
          <tr>
            <th></th>
            <th>Nguyên liệu</th>
            <th>Đơn vị</th>
            <th>Số lượng</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach ($foodItem->ingredients as $idx => $item)
            <tr>
              <td>{{ $idx + 1 }}</td>
              <td>{{ $item->name }}</td>
              <td>{{ $item->unit }}</td>
              <td>
                <form action="{{ route('food_ingredients.update', $item->pivot->id) }}" method="POST"
                  class="d-inline">
                  @csrf
                  @method('PATCH')
                  <!-- Food Item Selection -->
                  <input type="hidden" name="food_item_id" value="{{ $foodItem->id }}"
                    class="form-label @error('food_item_id') is-invalid @enderror"
                    >
                  @error('food_item_id')
                    <div class="invalid-feedback mb-3">
                      {{ $message }}
                    </div>
                  @enderror
          
                  <!-- Food Item Selection -->
                  <input type="hidden" name="ingredient_id" value="{{ $item->id }}"
                    class="form-label @error('ingredient_id') is-invalid @enderror"
                    >
                  @error('ingredient_id')
                    <div class="invalid-feedback mb-3">
                      {{ $message }}
                    </div>
                  @enderror
          
                  <!-- Quantity Input -->
                  <input
                    type="number" name="quantity" id="quantity"
                    value="{{ old('quantity', $item->pivot->quantity) }}"
                    step="0.001"
                    placeholder="Nhập tên"
                    class="d-inline form-control form-control-sm @error('quantity') is-invalid @enderror"
                    style="width: 8em;"
                  >
                  @error('quantity')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                  <!-- Submit Button -->
                  <button type="submit" class="btn btn-sm btn-warning">Ok</button>

                </form>
                
                <form
                  action="{{ route('food_ingredients.destroy', $item->pivot->id) }}"
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
    </div>
  </div>

  <!-- Back Button -->
  <a href="{{ route('food-items.index') }}" class="btn btn-secondary mt-3">
    Quay lại
  </a>
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
    @foreach ($errors->all() as $error)
      console.warn(`{{ $error }}`)
    @endforeach
  </script>
@endsection
