<script>
        const body = document.getElementById('body');
        const toggleBtn = document.getElementById('toggleBtn');
        const overlay = document.getElementById('overlay');
        const profileBtn = document.getElementById('profileBtn');
        const profileDropdown = document.getElementById('profileDropdown');

        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (window.innerWidth > 991) body.classList.toggle('sidebar-hidden');
            else body.classList.toggle('sidebar-open');
        });

        overlay.addEventListener('click', () => body.classList.remove('sidebar-open'));
        profileBtn.addEventListener('click', (e) => { e.stopPropagation(); profileDropdown.classList.toggle('active'); });
        window.addEventListener('click', () => profileDropdown.classList.remove('active'));

        const navLinks = document.querySelectorAll('.nav-link');
        const subLinks = document.querySelectorAll('.sub-link');

        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(item => item.classList.remove('active-nav'));
                subLinks.forEach(item => item.classList.remove('active-sub'));
                this.classList.add('active-nav');
                if (this.classList.contains('has-dropdown')) this.parentElement.classList.toggle('open');
            });
        });

        subLinks.forEach(link => {
            link.addEventListener('click', function() {
                subLinks.forEach(item => item.classList.remove('active-sub'));
                this.classList.add('active-sub');
                this.closest('.nav-item').querySelector('.nav-link').classList.add('active-nav');
            });
        });
    </script>
</body>
</html>