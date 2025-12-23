@extends('layouts.admin')

@section('content')
<div class="col-md-12" style="padding: 20px;">
  <div class="card card-primary card-outline" style="padding: 20px;">
    <div class="card-header">
      <div class="row w-100">
          <div class="col-md-6">
          <h5 class="card-title mb-0">Contacts List</h5>
          </div>
        <div class="col-md-6 text-end">
           <button id="createBtn" class="btn btn-primary"> <i class="bi bi-plus-circle"></i> Add Contact</button>
        </div>
    </div>
   </div>

    {{-- Filter Form --}}
    <form id="filterForm" class="mb-3 mt-2">
      <div class="row g-2">
        <div class="col-md-3"><input type="text" name="name" id="f_name" class="form-control" placeholder="Name"></div>
        <div class="col-md-3"><input type="text" name="email" id="f_email" class="form-control" placeholder="Email"></div>
        <div class="col-md-3">
          <select name="gender" id="f_gender" class="form-select">
            <option value="">Any Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" class="btn btn-info">Filter</button>
          <button type="button" id="clearFilters" class="btn btn-secondary">Clear</button>
        </div>
      </div>
    </form>

    <table class="table table-bordered" id="contactTable">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Gender</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<!-- Create/Edit Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="contactForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="contact_id" name="contact_id">
        <div class="modal-header">
          <h5 class="modal-title">Add Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body row g-3">
          <div class="col-md-6">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label>Phone</label>
            <input type="number" name="phone" class="form-control" required>
          </div>

          <div class="col-md-6">
            <label>Gender</label>
            <select name="gender" class="form-select" required>
              <option value="">Select</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="col-md-6">
            <label>Profile Image</label>
            <input type="file" name="profile_image" class="form-control" >
            <img id="previewProfile" src="" class="img-thumbnail mt-2" style="width:80px; display:none;">
          </div>

          <div class="col-md-6">
            <label>Additional File</label>
            <input type="file" name="additional_file" class="form-control">
            <a id="previewFile" href="#" target="_blank" class="d-none mt-2">View File</a>
          </div>

          {{-- Dynamic Custom Fields --}}
          <div id="customFieldsArea" class="col-12 mt-3 row"></div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Merge Modal -->
