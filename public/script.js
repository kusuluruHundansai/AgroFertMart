
// Initialize cart count
let cartCount = 0;
const cartIcon = document.getElementById('cart-count');

document.querySelectorAll('.btn').forEach(button => {
  button.addEventListener('click', function () {
    if (this.innerText === "Add to Cart") {
      cartCount++;
      cartIcon.textContent = cartCount;
      cartIcon.classList.add('bump');

      // Remove bump effect after animation ends
      setTimeout(() => {
        cartIcon.classList.remove('bump');
      }, 300);

      alert("Item has been added to your cart!");
    }
  });
});

// Contact form handler
document.getElementById('contact-form')?.addEventListener('submit', function (e) {
  e.preventDefault();

  const name = this.querySelector('input[type="text"]').value.trim();
  const email = this.querySelector('input[type="email"]').value.trim();
  const message = this.querySelector('textarea').value.trim();

  if (name && email && message) {
    alert(`Thank you, ${name}! Your message has been sent.`);
    this.reset();
  } else {
    alert("Please fill out all fields before submitting.");
  }
});
