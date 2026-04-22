@extends('layout.admin.template')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ── Scoped ke .adm-page agar tidak merembet ke template ── */
.adm-page { padding: 22px 20px 48px; }
.adm-page * { box-sizing: border-box; }

/* ─── HEADER ─── */
.adm-page .adm-header { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
.adm-page .adm-header-icon {
    width: 42px; height: 42px; border-radius: 11px;
    background: #3b6ff0; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0;
}
.adm-page .adm-header-icon i { font-size: 19px; color: #fff; }
.adm-page .adm-header-title { font-size: 17px; font-weight: 700; margin: 0 0 2px; color: #0f1c3f; }
.adm-page .adm-header-sub   { font-size: 12px; color: #94a3b8; margin: 0; }

/* ─── STAT ROW ─── */
.adm-page .adm-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 10px; margin-bottom: 18px; }
@media(max-width:576px){ .adm-page .adm-stats { grid-template-columns:1fr 1fr; } }

.adm-page .adm-stat {
    background: #fff; border: 1px solid #e8edf5;
    border-radius: 12px; padding: 13px 15px;
}
.adm-page .adm-stat-label { font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; margin-bottom: 5px; }
.adm-page .adm-stat-val   { font-size: 22px; font-weight: 700; color: #0f1c3f; }
.adm-page .adm-stat-dot   { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #16a34a; margin-right: 5px; }

/* ─── MAIN CARD ─── */
.adm-page .adm-card {
    background: #fff; border: 1px solid #e8edf5;
    border-radius: 16px; overflow: hidden;
}
.adm-page .adm-card-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 13px 18px; border-bottom: 1px solid #f0f3f9;
}
.adm-page .adm-card-title { font-size: 14px; font-weight: 700; color: #0f1c3f; margin: 0; }
.adm-page .adm-btn-add {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; font-weight: 600;
    color: #3b6ff0; background: #e8f0fe;
    border: none; border-radius: 8px;
    padding: 7px 13px; cursor: pointer; text-decoration: none;
    transition: background .15s;
}
.adm-page .adm-btn-add:hover { background: #d0e3fc; color: #1d4ed8; }

/* ─── SEARCH BAR ─── */
.adm-page .adm-search {
    display: flex; gap: 8px; align-items: center;
    padding: 12px 18px; border-bottom: 1px solid #f0f3f9;
}
.adm-page .adm-search-wrap {
    flex: 1; display: flex; align-items: center; gap: 7px;
    background: #f8f9fa; border: 1px solid #e5e7eb;
    border-radius: 9px; padding: 0 11px; height: 34px;
}
.adm-page .adm-search-wrap i { font-size: 13px; color: #aaa; flex-shrink: 0; }
.adm-page .adm-search-wrap input {
    border: none; background: transparent; font-size: 13px;
    color: #333; outline: none; width: 100%;
}
.adm-page .adm-search-wrap input::placeholder { color: #c0c0c0; }
.adm-page .adm-search-btn {
    height: 34px; padding: 0 16px;
    background: #3b6ff0; color: #fff;
    border: none; border-radius: 9px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    transition: background .15s; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 5px;
}
.adm-page .adm-search-btn:hover { background: #1d4ed8; }
.adm-page .adm-reset-btn {
    height: 34px; padding: 0 13px;
    background: #f1f5f9; color: #64748b;
    border: 1px solid #e5e7eb; border-radius: 9px;
    font-size: 12px; font-weight: 600; cursor: pointer;
    transition: background .15s; white-space: nowrap;
    display: inline-flex; align-items: center; gap: 5px;
    text-decoration: none;
}
.adm-page .adm-reset-btn:hover { background: #e5e7eb; color: #374151; }

/* ─── TABLE ─── */
.adm-page .adm-table-wrap { overflow-x: auto; }
.adm-page .adm-table {
    width: 100%; border-collapse: collapse;
    font-size: 13px; min-width: 600px;
}
.adm-page .adm-table thead th {
    /* ↓ padding dikurangi dari 10px 20px → 8px 12px */
    padding: 8px 12px;
    font-size: 11px; font-weight: 700; color: #94a3b8;
    text-align: left; text-transform: uppercase; letter-spacing: .04em;
    background: #f8faff; border-bottom: 1px solid #edf0f7;
    white-space: nowrap;
}
.adm-page .adm-table tbody td {
    /* ↓ padding dikurangi dari 12px 20px → 9px 12px */
    padding: 9px 12px;
    border-bottom: 1px solid #f5f7fc; color: #374151;
    vertical-align: middle;
}
.adm-page .adm-table tbody tr:last-child td { border-bottom: none; }
.adm-page .adm-table tbody tr:hover td { background: #f8faff; }

/* nama cell */
.adm-page .adm-name-cell { display: flex; align-items: center; gap: 8px; }
.adm-page .adm-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: #e8f0fe; display: flex; align-items: center;
    justify-content: center; font-size: 10px; font-weight: 700;
    color: #1d4ed8; flex-shrink: 0;
}
.adm-page .adm-uname { font-weight: 600; color: #0f1c3f; }

/* action cell */
.adm-page .adm-act { display: flex; gap: 5px; align-items: center; flex-wrap: wrap; }
.adm-page .adm-btn-edit,
.adm-page .adm-btn-del {
    display: inline-flex; align-items: center; gap: 3px;
    font-size: 11px; font-weight: 600; border: none;
    border-radius: 6px; padding: 4px 9px; cursor: pointer;
    transition: opacity .15s; text-decoration: none;
}
.adm-page .adm-btn-edit { background: #e8f0fe; color: #1d4ed8; }
.adm-page .adm-btn-edit:hover { background: #c7dcfa; }
.adm-page .adm-btn-del  { background: #fef2f2; color: #dc2626; }
.adm-page .adm-btn-del:hover  { background: #fecaca; }

/* pager */
.adm-page .adm-pager { padding: 12px 18px; border-top: 1px solid #f0f3f9; }

/* ─── MODAL ─── */
.adm-modal .modal-content {
    border: none; border-radius: 16px;
    box-shadow: 0 12px 40px rgba(0,0,0,0.14);
}
.adm-modal .modal-header {
    background: #0f1c3f; border: none;
    border-radius: 16px 16px 0 0; padding: 15px 20px;
    display: flex; align-items: center; justify-content: space-between;
}
.adm-modal .modal-title {
    font-size: 14px; font-weight: 700; color: #fff;
    display: flex; align-items: center; gap: 7px; margin: 0;
}
.adm-modal .modal-header .btn-close { filter: brightness(0) invert(1); opacity: .7; }
.adm-modal .modal-body { padding: 20px; }
.adm-modal .modal-footer {
    padding: 13px 20px; border-top: 1px solid #f0f3f9; gap: 8px;
}

.adm-modal .adm-field { margin-bottom: 13px; }
.adm-modal .adm-field-label {
    font-size: 11px; font-weight: 700; color: #64748b;
    text-transform: uppercase; letter-spacing: .04em; margin-bottom: 6px;
}
.adm-modal .adm-field .input-group-text {
    background: #f8f9fa; border: 1px solid #e5e7eb;
    color: #94a3b8; width: 38px; justify-content: center;
}
.adm-modal .adm-field .form-control {
    border: 1px solid #e5e7eb; font-size: 13px; padding: 9px 12px;
}
.adm-modal .adm-field .form-control:focus {
    border-color: #3b6ff0;
    box-shadow: 0 0 0 3px rgba(59,111,240,0.1);
}
.adm-modal .adm-field-hint { font-size: 11px; color: #aaa; margin-top: 4px; }

.adm-modal .adm-btn-save {
    background: #3b6ff0; color: #fff; border: none;
    border-radius: 9px; padding: 9px 20px;
    font-size: 13px; font-weight: 700; cursor: pointer;
    transition: background .15s;
}
.adm-modal .adm-btn-save:hover { background: #1d4ed8; }
.adm-modal .adm-btn-cancel {
    background: #f1f5f9; color: #64748b;
    border: 1px solid #e5e7eb; border-radius: 9px;
    padding: 9px 16px; font-size: 13px; font-weight: 600; cursor: pointer;
}
</style>

<div class="adm-page">

    {{-- ─── HEADER ─── --}}
    <div class="adm-header">
        <div class="adm-header-icon"><i class="bi bi-person-badge-fill"></i></div>
        <div>
            <div class="adm-header-title">Pengaturan Admin</div>
            <div class="adm-header-sub">Kelola akun admin sistem</div>
        </div>
    </div>

    {{-- ─── STATS ─── --}}
    <div class="adm-stats">
        <div class="adm-stat">
            <div class="adm-stat-label">Total Admin</div>
            <div class="adm-stat-val">{{ $admin->total() }}</div>
        </div>
        <div class="adm-stat">
            <div class="adm-stat-label">Bulan Ini</div>
            <div class="adm-stat-val">{{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}</div>
        </div>
        <div class="adm-stat">
            <div class="adm-stat-label">Status</div>
            <div class="adm-stat-val"><span class="adm-stat-dot"></span>Aktif</div>
        </div>
    </div>

    {{-- ─── MAIN CARD ─── --}}
    <div class="adm-card">

        <div class="adm-card-head">
            <div class="adm-card-title">Data Admin</div>
            <a href="javascript:void(0)" class="adm-btn-add" id="btn-tambahadmin">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </a>
        </div>

        {{--
            PERBAIKAN SEARCH:
            • action ke route admin yang benar
            • name="nama_admin" dipertahankan sesuai controller
            • Tombol Reset untuk hapus filter
        --}}
        <form action="{{ url('/admin') }}" method="GET" class="adm-search">
            <div class="adm-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text"
                       name="nama_admin"
                       placeholder="Cari nama atau email admin..."
                       value="{{ request('nama_admin') }}"
                       autocomplete="off">
            </div>
            <button type="submit" class="adm-search-btn">
                <i class="bi bi-search"></i> Cari
            </button>
            @if(request('nama_admin'))
            <a href="{{ url('/admin') }}" class="adm-reset-btn">
                <i class="bi bi-x-circle"></i> Reset
            </a>
            @endif
        </form>

        <div class="adm-table-wrap">
            <table class="adm-table">
                <thead>
                    <tr>
                        <th style="width:44px;">No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Tgl Dibuat</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admin as $d)
                    <tr>
                        <td style="color:#94a3b8;font-weight:700;font-size:12px;">
                            {{ $loop->iteration + $admin->firstItem() - 1 }}
                        </td>
                        <td>
                            <div class="adm-name-cell">
                                <div class="adm-avatar">{{ strtoupper(substr($d->name, 0, 2)) }}</div>
                                <span class="adm-uname">{{ $d->name }}</span>
                            </div>
                        </td>
                        <td style="color:#64748b;font-size:12px;">{{ $d->email }}</td>
                        <td style="color:#94a3b8;font-size:12px;white-space:nowrap;">
                            {{ $d->created_at ? \Carbon\Carbon::parse($d->created_at)->format('d M Y · H:i') : '—' }}
                        </td>
                        <td>
                            <div class="adm-act">
                                <a href="javascript:void(0)"
                                   class="adm-btn-edit edit"
                                   data-id="{{ $d->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="/admin/{{ $d->id }}/delete"
                                      method="POST"
                                      class="form-delete"
                                      style="margin:0;">
                                    @csrf
                                    <button type="button" class="adm-btn-del delete-confirm">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:#94a3b8;font-size:13px;">
                            <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                            Tidak ada data admin
                            @if(request('nama_admin'))
                                yang cocok dengan "<strong>{{ request('nama_admin') }}</strong>"
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="adm-pager">
            {{ $admin->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>{{-- end .adm-page --}}

{{-- ─── MODAL TAMBAH ─── --}}
<div class="modal fade adm-modal" id="modal-inputadmin"
     tabindex="-1" aria-hidden="true"
     data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-plus-fill"></i> Tambah Admin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="frmadmin">
                @csrf
                <div class="modal-body">
                    <div class="adm-field">
                        <div class="adm-field-label">Nama Lengkap</div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" id="name"
                                   class="form-control" placeholder="Nama lengkap" autocomplete="off">
                        </div>
                    </div>
                    <div class="adm-field">
                        <div class="adm-field-label">Email</div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email"
                                   class="form-control" placeholder="Alamat email" autocomplete="off">
                        </div>
                    </div>
                    <div class="adm-field">
                        <div class="adm-field-label">Password</div>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password"
                                   class="form-control" placeholder="Minimal 8 karakter">
                        </div>
                        <div class="adm-field-hint">Minimal 8 karakter</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="adm-btn-cancel" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="adm-btn-save">
                        <i class="bi bi-save2"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─── MODAL EDIT ─── --}}
<div class="modal fade adm-modal" id="modal-editadmin"
     tabindex="-1" aria-hidden="true"
     data-bs-backdrop="false" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i> Edit Admin
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="loadeditform"></div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var csrf       = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var bsAdd      = new bootstrap.Modal(document.getElementById('modal-inputadmin'));
    var bsEdit     = new bootstrap.Modal(document.getElementById('modal-editadmin'));

    /* ── Buka modal tambah ── */
    document.getElementById('btn-tambahadmin')?.addEventListener('click', function (e) {
        e.preventDefault();
        var frm = document.getElementById('frmadmin');
        frm?.reset();
        frm?.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
        bsAdd.show();
    });

    /* ── Edit ── */
    document.addEventListener('click', function (e) {
        var el = e.target.closest('.edit');
        if (!el) return;
        e.preventDefault();
        var id = el.getAttribute('data-id');
        var fd = new FormData();
        fd.append('_token', csrf);
        fd.append('id', id);
        fetch('/admin/edit', { method: 'POST', body: fd })
            .then(function (r) { if (!r.ok) throw new Error(); return r.text(); })
            .then(function (html) {
                document.getElementById('loadeditform').innerHTML = html;
                bsEdit.show();
            })
            .catch(function () {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat memuat data.' });
            });
    });

    /* ── Hapus ── */
    document.addEventListener('click', function (e) {
        var el = e.target.closest('.delete-confirm');
        if (!el) return;
        e.preventDefault();
        var form = el.closest('form');
        Swal.fire({
            title: 'Anda yakin?',
            text: 'Data yang dihapus tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#3b6ff0',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) form.submit();
        });
    });

    /* ── Submit tambah ── */
    var frmAdmin = document.getElementById('frmadmin');
    if (frmAdmin) {
        frmAdmin.addEventListener('submit', function (e) {
            e.preventDefault();
            var checks = [
                ['name',     'Nama wajib diisi.'],
                ['email',    'Email wajib diisi.'],
                ['password', 'Password wajib diisi.']
            ];
            for (var c of checks) {
                var field = frmAdmin.querySelector('[name="' + c[0] + '"]');
                if (!field?.value?.trim()) {
                    field.classList.add('is-invalid');
                    Swal.fire({ icon: 'warning', title: 'Belum lengkap', text: c[1] })
                        .then(function () { field.focus(); });
                    return;
                }
                field.classList.remove('is-invalid');
            }
            fetch('/admin/store', { method: 'POST', body: new FormData(frmAdmin) })
                .then(function (r) { if (!r.ok) throw new Error(); return r.json().catch(function () { return {}; }); })
                .then(function () {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Data admin berhasil disimpan.', timer: 2000, showConfirmButton: false });
                    bsAdd.hide();
                    frmAdmin.reset();
                    setTimeout(function () { location.reload(); }, 1500);
                })
                .catch(function () {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Terjadi kesalahan saat menyimpan.' });
                });
        });
    }

    /* ── Session alerts ── */
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil', text: {!! json_encode(session('success')) !!}, timer: 2200, showConfirmButton: false });
    @endif
    @if(session('warning'))
        Swal.fire({ icon: 'warning', title: 'Peringatan', text: {!! json_encode(session('warning')) !!}, timer: 2200, showConfirmButton: false });
    @endif
});
</script>
@endpush