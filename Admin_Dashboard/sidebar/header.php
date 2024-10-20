<!-- ============================================================== -->
<!-- Topbar header -->
<!-- ============================================================== -->
<header class="topbar" data-navbarbg="skin5">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header" data-logobg="skin5">
            <a class="navbar-brand" href="admin_page.php">
                <b class="logo-icon">
                    <img src="../../assets/images/logo.png" alt="homepage" style="width: 60px; height: auto;" />
                </b>
            </a>
        </div>
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <button id="sidebarToggle" class="navbar-toggler" type="button" aria-label="Toggle Sidebar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.left-sidebar').classList.toggle('collapsed');
        });
    });
</script>
