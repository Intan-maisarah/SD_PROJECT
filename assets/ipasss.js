document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('nav a');
    const marker = document.querySelector('#marker');
    const navbarHeight = document.querySelector('.navbar').offsetHeight;

    function indicator(el) {
        marker.style.left = `${el.offsetLeft}px`;
        marker.style.width = `${el.offsetWidth}px`;
    }

    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            navLinks.forEach(link => link.classList.remove('active'));
            link.classList.add('active');
            indicator(link);

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
                indicator(link);
            }
        });
    });

    const activeLink = document.querySelector('nav a.active');
    if (activeLink) {
        indicator(activeLink);
    } else {
        indicator(navLinks[0]); 
    }
});