<div class="modal fade" id="mergeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Merge Contacts</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="row g-2 mb-3">
          <div class="col-md-4">
            <label>Master Contact ID</label>
            <input id="merge_master_id" class="form-control" placeholder="Master contact id">
          </div>
          <div class="col-md-4">
            <label>Secondary Contact ID</label>
            <input id="merge_secondary_id" class="form-control" placeholder="Secondary contact id" readonly>
          </div>
          <div class="col-md-4 d-flex align-items-end gap-2">
            <button id="previewMergeBtn" class="btn btn-info">Preview</button>
            <button id="confirmMergeBtn" class="btn btn-success">Confirm Merge</button>
          </div>
        </div>
        <div id="mergePreviewArea" class="table-responsive"></div>
      </div>
    </div>
  </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>


  // ---  Custom Fields from Backend ---
  const customFields = @json($customFields ?? []);
  function renderCustomFields(values = {}) {
    let html = '';
    customFields.forEach(cf => {
      html += `
        <div class="col-md-6 mb-2">
          <label>${cf.name}</label>
          <input type="text" name="custom[${cf.id}]" class="form-control"
            value="${values[cf.id] ?? ''}">
        </div>`;
    });
    $('#customFieldsArea').html(html);
  }
  renderCustomFields();

  const table = $('#contactTable').DataTable({
    ajax: {
      url: "{{ route('admin.contacts.list') }}",
      data: d => {
        d.name = $('#f_name').val();
        d.email = $('#f_email').val();
        d.gender = $('#f_gender').val();
      }
    },
    columns: [
      { data: 'id' },
      { data: 'name' },
      { data: 'email' },
      { data: 'phone' },
      { data: 'gender' },
      { data: 'is_active', render: d => d ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' },
      { data: 'id', render: id => `
          <button class="btn btn-sm btn-info editBtn" data-id="${id}">Edit</button>
          <button class="btn btn-sm btn-danger deleteBtn" data-id="${id}">Delete</button>
          <button class="btn btn-sm btn-warning mergeBtn" data-id="${id}">Merge</button>
        `
      }
    ]
  });

  // --- Filters ---
  $('#filterForm').submit(e => { e.preventDefault(); table.ajax.reload(); });
  $('#clearFilters').click(() => { $('#filterForm')[0].reset(); table.ajax.reload(); });

  // --- Create Contact ---
  $('#createBtn').click(() => {
    $('#contactForm')[0].reset();
    $('#contact_id').val('');
    $('#previewProfile').hide();
    $('#previewFile').hide();
    renderCustomFields();
    $('.modal-title').text('Add Contact');
    $('#contactModal').modal('show');
  });

  // --- Save Contact ---
  $('#contactForm').submit(function(e){
    e.preventDefault();
    const id = $('#contact_id').val();
    const url = id ? `/admin/contacts/${id}` : "{{ route('admin.contacts.store') }}";
    const fd = new FormData(this);
    $.ajax({
      url: url, type: 'POST', data: fd, processData: false, contentType: false,
      success: res => {
        showMesseage('success', res.message);
        setTimeout(function(){
            $('#contactModal').modal('hide');
        table.ajax.reload();
        }, 2000);

      },
      error: xhr => showMesseage('error',xhr.responseJSON?.message || 'Save failed')
    });
  });

  // --- Edit ---
  $(document).on('click', '.editBtn', function(){
    const id = $(this).data('id');
    $.get(`/admin/contacts/${id}/edit`, res => {
      const c = res.contact;
      $('#contact_id').val(c.id);
      $('[name="name"]').val(c.name);
      $('[name="email"]').val(c.email);
      $('[name="phone"]').val(c.phone);
      $('[name="gender"]').val(c.gender);
      if (c.profile_image) {
        $('#previewProfile').attr('src', `/storage/${c.profile_image}`).show();
      }
      if (c.additional_file) {
        $('#previewFile').attr('href', `/storage/${c.additional_file}`).removeClass('d-none').show();
      }
      // custom fields
      const customValues = {};
      if (c.custom_values) {
        c.custom_values.forEach(cv => { customValues[cv.custom_field_id] = cv.value; });
      }
      renderCustomFields(customValues);
      $('.modal-title').text('Edit Contact');
      $('#contactModal').modal('show');
    });
  });

  // --- Delete Contact ---
  $(document).on('click', '.deleteBtn', function(){
    const id = $(this).data('id');
    if (!confirm('Mark contact inactive?')) return;
    $.ajax({
      url: `/admin/contacts/${id}`, type: 'DELETE',
      data: { _token: '{{ csrf_token() }}' },
      success: res => { 
        showMesseage('success', res.message)
        setTimeout(function(){
        table.ajax.reload();
        }, 2000);

      },
      error: () => showMesseage('error','Delete failed')
    });
  });

 
  $(document).on('click', '.mergeBtn', function(){
    const sec = $(this).data('id');
    $('#merge_secondary_id').val(sec);
    $('#merge_master_id').val('');
    $('#mergePreviewArea').html('');
    $('#mergeModal').modal('show');
  });

  $('#previewMergeBtn').click(() => {
      const master = $('#merge_master_id').val();
      const secondary = $('#merge_secondary_id').val();

      $.post("{{ route('admin.contacts.merge.preview') }}", {
        primary_id: master,
        secondary_id: secondary,
        _token: '{{ csrf_token() }}'
      }, res => {

        let html = `
        <table class="table table-bordered">
          <tr>
            <th>Field</th>
            <th>Master</th>
            <th>Secondary</th>
            <th>Action</th>
          </tr>`;

        res.comparison.customs.forEach(c => {
          html += `
          <tr>
            <td>${c.field_name}</td>
            <td>${c.primary ?? '-'}</td>
            <td>${c.secondary ?? '-'}</td>
            <td>
              ${(c.primary && c.secondary && c.primary !== c.secondary)
                ? '<span class="badge bg-warning">Conflict</span>'
                : '<span class="badge bg-success">Copy</span>'}
            </td>
          </tr>`;
        });

        html += '</table>';
        $('#mergePreviewArea').html(html);
      });
  });

  $('#confirmMergeBtn').click(() => {
    const master = $('#merge_master_id').val();
    const secondary = $('#merge_secondary_id').val();
    $.post("{{ route('admin.contacts.merge.do') }}", {
      master_id: master, secondary_id: secondary, _token: '{{ csrf_token() }}'
    }, res => {
        showMesseage('success', res.message)
        setTimeout(function(){
         $('#mergeModal').modal('hide');
         table.ajax.reload();
        }, 2000);
     
    });
  });

</script>
@endsection
