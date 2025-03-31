@extends('layouts.dash')

@section('head')
  <title>Employee</title>
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
      nhân viên
    </h1>
  </div>
  <div class="card">
    <div class="card-body">
      @php
        $variable = isset($employee) ? $employee : null;
        $form;
        switch ($mode) {
          case 'update':
            $form = [
              'action' => route('employees.update', $variable),
              'method' => 'PUT',
            ];
            break;
          default:
            $form = [
              'action' => route('employees.store'),
              'method' => 'POST',
            ];
            break;
        }
      @endphp
      <!-- Form chỉnh sửa -->
      <form action="{{ $form['action'] }}" method="POST">
        @method($form['method'])
        @csrf
        
        <x-form.input-hidden
          name="role"
          value="staff"
          />

        <div class="mb-3">
          <label for="name" class="form-label">
            Tên
          </label>
          <x-form.input
            type="text"
            name="name"
            :var="$variable"
            />
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">
            Email
          </label>
          <x-form.input
            type="email"
            name="email"
            :var="$variable"
            />
        </div>

        <div class="mb-3">
          <label for="phone" class="form-label">
            SDT
          </label>
          <x-form.input
            type="text"
            name="phone"
            :var="$variable"
            />
        </div>

        <div class="mb-3">
          <label for="address" class="form-label">
            Địa chỉ
          </label>
          <x-form.input
            type="text"
            name="address"
            :var="$variable"
            />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">
            Mật khẩu
          </label>
          <x-form.input
            type="password"
            name="password"
            :var="$variable"
            value="{{ $mode == 'update' ? null : '' }}"
            />
        </div>

        <div class="mb-3">
          <label for="password_confirmation" class="form-label">
            Xác nhận mật khẩu
          </label>
          <x-form.input
            type="password"
            name="password_confirmation"
            :var="$variable"
            />
        </div>

        {{-- <div class="mb-3">
          <label for="department_id" class="form-label">
            Department
          </label>
          <select
            name="department_id"
            id="department_id"
            @isset($employee)
              value="{{ old('department_id', $employee->department_id) }}"
            @endisset
            value="{{ old('department_id') }}"
            class="form-control @error('department_id') is-invalid @enderror"
          >
            @foreach ($departments as $department)
              <option value="{{ $department->id }}">
                {{ $department->name }}
              </option>
            @endforeach
          </select>
          @error('department_id')
            <div class="invalid-feedback">
              {{ $message }}
            </div>
          @enderror
        </div> --}}

        <!-- Nút hành động -->
        <button type="submit" class="btn btn-primary">Ok</button>
        <a href="{{ route('employees.index') }}" class="btn btn-secondary">
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
