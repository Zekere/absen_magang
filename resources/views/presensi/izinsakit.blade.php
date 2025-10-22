@extends ('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  /* Custom styles untuk halaman konfigurasi */
  .config-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .config-header h3 {
    font-weight: 700;
    margin-bottom: 5px;
    font-size: 1.75rem;
  }

  .config-header h5 {
    font-weight: 400;
    opacity: 0.9;
    font-size: 1rem;
  }

  .config-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    overflow: hidden;
  }

  .config-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
  }

  .card-header-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border: none;
  }

  .card-header-custom h4 {
    margin: 0;
    font-weight: 600;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .input-group-custom {
    margin-bottom: 20px;
  }

  .input-group-custom .input-group-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    width: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
  }

  .input-group-custom .form-control {
    border: 2px solid #e0e0e0;
    padding: 12px 15px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
  }

  .input-group-custom .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .btn-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }

  .btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  }

  .info-box {
    background: #f8f9fa;
    border-left: 4px solid #667eea;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
  }

  .info-box i {
    color: #667eea;
    margin-right: 10px;
  }

  .info-box p {
    margin: 0;
    color: #6c757d;
    font-size: 0.9rem;
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .config-header {
      padding: 20px;
      margin-bottom: 20px;
    }

    .config-header h3 {
      font-size: 1.5rem;
    }

    .config-header h5 {
      font-size: 0.9rem;
    }

    .card-header-custom {
      padding: 15px;
    }

    .card-header-custom h4 {
      font-size: 1.1rem;
    }

    .input-group-custom .input-group-text {
      width: 40px;
      font-size: 1rem;
    }

    .input-group-custom .form-control {
      padding: 10px 12px;
      font-size: 0.9rem;
    }

    .btn-save {
      padding: 10px;
      font-size: 0.95rem;
    }
  }

  @media (max-width: 576px) {
    .config-header h3 {
      font-size: 1.25rem;
    }

    .config-header h5 {
      font-size: 0.85rem;
    }
  }
</style>

<div class="container-fluid mt-4 px-3 px-md-4">
  <!-- Header Section -->
  <div class="config-header">
    <h3 class="mb-1">
      <i class="bi bi-clipboard2-data"></i> Izin Sakit Cuti
    </h3>
        <h5 class="mb-0">Pencatatan data izin sakit cuti</h5>

  
  </div>

