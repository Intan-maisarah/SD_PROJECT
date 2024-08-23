document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    const indicator = document.querySelector('.nav-indicator');
    const navbarHeight = document.querySelector('.navbar').offsetHeight;

    function updateIndicatorPosition(el) {
        const navWidth = el.offsetWidth;
        const navLeft = el.offsetLeft;
        indicator.style.width = `${navWidth}px`;
        indicator.style.left = `${navLeft}px`;
    }

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            navLinks.forEach(link => link.classList.remove('active'));
            link.classList.add('active');
            updateIndicatorPosition(link);

            const section = document.querySelector(link.getAttribute('href'));
            const sectionTop = section.getBoundingClientRect().top + window.scrollY - navbarHeight;

            window.scrollTo({
                top: sectionTop,
                behavior: 'smooth'
            });
        });
    });

    window.addEventListener('scroll', () => {
        const scrollPosition = window.scrollY + navbarHeight;

        navLinks.forEach(link => {
            const section = document.querySelector(link.getAttribute('href'));
            const sectionTop = section.getBoundingClientRect().top + window.scrollY - navbarHeight;
            const sectionHeight = section.offsetHeight;

            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                navLinks.forEach(link => link.classList.remove('active'));
                link.classList.add('active');
                updateIndicatorPosition(link);
            }
        });
    });

    // Initialize indicator position on page load
    const activeLink = document.querySelector('.nav-link.active');
    if (activeLink) {
        updateIndicatorPosition(activeLink);
    } else {
        updateIndicatorPosition(navLinks[0]); // Initialize on first link if none active
    }
});
