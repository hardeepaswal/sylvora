document.addEventListener('DOMContentLoaded', function () {

      // Show the floating Back to top link after the visitor passes halfway down.
      const backToTop = document.getElementById('back-to-top');
      if (backToTop) {
        function updateBackToTopVisibility() {
          const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
          backToTop.classList.toggle('hidden', maxScroll <= 0 || window.scrollY < maxScroll / 4);
        }

        window.addEventListener('scroll', updateBackToTopVisibility, { passive: true });
        window.addEventListener('resize', updateBackToTopVisibility);
        updateBackToTopVisibility();
      }
      
      // 1. Mobile Menu Toggle
      const mobileToggle = document.getElementById('mobile-menu-toggle');
      const mobileMenu = document.getElementById('mobile-menu');
      const hamburgerIcon = document.getElementById('hamburger-icon');
      const closeIcon = document.getElementById('close-icon');

      if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function () {
          const isOpen = !mobileMenu.classList.contains('hidden');
          if (isOpen) {
            mobileMenu.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
          } else {
            mobileMenu.classList.remove('hidden');
            hamburgerIcon.classList.add('hidden');
            closeIcon.classList.remove('hidden');
          }
        });

        // Close mobile menu on clicking any link
        document.querySelectorAll('.mobile-nav-link').forEach(link => {
          link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            hamburgerIcon.classList.remove('hidden');
            closeIcon.classList.add('hidden');
          });
        });
      }

      // 2. Book a consultation CTA scroll / trigger
      const headerConsultBtn = document.getElementById('header-consult-btn');
      const mobileConsultBtn = document.getElementById('mobile-consult-btn');
      
      function scrollToContact() {
        const contactSection = document.getElementById('contact');
        if (contactSection) {
          contactSection.scrollIntoView({ behavior: 'smooth' });
          const nameInput = document.getElementById('client-name');
          if (nameInput) {
            setTimeout(() => nameInput.focus(), 600);
          }
        }
      }

      if (headerConsultBtn) headerConsultBtn.addEventListener('click', scrollToContact);
      if (mobileConsultBtn) mobileConsultBtn.addEventListener('click', () => {
        if (mobileMenu) {
          mobileMenu.classList.add('hidden');
          hamburgerIcon.classList.remove('hidden');
          closeIcon.classList.add('hidden');
        }
        scrollToContact();
      });

      // 3. Property Category Filter Tabs
      const filterTabs = document.querySelectorAll('.filter-tab');
      const propertyCards = document.querySelectorAll('.property-card');

      filterTabs.forEach(tab => {
        tab.addEventListener('click', function () {
          const filter = this.getAttribute('data-filter');

          // Update active tab styles
          filterTabs.forEach(t => {
            t.classList.remove('bg-brand-forest', 'text-brand-ivory');
            t.classList.add('text-brand-forestDeep');
          });
          this.classList.remove('text-brand-forestDeep');
          this.classList.add('bg-brand-forest', 'text-brand-ivory');

          // Filter cards
          propertyCards.forEach(card => {
            const cat = card.getAttribute('data-category');
            if (filter === 'all' || cat === filter) {
              card.style.display = 'flex';
            } else {
              card.style.display = 'none';
            }
          });
        });
      });

      // 4. Property Details Modal
      const modal = document.getElementById('property-modal');
      const closeModalBtn = document.getElementById('close-modal-btn');
      const modalCancelBtn = document.getElementById('modal-cancel-btn');
      const modalInquireBtn = document.getElementById('modal-inquire-btn');
      const modalImg = document.getElementById('modal-img');
      const modalTitle = document.getElementById('modal-title');
      const modalLocation = document.getElementById('modal-location');
      const modalSpecs = document.getElementById('modal-specs');
      const modalPrice = document.getElementById('modal-price');

      function openModal(card) {
        const cImg = card.querySelector('img'); if (cImg) modalImg.src = cImg.src;
        modalTitle.textContent = card.getAttribute('data-name');
        modalLocation.innerHTML = `<svg class="w-3.5 h-3.5 mr-1 text-brand-bronze" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path></svg>${card.getAttribute('data-loc')}`;
        modalSpecs.textContent = card.getAttribute('data-specs');
        modalPrice.textContent = card.getAttribute('data-price');
        modal.classList.remove('hidden');
      }

      function closeModal() {
        modal.classList.add('hidden');
      }

      document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
          e.stopPropagation();
          const card = this.closest('.property-card');
          if (card) openModal(card);
        });
      });

      if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
      if (modalCancelBtn) modalCancelBtn.addEventListener('click', closeModal);
      if (modal) {
        modal.addEventListener('click', function (e) {
          if (e.target === modal) closeModal();
        });
      }

      if (modalInquireBtn) {
        modalInquireBtn.addEventListener('click', function () {
          const propName = modalTitle.textContent;
          closeModal();
          scrollToContact();
          const messageField = document.getElementById('client-message');
          if (messageField) {
            messageField.value = `I am interested in scheduling a viewing for: ${propName}.`;
          }
        });
      }

      // 5. Interactive Intent Button Selection
      const intentButtons = document.querySelectorAll('.intent-btn');
      const hiddenIntent = document.getElementById('selected-intent');

      intentButtons.forEach(btn => {
        btn.addEventListener('click', function () {
          const val = this.getAttribute('data-intent');
          hiddenIntent.value = val;

          intentButtons.forEach(b => {
            b.classList.remove('bg-brand-forest', 'text-brand-ivory', 'border-brand-forest');
            b.classList.add('bg-white', 'text-brand-forestDeep', 'border-brand-sageMuted');
          });

          this.classList.remove('bg-white', 'text-brand-forestDeep', 'border-brand-sageMuted');
          this.classList.add('bg-brand-forest', 'text-brand-ivory', 'border-brand-forest');
        });
      });

      // Global helper to select service intent from "What We Do" cards
      window.selectServiceIntent = function (serviceName) {
        scrollToContact();
        const targetBtn = Array.from(intentButtons).find(b => b.getAttribute('data-intent') === serviceName);
        if (targetBtn) {
          targetBtn.click();
        }
      };

      // 6. Interactive Form Submission
      const form = document.getElementById('consultation-form');
      const successBanner = document.getElementById('form-success-banner');
      const confirmName = document.getElementById('confirm-name');
      const confirmContact = document.getElementById('confirm-contact');
      const resetBtn = document.getElementById('reset-form-btn');

      if (form) {
        form.addEventListener('submit', function (e) {
          e.preventDefault();
          const nameVal = document.getElementById('client-name').value.trim();
          const contactVal = document.getElementById('client-contact').value.trim();

          if (!nameVal || !contactVal) return;

          confirmName.textContent = nameVal;
          confirmContact.textContent = contactVal;

          form.classList.add('hidden');
          successBanner.classList.remove('hidden');
        });
      }

      if (resetBtn) {
        resetBtn.addEventListener('click', function () {
          form.reset();
          // reset intent pill
          const firstIntent = intentButtons[0];
          if (firstIntent) firstIntent.click();

          form.classList.remove('hidden');
          successBanner.classList.add('hidden');
        });
      }

    });
