<script>
document.addEventListener('DOMContentLoaded', function () {

    const sidebar = document.getElementById('app-sidebar');

    const toggleButton =
        document.getElementById('sidebar-toggle');

    const overlay =
        document.getElementById('sidebar-overlay');


    if (!sidebar || !toggleButton || !overlay) {
        return;
    }


    function openSidebar() {

        sidebar.classList.add('sidebar-open');

        overlay.classList.add('show');

    }


    function closeSidebar() {

        sidebar.classList.remove('sidebar-open');

        overlay.classList.remove('show');

    }


    toggleButton.addEventListener('click', function () {

        if (sidebar.classList.contains('sidebar-open')) {

            closeSidebar();

        } else {

            openSidebar();

        }

    });


    overlay.addEventListener('click', function () {

        closeSidebar();

    });


    /* Tutup sidebar setelah klik menu di HP */

    const sidebarLinks =
        sidebar.querySelectorAll('.sidebar-link');


    sidebarLinks.forEach(function (link) {

        link.addEventListener('click', function () {

            if (window.innerWidth <= 767.98) {

                closeSidebar();

            }

        });

    });


    /* Reset ketika kembali ke desktop */

    window.addEventListener('resize', function () {

        if (window.innerWidth > 767.98) {

            closeSidebar();

        }

    });

});
</script>

@stack('scripts')