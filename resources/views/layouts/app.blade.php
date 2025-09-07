<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DebtCollect Pro | Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #ffffffff;
            color: #141415ff;
            font-family: 'Inter', sans-serif;
        }

        /* Sidebar Dark Theme */
.sidebar {
    width: 260px;
    background: #0f172a;   /* deep navy */
    height: 100vh;
    position: fixed;
    overflow-y: auto;
    box-shadow: 2px 0 6px rgba(0,0,0,0.2);
    color: #e2e8f0; /* light gray text */
    font-family: 'Inter', sans-serif;
}

/* Logo */
.sidebar .logo-container {
    padding: 16px;
    border-bottom: 1px solid #1e293b;
    text-align: center;
}

/* Section Titles (MAIN NAVIGATION / MANAGEMENT) */
.sidebar h4 {
    font-size: 11px;
    font-weight: 600;
    color: #64748b; /* grayish text */
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin: 20px 16px 8px;
}

/* Menu Items */
.sidebar .menu-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    color: #cbd5e1; /* light slate */
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    margin: 2px 8px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.sidebar .menu-item i {
    font-size: 16px;
}

/* Hover */
.sidebar .menu-item:hover {
    background: #1e293b; /* slightly lighter navy */
    color: #ffffff;
}

/* Active item */
.sidebar .menu-item.active {
    background: #334155; /* blue-gray highlight */
    color: #ffffff;
    font-weight: 600;
}

/* Sub-menu */
.sub-menu {
    margin-left: 10px;
    padding-left: 10px;
    border-left: 2px solid #334155;
    display: none;
}

.sub-menu.show {
    display: block;
}

.sub-menu .sub-menu-item {
    display: block;
    padding: 8px 20px;
    font-size: 13px;
    color: #94a3b8;
    border-radius: 4px;
    text-decoration: none;
}

.sub-menu .sub-menu-item:hover {
    background: #1e293b;
    color: #ffffff;
}

/* Active sub-menu item */
.sub-menu .sub-menu-item.active {
    background: #334155;
    color: #ffffff;
    font-weight: 600;
}


        /* Active sub menu item → bold only */
         
        /* Main content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
        }

        .header {
            height: 70px;
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 20px;
        }

        .dashboard {
            padding: 20px;
            flex: 1;
        }

        .user-menu {
            display: flex;
            align-items: center;
        }

        .user-info {
            margin-right: 15px;
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 15px;
        }

        .user-role {
            font-size: 13px;
            color: #95979cff;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #2563eb;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }
        /* Active item = light gray bg */
.sidebar .menu-item.active,
.sidebar .sub-menu-item.active {
    background: #d8dce6ff;   /* Tailwind gray-100 */
    color: #111827;        /* dark text */
    font-weight: 700;
}

/* Hover state */
.sidebar .menu-item:hover,  
.sidebar .sub-menu-item:hover {
    background: #f9fafb;   /* lighter gray */
    color: #111827;
}

    </style>
    @yield('css')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('assets/media/logos/Collexa.png') }}" alt="Logo" style="max-width:200px;">
        </div>

        <div class="sidebar-menu">

            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i>
                <span class="menu-text">Dashboard</span>
            </a>

            <!-- Assignment Module -->
          <div class="menu-item {{ request()->routeIs('assignments.*') ? 'active' : '' }}" 
     onclick="toggleSubMenu('assignment-submenu', this)">
    <span class="menu-text">Assignment Management</span>
    <i class="fas {{ request()->routeIs('assignments.*') ? 'fa-chevron-up' : 'fa-chevron-down' }} arrow"></i>
