document.addEventListener('DOMContentLoaded', function () {
    // ============================================
    // Tab Navigation
    // ============================================
    const tabButtons = document.querySelectorAll('.toc-tab-button');
    const tabPanes = document.querySelectorAll('.toc-tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function () {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Show corresponding tab pane
            const targetPane = document.querySelector(`[data-tab-content="${targetTab}"]`);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });

    // ============================================
    // Enhanced Live Preview with Dynamic Heading Selection
    // ============================================
    const previewContainer = document.querySelector('.tbrv-preview');

    if (previewContainer) {
        let tocContainer;
        let tocList;
        let toggleButton;
        let toggleLink;
        let isVisible = true;

        // Sample content structure with different heading levels
        const sampleContent = {
            h1: [{ text: 'Main Title (H1)', level: 1 }],
            h2: [
                { text: '1. Introduction (H2)', level: 2 },
                { text: '2. Main Content (H2)', level: 2 },
                { text: '3. Conclusion (H2)', level: 2 }
            ],
            h3: [
                { text: '1.1 Background (H3)', level: 3, parent: '1. Introduction (H2)' },
                { text: '1.2 Purpose (H3)', level: 3, parent: '1. Introduction (H2)' },
                { text: '2.1 Features (H3)', level: 3, parent: '2. Main Content (H2)' },
                { text: '2.2 Benefits (H3)', level: 3, parent: '2. Main Content (H2)' }
            ],
            h4: [
                { text: '1.1.1 History (H4)', level: 4, parent: '1.1 Background (H3)' },
                { text: '2.1.1 Key Features (H4)', level: 4, parent: '2.1 Features (H3)' }
            ],
            h5: [
                { text: '2.1.1.1 Feature Details (H5)', level: 5, parent: '2.1.1 Key Features (H4)' }
            ],
            h6: [
                { text: '2.1.1.1.1 Specifics (H6)', level: 6, parent: '2.1.1.1 Feature Details (H5)' }
            ]
        };

        // Function to generate TOC HTML based on selected headings
        function generateTOCPreview() {
            // Get selected headings
            const headingCheckboxes = document.querySelectorAll('.toc-heading-option input[type="checkbox"]');
            const selectedHeadings = Array.from(headingCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);

            if (selectedHeadings.length === 0) {
                return '<li style="color: #64748B; font-style: italic;">No headings selected</li>';
            }

            // Build hierarchical structure
            let html = '';
            let lastLevel = 0;

            selectedHeadings.sort((a, b) => {
                const levelA = parseInt(a.replace('h', ''));
                const levelB = parseInt(b.replace('h', ''));
                return levelA - levelB;
            });

            selectedHeadings.forEach((heading, index) => {
                const level = parseInt(heading.replace('h', ''));
                const items = sampleContent[heading] || [];

                items.forEach((item, itemIndex) => {
                    const indent = (level - Math.min(...selectedHeadings.map(h => parseInt(h.replace('h', ''))))) * 1.25;

                    if (itemIndex === 0 || index === 0 || level <= lastLevel) {
                        html += `<li style="margin-left: ${indent}rem;"><a href="#" class="tbrv-link">${item.text}</a></li>`;
                    } else {
                        html += `<li style="margin-left: ${indent}rem;"><a href="#" class="tbrv-link">${item.text}</a></li>`;
                    }
                });

                lastLevel = level;
            });

            return html || '<li style="color: #64748B; font-style: italic;">No content to display</li>';
        }

        // Function to update the preview
        function updatePreview() {
            if (!tocContainer) {
                // Create initial structure
                tocContainer = document.createElement('div');
                tocContainer.className = 'tbrv-container';
                tocContainer.innerHTML = `
                    <div class="tbrv-header">
                        <span class="tbrv-title">Table of Contents</span>
                        <span class="tbrv-toggle">[<a href="#" class="tbrv-toggle-link">hide</a>]</span>
                    </div>
                    <ul class="tbrv-list"></ul>
                `;
                previewContainer.appendChild(tocContainer);

                tocList = tocContainer.querySelector('.tbrv-list');
                toggleButton = tocContainer.querySelector('.tbrv-toggle-link');
                toggleLink = tocContainer.querySelector('.tbrv-toggle');

                // Add toggle functionality
                if (toggleButton && tocList) {
                    toggleButton.addEventListener('click', function (e) {
                        e.preventDefault();
                        if (isVisible) {
                            tocList.style.display = 'none';
                            toggleButton.textContent = 'show';
                            isVisible = false;
                        } else {
                            tocList.style.display = 'block';
                            toggleButton.textContent = 'hide';
                            isVisible = true;
                        }
                    });
                }
            }

            // Update TOC content
            if (tocList) {
                tocList.innerHTML = generateTOCPreview();

                // Prevent default link behavior
                const links = tocList.querySelectorAll('a');
                links.forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                    });
                });
            }

            // Update collapsible visibility
            updateCollapsibleDisplay();
        }

        // Update preview based on collapsible setting
        function updateCollapsibleDisplay() {
            const collapsibleCheckbox = document.querySelector('input[name="tbrv_options[collapsible]"]');
            if (collapsibleCheckbox && toggleLink) {
                if (collapsibleCheckbox.checked) {
                    toggleLink.style.display = 'inline';
                } else {
                    toggleLink.style.display = 'none';
                }
            }
        }

        // Listen for heading checkbox changes
        const headingCheckboxes = document.querySelectorAll('.toc-heading-option input[type="checkbox"]');
        headingCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updatePreview);
        });

        // Listen for collapsible checkbox changes
        const collapsibleCheckbox = document.querySelector('input[name="tbrv_options[collapsible]"]');
        if (collapsibleCheckbox) {
            collapsibleCheckbox.addEventListener('change', updateCollapsibleDisplay);
        }

        // Initialize preview
        updatePreview();
    }

    // ============================================
    // Enhanced Form Interactions
    // ============================================

    // Add smooth transitions to checkbox wrappers
    const checkboxWrappers = document.querySelectorAll('.toc-checkbox-wrapper');
    checkboxWrappers.forEach(wrapper => {
        const checkbox = wrapper.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    wrapper.style.background = 'linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.1))';
                } else {
                    wrapper.style.background = '';
                }
            });

            // Initialize state
            if (checkbox.checked) {
                wrapper.style.background = 'linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.1))';
            }
        }
    });

    // Add visual feedback for heading selections
    const headingOptions = document.querySelectorAll('.toc-heading-option');
    headingOptions.forEach(option => {
        const checkbox = option.querySelector('input[type="checkbox"]');
        if (checkbox) {
            checkbox.addEventListener('change', function () {
                if (this.checked) {
                    option.style.background = 'linear-gradient(135deg, rgba(79, 70, 229, 0.15), rgba(124, 58, 237, 0.15))';
                    option.style.borderColor = '#4F46E5';
                } else {
                    option.style.background = '';
                    option.style.borderColor = '';
                }
            });

            // Initialize state
            if (checkbox.checked) {
                option.style.background = 'linear-gradient(135deg, rgba(79, 70, 229, 0.15), rgba(124, 58, 237, 0.15))';
                option.style.borderColor = '#4F46E5';
            }
        }
    });

    // ============================================
    // Success Message Animation
    // ============================================
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('settings-updated') === 'true') {
        const firstCard = document.querySelector('.toc-settings-card');
        if (firstCard) {
            const successMessage = document.createElement('div');
            successMessage.className = 'toc-success-message';
            successMessage.innerHTML = '✅ <strong>Settings saved successfully!</strong>';
            successMessage.style.opacity = '0';
            successMessage.style.transition = 'opacity 0.3s ease-in-out';

            firstCard.parentNode.insertBefore(successMessage, firstCard);

            // Fade in
            setTimeout(() => {
                successMessage.style.opacity = '1';
            }, 100);

            // Fade out and remove after 5 seconds
            setTimeout(() => {
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.remove();
                }, 300);
            }, 5000);
        }
    }

    // ============================================
    // Smooth Scroll for Settings Page
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
