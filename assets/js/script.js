document.addEventListener('DOMContentLoaded', function () {

    // Toggle Functionality
    const toggleLink = document.querySelector('.toc-master-toggle-link');
    const list = document.querySelector('.toc-master-list');

    if (toggleLink && list) {
        toggleLink.addEventListener('click', function (e) {
            e.preventDefault();
            if (list.style.display === 'none') {
                list.style.display = 'block';
                toggleLink.textContent = 'hide';
            } else {
                list.style.display = 'none';
                toggleLink.textContent = 'show';
            }
        });
    }

    // Smooth Scroll
    const links = document.querySelectorAll('.toc-master-link');

    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 20, // Offset
                    behavior: 'smooth'
                });

                // Update URL hash without jumping
                history.pushState(null, null, '#' + targetId);
            }
        });
    });

    // SpyScroll (Active Highlight)
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.5
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                // Remove active class from all
                links.forEach(link => link.classList.remove('active'));
                // Add to current
                const activeLink = document.querySelector(`.toc-master-link[href="#${id}"]`);
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }
        });
    }, observerOptions);

    // Observe all headings present in TOC
    links.forEach(link => {
        const targetId = link.getAttribute('href').substring(1);
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            observer.observe(targetElement);
        }
    });

});
