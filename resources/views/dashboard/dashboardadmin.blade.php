@extends('layout.admin.template')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="container-fluid">
  
  <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
    <div>
      <div class ="col">
        <h3 class="fw-bold mb-4">Dashboard</h3>
      </div>
    </div>
  </div>
   
</div>

<div class="page-body">
  <div class="row row-cols-1 row-cols-md-3 row-cols-lg-5 g-3">
    
    <!-- Card 1 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-success text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-fingerprint">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3" />
              <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6" />
              <path d="M12 11v2a14 14 0 0 0 2.5 8" />
              <path d="M8 15a18 18 0 0 0 1.8 6" />
              <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $jmlkaryawan }}</div>
          <div class="text-secondary">Jumlah Karyawan</div>
        </div>
      </div>
    </div>

    <!-- Card 2 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-primary text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-users">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
              <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
              <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekappresensi -> jmlhadir ?? 0  }}</div>
          <div class="text-secondary">Karyawan Hadir</div>
        </div>
      </div>
    </div>

    <!-- Card 3 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-warning text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-file-text">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M14 3v4a1 1 0 0 0 1 1h4" />
              <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
              <path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekapizin->jmlizin ?? 0 }}</div>
          <div class="text-secondary">Karyawan Izin</div>
        </div>
      </div>
    </div>

    <!-- Card 4 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-danger text-white avatar d-flex align-items-center justify-content-center mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                 fill="none" stroke="currentColor" stroke-width="2"
                 stroke-linecap="round" stroke-linejoin="round"
                 class="icon icon-tabler icon-tabler-mood-sick">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z" />
              <path d="M9 10h-.01" /><path d="M15 10h-.01" />
              <path d="M8 16l1 -1l1.5 1l1.5 -1l1.5 1l1.5 -1l1 1" />
            </svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekapizin->jmlsakit ?? 0 }}</div>
          <div class="text-secondary">Karyawan Sakit</div>
        </div>
      </div>
    </div>

    <!-- Card 5 -->
    <div class="col">
      <div class="card card-sm">
        <div class="card-body text-center">
          <span class="bg-dark text-white avatar d-flex align-items-center justify-content-center mx-auto">
           <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-alarm"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 13m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M12 10l0 3l2 0" /><path d="M7 4l-2.75 2" /><path d="M17 4l2.75 2" /></svg>
          </span>
          <div class="font-weight-medium mt-2">{{ $rekappresensi -> jmlhadir ?? 0}}</div>
          <div class="text-secondary">Karyawan Terlambat</div>
        </div>
      </div>
    </div>

  </div>

  <!-- Kalender Section -->
  <div class="row mt-4">
    <!-- Digital Clock & Date - Order 1 untuk mobile, 2 untuk desktop -->
    <div class="col-12 col-md-6 order-1 order-md-2 mb-3 mb-md-0">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">Waktu Sekarang</h4>
        </div>
        <div class="card-body text-center py-4 py-md-5">
          <div class="digital-clock mb-3">
            <h1 class="fw-bold mb-0" id="digitalClock" style="font-size: clamp(2rem, 8vw, 4rem);">00:00:00</h1>
          </div>
          <div class="digital-date">
            <h4 class="text-muted mb-0" id="digitalDate" style="font-size: clamp(1rem, 3vw, 1.5rem);"></h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Kalender - Order 2 untuk mobile, 1 untuk desktop -->
    <div class="col-12 col-md-6 order-2 order-md-1">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title mb-0">Kalender</h4>
        </div>
        <div class="card-body">
          <div id="calendar-container">
            <div class="calendar-header d-flex justify-content-between align-items-center mb-3">
              <button class="btn btn-sm btn-outline-primary" id="prevMonth">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
              </button>
              <h5 class="mb-0" id="currentMonth"></h5>
              <button class="btn btn-sm btn-outline-primary" id="nextMonth">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
              </button>
            </div>
            <div class="table-responsive">
              <table class="table table-bordered text-center" id="calendar-table">
                <thead>
                  <tr>
                    <th class="text-danger">Min</th>
                    <th>Sen</th>
                    <th>Sel</th>
                    <th>Rab</th>
                    <th>Kam</th>
                    <th>Jum</th>
                    <th class="text-primary">Sab</th>
                  </tr>
                </thead>
                <tbody id="calendar-body">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
#calendar-table td {
  padding: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
}

#calendar-table td:hover {
  background-color: #f0f0f0;
}

#calendar-table td.today {
  background-color: #0d6efd;
  color: white;
  font-weight: bold;
}

#calendar-table td.other-month {
  color: #ccc;
}

#calendar-table td.selected {
  background-color: #198754;
  color: white;
}

.avatar {
  width: 48px;
  height: 48px;
  border-radius: 50%;
}

#digitalClock {
  font-family: 'Courier New', monospace;
  letter-spacing: 0.1em;
}
</style>

<script>
let currentDate = new Date();

function renderCalendar() {
  const year = currentDate.getFullYear();
  const month = currentDate.getMonth();
  
  // Update header
  const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
  ];
  document.getElementById('currentMonth').textContent = `${monthNames[month]} ${year}`;
  
  // Get first day of month and number of days
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const daysInPrevMonth = new Date(year, month, 0).getDate();
  
  let calendarHTML = '';
  let day = 1;
  let nextMonthDay = 1;
  
  // Create 6 rows for calendar
  for (let i = 0; i < 6; i++) {
    calendarHTML += '<tr>';
    
    // Create 7 columns for each day of week
    for (let j = 0; j < 7; j++) {
      if (i === 0 && j < firstDay) {
        // Previous month days
        const prevDay = daysInPrevMonth - firstDay + j + 1;
        calendarHTML += `<td class="other-month">${prevDay}</td>`;
      } else if (day > daysInMonth) {
        // Next month days
        calendarHTML += `<td class="other-month">${nextMonthDay}</td>`;
        nextMonthDay++;
      } else {
        // Current month days
        const today = new Date();
        const isToday = day === today.getDate() && 
                       month === today.getMonth() && 
                       year === today.getFullYear();
        
        calendarHTML += `<td class="${isToday ? 'today' : ''}">${day}</td>`;
        day++;
      }
    }
    
    calendarHTML += '</tr>';
    
    // Break if we've filled all days
    if (day > daysInMonth && nextMonthDay > 7) break;
  }
  
  document.getElementById('calendar-body').innerHTML = calendarHTML;
}

function updateDigitalClock() {
  const now = new Date();
  
  // Update time
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');
  document.getElementById('digitalClock').textContent = `${hours}:${minutes}:${seconds}`;
  
  // Update date
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  
  const dayName = days[now.getDay()];
  const date = now.getDate();
  const monthName = months[now.getMonth()];
  const year = now.getFullYear();
  
  document.getElementById('digitalDate').textContent = 
    `${dayName}, ${date} ${monthName} ${year}`;
}

// Event listeners
document.getElementById('prevMonth').addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() - 1);
  renderCalendar();
});

document.getElementById('nextMonth').addEventListener('click', () => {
  currentDate.setMonth(currentDate.getMonth() + 1);
  renderCalendar();
});

// Initialize
renderCalendar();
updateDigitalClock();

// Update clock every second
setInterval(updateDigitalClock, 1000);
</script>

@endsection