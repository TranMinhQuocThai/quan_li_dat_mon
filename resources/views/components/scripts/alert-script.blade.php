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