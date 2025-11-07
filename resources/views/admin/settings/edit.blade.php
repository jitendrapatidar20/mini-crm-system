@extends('layouts.admin')
@section('content')

<div class="col-md-8 offset-md-2" style="padding: 20px;">
  <div class="card card-primary card-outline" style="padding: 20px;">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="card-title">Edit Setting</div>
      <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <form id="editSettingForm">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="{{ $setting->name }}" class="form-control" readonly>
      </div>

      <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ $setting->description }}</textarea>
      </div>

      <div class="mb-3">
        <label>Parameter Type</label>
        <select name="parameter_type" class="form-select">
          <option value="string" {{ $setting->parameter_type == 'string' ? 'selected' : '' }}>String</option>
          <option value="email" {{ $setting->parameter_type == 'email' ? 'selected' : '' }}>Email</option>
          <option value="number" {{ $setting->parameter_type == 'number' ? 'selected' : '' }}>Number</option>
          <option value="url" {{ $setting->parameter_type == 'url' ? 'selected' : '' }}>URL</option>
          <option value="boolean" {{ $setting->parameter_type == 'boolean' ? 'selected' : '' }}>Boolean</option>
        </select>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$('#editSettingForm').on('submit', function(e) {
  e.preventDefault();
  $.ajax({
    url: "{{ route('admin.settings.update', $setting->id) }}",
    type: 'POST',
    data: $(this).serialize(),
    success: res => {
     showMesseage('success', res.message)
     setTimeout(function(){
            window.location.href = "{{ route('admin.settings.index') }}";
     }, 2000);
    },
    error: xhr => {
      showMesseage('error', 'Error updating record');
      console.log(xhr.responseJSON);
    }
  });
});
</script>
@endsection
