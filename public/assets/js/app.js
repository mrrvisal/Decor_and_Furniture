/**
 * Decor & Furniture - Frontend JS
 * Mobile menu, add-to-cart feedback, landing animation
 */
(function () {
  "use strict";

  console.log("Decor JS loaded - validation enabled");

  // Mobile nav toggle
  var toggle = document.getElementById("nav-toggle");
  var nav = document.getElementById("nav-main");
  if (toggle && nav) {
    toggle.addEventListener("click", function () {
      nav.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", nav.classList.contains("is-open"));
      toggle.textContent = nav.classList.contains("is-open") ? "✕" : "☰";
    });
    document.addEventListener("click", function (e) {
      if (
        nav.classList.contains("is-open") &&
        !nav.contains(e.target) &&
        !toggle.contains(e.target)
      ) {
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
        toggle.textContent = "☰";
      }
    });
  }

  // Landing hero animation (already in CSS, ensure class)
  var hero = document.getElementById("landing-hero");
  if (hero) hero.classList.add("animated");

  // Add-to-cart form: show loading state, optional toast
  document.querySelectorAll(".add-to-cart-form").forEach(function (form) {
    form.addEventListener("submit", function () {
      var btn = form.querySelector('button[type="submit"]');
      if (btn && !btn.disabled) {
        var orig = btn.textContent;
        btn.textContent = "Adding…";
        btn.disabled = true;
        setTimeout(function () {
          btn.textContent = orig;
          btn.disabled = false;
        }, 2000);
      }
    });
  });

  // Show toast message (e.g. after add to cart if we use AJAX later)
  window.showToast = function (message) {
    var existing = document.querySelector(".toast");
    if (existing) existing.remove();
    var el = document.createElement("div");
    el.className = "toast";
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(function () {
      el.style.opacity = "0";
      el.style.transition = "opacity 0.2s";
      setTimeout(function () {
        el.remove();
      }, 200);
    }, 2500);
  };

  // Update cart count in header (for AJAX add-to-cart)
  window.updateCartCount = function (count) {
    var el = document.getElementById("cart-count");
    if (el && count !== undefined) el.textContent = count;
  };

  // Auto-submit filters form on any change
  var filtersForm = document.getElementById("filters-form");
  if (filtersForm) {
    // Store original values to detect actual changes
    var originalValues = {};
    // Category change auto-submit
    var categorySelect = filtersForm.querySelector('select[name="category"]');
    if (categorySelect) {
      originalValues[categorySelect.name] = categorySelect.value;
      categorySelect.addEventListener("change", function () {
        if (categorySelect.value !== originalValues[categorySelect.name]) {
          originalValues[categorySelect.name] = categorySelect.value;
          filtersForm.submit();
        }
      });
    }
    // Price dropdowns auto-submit on change
    var minPriceSelect = filtersForm.querySelector('select[name="min_price"]');
    var maxPriceSelect = filtersForm.querySelector('select[name="max_price"]');
    if (minPriceSelect) {
      originalValues[minPriceSelect.name] = minPriceSelect.value;
      minPriceSelect.addEventListener("change", function () {
        if (minPriceSelect.value !== originalValues[minPriceSelect.name]) {
          originalValues[minPriceSelect.name] = minPriceSelect.value;
          filtersForm.submit();
        }
      });
    }
    if (maxPriceSelect) {
      originalValues[maxPriceSelect.name] = maxPriceSelect.value;
      maxPriceSelect.addEventListener("change", function () {
        if (maxPriceSelect.value !== originalValues[maxPriceSelect.name]) {
          originalValues[maxPriceSelect.name] = maxPriceSelect.value;
          filtersForm.submit();
        }
      });
    }
    // Search input auto-submit on input with 500ms delay
    var searchInput = filtersForm.querySelector('input[name="q"]');
    var searchTimeout;
    if (searchInput) {
      originalValues[searchInput.name] = searchInput.value;
      searchInput.addEventListener("input", function () {
        clearTimeout(searchTimeout);
        if (searchInput.value !== originalValues[searchInput.name]) {
          searchTimeout = setTimeout(function () {
            originalValues[searchInput.name] = searchInput.value;
            filtersForm.submit();
          }, 500);
        }
      });
      // Also submit on Enter key
      searchInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter") {
          clearTimeout(searchTimeout);
          filtersForm.submit();
        }
      });
    }
  }

  // Admin products filters - auto-submit on change
  var adminFiltersForm = document.getElementById("admin-filters-form");
  if (adminFiltersForm) {
    var categorySelect = document.getElementById("category-select");
    if (categorySelect) {
      categorySelect.addEventListener("change", function () {
        adminFiltersForm.submit();
      });
    }
    // Price dropdowns - submit on change
    var minPriceSelect = document.getElementById("min-price-select");
    var maxPriceSelect = document.getElementById("max-price-select");
    if (minPriceSelect) {
      minPriceSelect.addEventListener("change", function () {
        adminFiltersForm.submit();
      });
    }
    if (maxPriceSelect) {
      maxPriceSelect.addEventListener("change", function () {
        adminFiltersForm.submit();
      });
    }
  }

  // Logout confirmation modal
  var logoutModal = document.getElementById("logout-modal");
  var logoutConfirmBtn = document.getElementById("logout-confirm-btn");
  if (logoutModal && logoutConfirmBtn) {
    var logoutUrl = "";

    // Open modal when logout is clicked
    document.querySelectorAll(".logout-trigger").forEach(function (trigger) {
      trigger.addEventListener("click", function (e) {
        e.preventDefault();
        logoutUrl = this.getAttribute("data-logout-url") || this.href;
        logoutModal.classList.add("active");
      });
    });

    // Confirm logout
    logoutConfirmBtn.addEventListener("click", function (e) {
      if (logoutUrl) {
        window.location.href = logoutUrl;
      }
    });

    // Close modal functions
    var closeModal = function () {
      logoutModal.classList.remove("active");
      logoutUrl = "";
    };

    // Close on cancel button
    var modalCancel = logoutModal.querySelector(".modal-cancel");
    if (modalCancel) {
      modalCancel.addEventListener("click", closeModal);
    }

    // Close on close button
    var modalClose = logoutModal.querySelector(".modal-close");
    if (modalClose) {
      modalClose.addEventListener("click", closeModal);
    }

    // Close on overlay click
    logoutModal.addEventListener("click", function (e) {
      if (e.target === logoutModal) {
        closeModal();
      }
    });

    // Close on Escape key
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && logoutModal.classList.contains("active")) {
        closeModal();
      }
    });
  }

  // Bootstrap-like Form Validation
  // Enable validation on all forms with 'needs-validation' class
  // Validation shows ONLY after form submit attempt
  var forms = document.querySelectorAll("form.needs-validation");
  console.log("Found " + forms.length + " forms with needs-validation class");

  forms.forEach(function (form) {
    form.addEventListener(
      "submit",
      function (event) {
        console.log("Form submitted, checking validity...");
        if (!form.checkValidity()) {
          console.log("Form invalid, showing errors");
          event.preventDefault();
          event.stopPropagation();
        } else {
          console.log("Form valid, submitting...");
        }
        form.classList.add("was-validated");
      },
      false,
    );
  });

  // Auto-hide alerts after 5 seconds
  var alerts = document.querySelectorAll(".alert");
  alerts.forEach(function (alert) {
    setTimeout(function () {
      alert.style.opacity = "0";
      alert.style.transition = "opacity 0.5s ease";
      setTimeout(function () {
        alert.remove();
      }, 500);
    }, 5000);
  });

  // Quantity selector buttons (+/-)
  document.querySelectorAll(".qty-controls").forEach(function (controls) {
    var minusBtn = controls.querySelector(".qty-minus");
    var plusBtn = controls.querySelector(".qty-plus");
    var input = controls.querySelector(".qty-input");

    if (minusBtn && plusBtn && input) {
      var min = parseInt(input.getAttribute("min")) || 1;
      var max = parseInt(input.getAttribute("max")) || 999;

      minusBtn.addEventListener("click", function () {
        var current = parseInt(input.value) || 1;
        if (current > min) {
          input.value = current - 1;
        }
      });

      plusBtn.addEventListener("click", function () {
        var current = parseInt(input.value) || 1;
        if (current < max) {
          input.value = current + 1;
        }
      });
    }
  });
})();
