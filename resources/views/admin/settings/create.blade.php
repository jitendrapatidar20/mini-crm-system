@extends('layouts.admin')
@section('content')

<div class="col-md-8 offset-md-2" style="padding: 20px;">
  <div class="card card-primary card-outline" style="padding: 20px;">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="card-title">Create Setting</div>
      <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <form id="createSettingForm">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label>Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required>
      </div>

      <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
      </div>

      <div class="mb-3">
        <label>Parameter Type <span class="text-danger">*</span></label>
        <select name="parameter_type" class="form-select" required>
          <option value="">Select Type</option>
          <option value="string">String</option>
          <option value="email">Email</option>
          <option value="number">Number</option>
          <option value="url">URL</option>
          <option value="boolean">Boolean</option>
        </select>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success">Save</button>
      </div>
    </form>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$('#createSettingForm').on('submit', function(e) {
  e.preventDefault();
  $.ajax({
    url: "{{ route('admin.settings.store') }}",
    type: 'POST',
    data: $(this).serialize(),
    success: res => {
       showMesseage('success', res.message)
       setTimeout(function(){
            window.location.href = "{{ route('admin.settings.index') }}";
        }, 2000);
    },
    error: xhr => {
      showMesseage('error', 'Error saving record');
      console.log(xhr.responseJSON);
    }
  });
});
</script>
@endsection
