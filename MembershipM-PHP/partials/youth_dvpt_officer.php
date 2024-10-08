<!-- Sidebar -->
<nav class="navbar navbar-vertical navbar-expand-xl navbar-light navbar-glass">
    <a class="navbar-brand text-left" href="../index-2.html">
        <!-- Logo and Text Section -->
        <div class="d-flex flex-column align-items-center py-2">
            <img src="../public/assets/img/merged_logos.png" width="60%" alt="Ligi Mashinani Logo" class="logo-img">
            <div class="info">
                <h5 class="text-center text-dark">
                    <b>
                        GoMC<br>
                        Makueni Family Management
                    </b>
                </h5>
            </div>
        </div>
    </a>

    <div class="navbar-collapse collapse show" id="navbarVerticalCollapse">
        <ul class="navbar-nav flex-column">
            <!-- Dashboard Home -->
            <li class="nav-item">
                <a class="nav-link dropdown-indicator d-flex align-items-center" href="#home" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="home">
                    <span class="nav-link-icon mr-2">
                        <i class="fas fa-tachometer-alt"></i>
                    </span>
                    <span>Dashboard Home</span>
                </a>
                <ul class="nav collapse" id="home" data-parent="#navbarVerticalCollapse">
                    <li class="nav-item">
                        <a class="nav-link" href="../views/dashboard.php">
                            <i class="fas fa-home"></i>
                            <span class="d-block">Dashboard Home</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Account Actions -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="../views/login.php">
                    <span class="nav-link-icon mr-2">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                    <span>Login</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="../views/edit_account.php">
                    <span class="nav-link-icon mr-2">
                        <i class="fas fa-user-edit"></i>
                    </span>
                    <span>Edit Account Details</span>
                </a>
            </li>

            <!-- Profile Management -->
            <li class="nav-item">
                <a class="nav-link dropdown-indicator d-flex align-items-center" href="#profileManagement" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="profileManagement">
                    <span class="nav-link-icon mr-2">
                        <i class="fas fa-users"></i>
                    </span>
                    <span>Profile Management</span>
                </a>
                <ul class="nav collapse" id="profileManagement" data-parent="#navbarVerticalCollapse">
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="../views/view_profiles.php">
                            <span class="nav-link-icon mr-2">
                                <i class="fas fa-eye"></i>
                            </span>
                            <span class="d-block">View Profiles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="../views/retrieve_lists.php">
                            <span class="nav-link-icon mr-2">
                                <i class="fas fa-list"></i>
                            </span>
                            <span class="d-block">Retrieve Lists</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Reports -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="../views/generate_reports.php">
                    <span class="nav-link-icon mr-2">
                        <i class="fas fa-file-alt"></i>
                    </span>
                    <span>Generate Reports</span>
                </a>
            </li>

            <!-- Export Data -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="../views/export_data.php">
                    <span class="nav-link-icon mr-2">
                        <i class="fas fa-file-export"></i>
                    </span>
                    <span>Export Data</span>
                </a>
            </li>

            <!-- Logout -->
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="../views/logout.php">
                    <span class="nav-link-icon mr-2">
                        <i class="fas fa-sign-out-alt"></i>
                    </span>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
<!-- End Sidebar -->