</div>
            <div id="assignment-submenu" class="sub-menu {{ request()->routeIs('assignments.*') ? 'show' : '' }}">
                <a href="{{ route('assignments.index') }}" 
                   class="sub-menu-item {{ request()->routeIs('assignments.index') ? 'active' : '' }}">
                   All cases
                </a>
                <a href="{{ route('assignments.create') }}" 
                   class="sub-menu-item {{ request()->routeIs('assignments.create') ? 'active' : '' }}">
                   Case Creation
                </a>
                <a href="{{ route('assignments.assign.form') }}" 
                   class="sub-menu-item {{ request()->routeIs('assignments.assign.form') ? 'active' : '' }}">
                   Assignment Engine
                </a>
                <a href="{{ route('assignments.status') }}" 
                   class="sub-menu-item {{ request()->routeIs('assignments.status') ? 'active' : '' }}">
                   Case Status Monitoring
                </a>
                   <a href="{{ route('assignments.priority_due_date') }}" 
                   class="sub-menu-item {{ request()->routeIs('assignments.priority_due_date') ? 'active' : '' }}">
                   Priority & due date management
                </a>
            </div>

            <!-- Other Menu Items -->
            <a href="#" class="menu-item">
                
                <span class="menu-text">VC Dial</span>
            </a>

            <a href="#" class="menu-item">
                
                <span class="menu-text">Campaign & Communication Engine</span>
            </a>

            <a href="#" class="menu-item">
               
                <span class="menu-text">Negotiation & Proposal Handling</span>
            </a>

            <a href="#" class="menu-item">
       
                <span class="menu-text">PTP & Bank Updates</span>
            </a>

            <a href="#" class="menu-item">
   
                <span class="menu-text">Invoicing & Reporting</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
         
        <div class="header">
            <!-- Top Right Toolbar -->
            <div style="display:flex;align-items:center;gap:12px;">
                <!-- Language Dropdown -->
                <select style="padding:6px 10px;border:1px solid #d1d5db;border-radius:8px;font-size:14px;cursor:pointer;">
                    <option>Eng</option>
                    <option>CHINESE</option>
                    <option>MANDRIAN</option>
                </select>
 
            
<!-- Calendar Button -->
 <div id="calendarWrapper" style="display:inline-block; position:relative;">
  <button id="calendarButton"
          style="background-color:#66d3ce;
                 color:#fff;
                 font-weight:500;
                 border:none;
                 border-radius:8px;
                 font-size:14px;
                 padding:8px 14px;
                 min-width:200px;
                 cursor:pointer;
                 display:flex;
                 align-items:center;
                 justify-content:center;
                 gap:8px;">
    <i class="fas fa-calendar-alt"></i>
    <span id="calendarLabel">Select Date Range</span>
  </button>
</div>


 






        
        </div>
       

            <div class="user-menu">
                <div class="user-info">
                    <div class="user-name" style="margin-left:12px;color:gray;">
                        {{ session('user_name', 'Guest User') }}
                    </div>
                    <div class="user-role">Admin</div>
                </div>
                <div class="user-avatar">
                    {{ strtoupper(substr(session('user_name', 'G'),0,1)) }}
                </div>
            </div>
        </div>

        <!-- Dashboard Content -->
        <div class="dashboard">
            @yield('content')
        </div>
    </div>

    <script>
        function toggleSubMenu(id, element) {
            const submenu = document.getElementById(id);
            const arrow = element.querySelector('.arrow');

            submenu.classList.toggle('show');
            arrow.classList.toggle('fa-chevron-up');
            arrow.classList.toggle('fa-chevron-down');
        }
    </script>
 <script>
  document.addEventListener("DOMContentLoaded", function() {
  const calendarLabel = document.getElementById('calendarLabel');

  // Attach directly to wrapper instead of hidden input
  const fp = flatpickr("#calendarWrapper", {
    mode: "range",
    dateFormat: "d-m-Y",
    position: "below",  // 👈 Now works, because wrapper is visible
    clickOpens: false,  // Prevents auto open
    onClose: function(selectedDates, dateStr) {
      if (dateStr) {
        calendarLabel.textContent = dateStr;
      }
    }
  });

  // Open when button clicked
  document.getElementById('calendarButton').addEventListener('click', function() {
    fp.open();
  });
});

</script>



     
 
  
    @yield('js')
</body>
</html>
