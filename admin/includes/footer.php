        </main>
    </div><!-- end main wrapper -->
    </div><!-- end flex -->

    <script>
        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.getElementById('adminSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        // Auto-dismiss alerts after 5 seconds
        document.querySelectorAll('.alert-success, .alert-error').forEach(el => {
            setTimeout(() => {
                el.style.transition = 'opacity 0.3s, transform 0.3s';
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 300);
            }, 5000);
        });
    </script>
</body>
</html>
<?php ob_end_flush(); ?>
