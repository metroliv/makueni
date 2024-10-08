<!-- Navbar -->
<nav class="main-header navbar navbar-expand-lg navbar-white navbar-light shadow-sm">
  <div class="container-fluid">
    <!-- Left Navbar links -->
    <ul class="navbar-nav me-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Home</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">About</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Services</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Contact</a>
      </li>
    </ul>

    <!-- Search Form -->
    <form class="d-none d-sm-inline-block form-inline ms-auto me-3">
      <div class="input-group">
        <input type="search" class="form-control border-light" placeholder="Search..." aria-label="Search">
        <button class="btn btn-outline-primary" type="submit">
          <i class="fas fa-search"></i>
        </button>
      </div>
    </form>

    <!-- Right Navbar links -->
    <ul class="navbar-nav ms-auto">
      <!-- Notifications Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="far fa-bell"></i>
          <span class="badge bg-danger rounded-pill">3</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
          <li><a class="dropdown-item" href="#">New message from John</a></li>
          <li><a class="dropdown-item" href="#">Server overload alert</a></li>
          <li><a class="dropdown-item" href="#">Software update available</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item text-center" href="#">View All Notifications</a></li>
        </ul>
      </li>

      <!-- User Dropdown -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="d-inline-block position-relative">
            <!-- User Avatar -->
            <img src="assets/img/avatars/victorm.jpg" alt="User Avatar" class="rounded-circle" width="30" height="30">
            <!-- Status Dot -->
            <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
              <span class="visually-hidden"></span>
            </span>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
          <li class="dropdown-item d-flex align-items-center">
            <div class="flex-shrink-0 me-3">
              <img src="assets/img/avatars/victorm.jpg" alt="User Avatar" class="rounded-circle" width="40" height="40">
            </div>
            <div class="flex-grow-1">
              <span class="fw-bold d-block">Victor Mulinge</span>
              <small class="text-muted">Admin</small>
            </div>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item" href="settings.php">
              <i class="fas fa-cog me-2"></i>
              <span class="align-middle">Settings</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="login.php">
              <i class="fas fa-sign-in-alt me-2"></i>
              <span class="align-middle">Login</span>
            </a>
          </li>
          <li>
            <a class="dropdown-item" href="logout.php">
              <i class="fas fa-sign-out-alt me-2"></i>
              <span class="align-middle">Logout</span>
            </a>
          </li>
        </ul>
      </li>
    </ul>
  </div>
</nav>

<!-- Include jQuery, Bootstrap JS, and necessary scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Search bar submit action
  $(document).ready(function() {
    $('form').on('submit', function(e) {
      e.preventDefault(); // Prevent default form submission behavior
      const query = $(this).find('input[type="search"]').val(); // Get the search input value
      if (query) {
        console.log('Search query:', query);
      }
    });
  });

  // Notifications Dropdown
  $('#notificationsDropdown').on('click', function() {
    $(this).find('.badge').remove(); // Remove notification badge after clicking
    console.log('Notifications opened');
  });

  // User Dropdown
  $('#userDropdown').on('click', function() {
    console.log('User dropdown opened');
  });
</script>