<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        <!-- Filter dan Search -->
        <div class="card mb-3">
          <div class="card-body">
            <div class="row">
              <div class="col-md-3">
                <label class="form-label">Show entries:</label>
                <select id="entriesPerPage" class="form-select">
                  <option value="5">5</option>
                  <option value="10" selected>10</option>
                  <option value="25">25</option>
                  <option value="50">50</option>
                  <option value="all">All</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Filter Status:</label>
                <select id="filterStatus" class="form-select">
                  <option value="">Semua Status</option>
                  <option value="1">Izin</option>
                  <option value="2">Sakit</option>
                  <option value="3">Cuti</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Filter Persetujuan:</label>
                <select id="filterApproved" class="form-select">
                  <option value="">Semua</option>
                  <option value="0">Pending</option>
                  <option value="1">Disetujui</option>
                  <option value="2">Ditolak</option>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Search:</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari NIK, Nama, Jabatan...">
              </div>
            </div>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-bordered table-striped align-middle" id="dataTable">
            <thead class="table-dark text-center">
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>NIK</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Bukti</th>
                <th>Persetujuan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tableBody">
              @foreach ($izinsakit as $d)
                <tr data-status="{{ $d->status }}" data-approved="{{ $d->status_approved }}">
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td>{{ date('d-m-Y', strtotime($d->tgl_izin)) }}</td>
                  <td>{{ $d->nik }}</td>
                  <td>{{ $d->nama_lengkap }}</td>
                  <td>{{ $d->jabatan }}</td>
                  <td>
                    @if ($d->status == "1")
                      <span class="badge bg-info">Izin</span>
                    @elseif ($d->status == "2")
                      <span class="badge bg-warning">Sakit</span>
                    @elseif ($d->status == "3")
                      <span class="badge bg-success">Cuti</span>
                    @else
                      <span class="badge bg-secondary">Tidak Diketahui</span>
                    @endif
                  </td>
                  <td>{{ $d->keterangan }}</td>
                  <td class="text-center">
                    @if($d->bukti_surat)
                      <a href="/presensi/{{ $d->id }}/lihatbukti" class="btn btn-sm btn-info" title="Lihat Bukti">
                        <i class="bi bi-eye"></i>
                      </a>
                      <a href="/presensi/{{ $d->id }}/downloadbukti" class="btn btn-sm btn-success" title="Download">
                        <i class="bi bi-download"></i>
                      </a>
                    @else
                      <span class="badge bg-secondary">Tidak Ada</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if ($d->status_approved == 1)
                      <span class="badge bg-success">Disetujui</span>
                    @elseif ($d->status_approved == 2)
                      <span class="badge bg-danger">Ditolak</span>
                    @else
                      <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($d->status_approved==0)
                      <a href="#" class="btn btn-sm btn-primary approved" id_izinsakit="{{ $d->id }}" title="Proses Persetujuan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-external-link">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6"></path>
                          <path d="M11 13l9 -9"></path>
                          <path d="M15 4h5v5"></path>
                        </svg>
                      </a>
                    @else
                      <a href="/presensi/{{ $d->id }}/batalkanizinsakit" class="btn btn-sm btn-danger" title="Batalkan Persetujuan">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-circle-x"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M10 10l4 4m0 -4l-4 4" /></svg>
                      </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <!-- Pagination Info dan Controls -->
        <div class="row mt-3">
          <div class="col-md-6">
            <p id="paginationInfo" class="text-muted">Showing 1 to 10 of {{ count($izinsakit) }} entries</p>
          </div>
          <div class="col-md-6">
            <nav>
              <ul class="pagination justify-content-end" id="pagination">
                <!-- Pagination buttons will be generated by JavaScript -->
              </ul>
            </nav>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-izinsakit" tabindex="-1" aria-hidden="true" data-bs-backdrop="false" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold"><i class="bi bi-check-circle-fill me-2"></i> Persetujuan </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/presensi/approved" method="POST">
            @csrf 
            <input type="hidden" id="id_izinsakit_form" name="id_izinsakit_form">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label">Status Persetujuan:</label>
                        <select name="status_approved" id="status_approved" class="form-select">
                            <option value="1">Disetujui</option>
                            <option value="2">Ditolak</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <div class="form-group">
                        <button class="btn btn-primary w-100" type="submit">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-send"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                          Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
    let currentPage = 1;
    let entriesPerPage = 10;
    let allRows = [];
    let filteredRows = [];

    // Initialize
    function init() {
        allRows = Array.from(document.querySelectorAll('#tableBody tr'));
        filteredRows = [...allRows];
        applyFilters();
    }

    // Apply filters
    function applyFilters() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const statusFilter = $('#filterStatus').val();
        const approvedFilter = $('#filterApproved').val();

        filteredRows = allRows.filter(row => {
            const text = row.textContent.toLowerCase();
            const status = row.getAttribute('data-status');
            const approved = row.getAttribute('data-approved');

            const matchSearch = text.includes(searchTerm);
            const matchStatus = !statusFilter || status === statusFilter;
            const matchApproved = !approvedFilter || approved === approvedFilter;

            return matchSearch && matchStatus && matchApproved;
        });

        currentPage = 1;
        displayPage();
    }

    // Display current page
    function displayPage() {
        // FIX: Handle 'all' case properly
        const isShowAll = entriesPerPage === 'all';
        const itemsPerPage = isShowAll ? filteredRows.length : parseInt(entriesPerPage);
        const start = isShowAll ? 0 : (currentPage - 1) * itemsPerPage;
        const end = isShowAll ? filteredRows.length : start + itemsPerPage;

        // Hide all rows
        allRows.forEach(row => row.style.display = 'none');

        // Show filtered rows for current page
        filteredRows.slice(start, end).forEach((row, index) => {
            row.style.display = '';
            // FIX: Properly calculate row number
            row.querySelector('td:first-child').textContent = start + index + 1;
        });

        updatePaginationInfo(start, end);
        updatePaginationButtons();
    }

    // Update pagination info
    function updatePaginationInfo(start, end) {
        const total = filteredRows.length;
        const showing = total === 0 ? 0 : start + 1;
        const to = Math.min(end, total);
        $('#paginationInfo').text(`Showing ${showing} to ${to} of ${total} entries${allRows.length !== total ? ` (filtered from ${allRows.length} total entries)` : ''}`);
    }

    // Update pagination buttons
    function updatePaginationButtons() {
        const isShowAll = entriesPerPage === 'all';
        const totalPages = isShowAll ? 1 : Math.ceil(filteredRows.length / parseInt(entriesPerPage));
        const pagination = $('#pagination');
        pagination.empty();

        if (totalPages <= 1) return;

        // Previous button
        pagination.append(`
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
            </li>
        `);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                pagination.append(`
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                    </li>
                `);
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                pagination.append('<li class="page-item disabled"><a class="page-link">...</a></li>');
            }
        }

        // Next button
        pagination.append(`
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
            </li>
        `);
    }

    // Event listeners
    $('#entriesPerPage').on('change', function() {
        entriesPerPage = $(this).val();
        currentPage = 1;
        displayPage();
    });

    $('#searchInput').on('keyup', function() {
        applyFilters();
    });

    $('#filterStatus, #filterApproved').on('change', function() {
        applyFilters();
    });

    $(document).on('click', '#pagination a', function(e) {
        e.preventDefault();
        const page = parseInt($(this).attr('data-page'));
        if (page && page !== currentPage) {
            currentPage = page;
            displayPage();
            $('html, body').animate({ scrollTop: 0 }, 300);
        }
    });

    // Modal approved
    $(document).on('click', '.approved', function(e) {
        e.preventDefault();
        const id_izinsakit = $(this).attr("id_izinsakit");
        $("#id_izinsakit_form").val(id_izinsakit);
        $("#modal-izinsakit").modal("show");
    });

    // Initialize on page load
    init();
});
</script>
@endpush