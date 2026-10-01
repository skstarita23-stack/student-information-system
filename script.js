// script.js - client-side interaction (PHP still validates everything again).

// 1. Delete confirmation
document.querySelectorAll('.confirm-delete').forEach(function (link) {
  link.addEventListener('click', function (event) {
    if (!confirm('Are you sure you want to delete this student?')) {
      event.preventDefault(); // Cancel: stay on the page
    }
  });
});

// 2. Basic form validation
var form = document.getElementById('studentForm');
if (form) {
  form.addEventListener('submit', function (event) {
    var errors = [];
    var v = function (id) { return document.getElementById(id).value.trim(); };

    if (v('first_name') === '') errors.push('First name is required.');
    if (v('last_name') === '') errors.push('Last name is required.');
    if (v('gender') === '') errors.push('Please select a gender.');
    if (v('date_of_birth') === '') {
      errors.push('Date of birth is required.');
    } else if (new Date(v('date_of_birth')) > new Date()) {
      errors.push('Date of birth cannot be in the future.');
    }
    if (v('course') === '') errors.push('Course/Program is required.');
    var year = parseInt(v('year_level'), 10);
    if (isNaN(year) || year < 1 || year > 6) errors.push('Year level must be 1 to 6.');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v('email'))) errors.push('Please enter a valid email address.');
    if (!/^[0-9+\-\s()]{7,30}$/.test(v('contact_number'))) errors.push('Contact number must be 7-30 characters (digits, +, -, spaces).');

    var old = document.getElementById('js-errors');
    if (old) old.remove();
    if (errors.length > 0) {
      event.preventDefault();
      var box = document.createElement('div');
      box.id = 'js-errors';
      box.className = 'alert alert-error';
      box.innerHTML = '<strong>Please fix the following:</strong><ul></ul>';
      errors.forEach(function (msg) {
        var li = document.createElement('li');
        li.textContent = msg;
        box.querySelector('ul').appendChild(li);
      });
      form.parentNode.insertBefore(box, form);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
  });
}

// 3. Success/error messages fade out after 5 seconds
setTimeout(function () {
  document.querySelectorAll('.alert-success').forEach(function (el) {
    el.style.transition = 'opacity 0.5s';
    el.style.opacity = '0';
    setTimeout(function () { el.remove(); }, 500);
  });
}, 5000);

// 4. Login form validation
var loginForm = document.getElementById('loginForm');
if (loginForm) {
  loginForm.addEventListener('submit', function (event) {
    var u = document.getElementById('username').value.trim();
    var p = document.getElementById('password').value;
    if (u === '' || p === '') {
      event.preventDefault();
      alert('Please enter your username and password.');
    }
  });
}

// 5. Parallax effect on the login page (scroll + mouse movement)
var uniBg = document.getElementById('uniBg');
var heroText = document.getElementById('heroText');
if (uniBg && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
  var mouseX = 0, mouseY = 0;

  function updateParallax() {
    var scroll = window.scrollY;
    // Background moves slowly (with a little mouse shift); the 15% extra height hides the edges
    uniBg.style.transform = 'translate3d(' + mouseX + 'px,' + (-Math.min(scroll * 0.12, 60) + mouseY) + 'px,0) scale(1.05)';
    // Hero text drifts slightly slower than the page
    if (heroText) heroText.style.transform = 'translate3d(0,' + (scroll * 0.18) + 'px,0)';
  }

  window.addEventListener('scroll', updateParallax);
  document.addEventListener('mousemove', function (e) {
    mouseX = (e.clientX / window.innerWidth - 0.5) * -20;
    mouseY = (e.clientY / window.innerHeight - 0.5) * -20;
    updateParallax();
  });
  updateParallax();
}