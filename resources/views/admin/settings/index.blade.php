@extends('layouts.admin')
@section('content')

<div class="col-md-12" style="padding: 20px;">
  <div class="card card-primary card-outline" style="padding: 20px;">
   <div class="card-header">
      <div class="row w-100">
          <div class="col-md-6">
          <h5 class="card-title mb-0">Settings List</h5>
          </div>
        <div class="col-md-6 text-end">
          <a href="{{ route('admin.settings.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Setting
         </a>
        </div>
    </div>
   </div>
    <table class="table table-bordered" id="settingsTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Description</th>
          <th>Parameter Type</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<script>

  const table = $('#settingsTable').DataTable({
    ajax: {
      url: "{{ route('admin.settings.index') }}",
      dataSrc: ""
    },
    columns: [
      { data: null, render: (d,t,r,m) => m.row + 1 },
      { data: 'name' },
      { data: 'description' },
      { data: 'parameter_type' },
      { data: 'status', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' },
      { data: 'id', render: id => `
          <a href="/admin/settings/edit/${id}" class="btn btn-sm btn-primary"><i class="bi bi-pencil-square"></i></a>
          <button class="btn btn-sm btn-danger deleteBtn" data-id="${id}"><i class="bi bi-trash"></i></button>
        `
      }
    ]
  });

  $(document).on('click', '.deleteBtn', function() {
    if (!confirm('Delete this setting?')) return;
    const id = $(this).data('id');
    $.ajax({
      url: `/admin/settings/${id}`,
      type: 'DELETE',
      data: { _token: '{{ csrf_token() }}' },
      success: res => { alert(res.message); table.ajax.reload(); },
      error: () => alert('Error deleting record.')
    });
  });

</script>
@endsection
