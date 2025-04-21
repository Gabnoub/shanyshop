// =================================== Currentslide show and stop automatic caroussel=============================================//

function currentSlide(i) {

    // Stop automatic mode
    clearInterval(myinterval);

    progressBars.forEach(bar => bar.classList.remove("active"));
    progressBars.forEach(bar => bar.classList.remove("active__after_clicked"));

    slides.forEach(slide => slide.style.display = "none");
    index = i;
    progressBars[index].classList.add("active__after_clicked");
    slides[index].style.display = "block";

}
// =======================================  Activate and desactivate drop-down menu  ==========================================//

const navLinks = document.querySelector(".nav__links");
const menuBtn = document.getElementById("menu__icon");
// const menuBtnClose = document.getElementById("menu__icon-close");
menuBtn.addEventListener("click", function() {
    navLinks.classList.toggle("active");
    menuBtn.innerHTML = navLinks.classList.contains("active") ? "✖" : "☰";
}
)

document.addEventListener("click", function (event) {
    if (!navLinks.contains(event.target) && !menuBtn.contains(event.target)) {
        navLinks.classList.remove("active");
        menuBtn.textContent = "☰";
    }
})



// =======================================  Activate and desactivate Cart section  ==========================================//
const icon = document.getElementById("cart__icon");
const cart = document.getElementById("cartCont");
const close = document.getElementById("close__icon");
const opCart = document.querySelector(".open__cart");
const cQty = document.getElementById("cart__qty");

icon.addEventListener("click", function() {
    cart.style.transform =  `translateX(${0}%)`;
    document.body.style.overflow = "hidden"; // 🔒 Scroll sperren
})

close.addEventListener("click", function() {
    cart.style.transform =  `translateX(${100}%)`;  
    document.body.style.overflow = "auto"; // ✅ Scroll erlauben
})

// =======================================  Charge selected thumbnail as first pic in product page  ==========================================//

document.querySelectorAll(".tn__image").forEach(input => {
    input.addEventListener("click", function (event) {
    const tnAll = document.querySelectorAll(".tn__image");
    tnAll.forEach(tn => tn.classList.remove("active"));
    event.target.classList.add("active");
    const tnImageSrc = event.target.src;
    document.querySelector(".main__prImage").src = tnImageSrc;
    });
  });
  // =======================================  Add to cart button was clicked  ==========================================//
  window.addEventListener("pageshow", () => {
    renderCart();
  });
// Warenkorb auslesen
function getCart() {
  return JSON.parse(localStorage.getItem("cart")) || {};
}

// Warenkorb speichern
function saveCart(cart) {
  localStorage.setItem("cart", JSON.stringify(cart));
}

// Produkt hinzufügen
function addToCart(product) {
  const cart = getCart();
  if (cart[product.id]) {
    cart[product.id].qty += 1;
  } else {
    cart[product.id] = { ...product, qty: 1 };
  }
  saveCart(cart);
  renderCart();
}

// Produkt entfernen
function removeItem(id) {
  const cart = getCart();
  delete cart[id];
  saveCart(cart);
  renderCart();
}

// Menge aktualisieren
function changeQty(id, delta) {
  const cart = getCart();
  if (cart[id]) {
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    saveCart(cart);
    renderCart();
  }
}

// Warenkorb rendern
function renderCart() {
  const cart = getCart();
  const container = document.getElementById("cp-items");
  const qtyCounter = document.getElementById("cart__qty");
  const qtyCounter_new = document.getElementById("cart__qty_new");
  
  container.innerHTML = "";
  let totalItems = 0;
  let totalPrice = 0;
  let message = "🛍️ Nouvelle commande:\n";
  // 💰 Gesamtpreis berechnen
  let total_price = Object.values(cart).reduce((sum, p) => sum + p.qty * p.price, 0);
  let summe = total_price.toLocaleString('de-DE') + " CFA";

  if (Object.keys(cart).length === 0) {
    let html = `<div style="color:black; width:200px; text-align:center; font-size:medium"><strong>Votre panier est actuellement vide.</strong></div>`;
    container.innerHTML = html;
  } else {
    for (let key in cart) {
      const p = cart[key];
      totalItems += p.qty;
      const lineTotal = p.qty * p.price;
      totalPrice += lineTotal;
      message += `• ${p.title} x${p.qty} = ${lineTotal.toLocaleString('de-DE')} CFA\n`;

      const el = document.createElement("div");
      el.className = "cart__product-item";
      el.innerHTML = `
         
            <a class="cart__pr_link" href="${p._slug}"><img src="${p.image}"></a>
            <div class="cart__right">
                <div class="cart_description">
                    <p class="cart__pr__title"><strong>${p.title}</strong></p>
                    <p class="cart__pr__price">${p.price} CFA</p>
                </div>
                <div class="quantity">

                    <button style="cursor:pointer;" class="qty-minus" data-id="${p.id}">-</button>
                    <button style="cursor:pointer;" class="qty-display" data-id="${p.id}">${p.qty}</button>
                    <button style="cursor:pointer;" class="qty-plus" data-id="${p.id}">+</button>
                </div>
                <button class="cart_delete" onclick="removeItem('${p.id}')"><i class="uil uil-trash-alt"></i></button>
            </div>
          `;
      container.appendChild(el);
    }
    const el1 = document.createElement("div");
      el1.className = "cart_bottom";
      el1.innerHTML = `
      <div id="total" style="margin-top:1rem; color:black; width:250px; text-align:center; font-size:small; font-weight:600;">Total de votre commande</div>
      <p id="cart__total" style="color:black; text-align:center; font-size:small"></p>
      <button id="whatsappCheckout">Commander via <i class="uil uil-whatsapp"></i> Whatsapp</button>
      `;

      container.appendChild(el1);

      const totalEl = document.getElementById("cart__total");
      if (totalEl) {
        totalEl.textContent = summe;
      } else {
        console.warn("Element #cart__total not found");
      }

    message += `\n💰 Total: ${totalPrice.toLocaleString('de-DE')} CFA`;

    if (totalEl) totalEl.textContent = totalPrice.toLocaleString('de-DE') + " CFA";
    
    const checkoutBtn = document.getElementById("whatsappCheckout");
    if (checkoutBtn) {
      checkoutBtn.onclick = () => {
        const phone = "+237652042276";
        const url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
        window.open(url, "_blank");
      };
    }
  }

  qtyCounter.textContent = totalItems;
  qtyCounter_new.textContent = totalItems;

  // Buttons registrieren
  document.querySelectorAll(".qty-plus").forEach(btn =>
    btn.addEventListener("click", () => changeQty(btn.dataset.id, 1))
  );
  document.querySelectorAll(".qty-minus").forEach(btn =>
    btn.addEventListener("click", () => changeQty(btn.dataset.id, -1))
  );
  document.querySelectorAll(".remove").forEach(btn =>
    btn.addEventListener("click", () => removeItem(btn.dataset.id))
  );
}
//------------------------------------------------ Initialisierung -----------------------------------------------------------------------------//
document.addEventListener("DOMContentLoaded", () => {
  
  renderCart();

  

  document.querySelectorAll(".add-to-cart-btn").forEach(btn =>
    btn.addEventListener("click", () => {
      const product = btn.closest(".product-card");
      addToCart({
        id: product.dataset.id,
        title: product.dataset.title,
        price: parseInt(product.dataset.price),
        image: product.dataset.image,
        _slug: product.dataset.slug
      });
    })
  );

  const lifestyleImages = document.querySelectorAll(".animation");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("visible");
      }
    });
  }, {
    threshold: 0.3 // wenn 20% sichtbar, animieren
  });

  lifestyleImages.forEach(image => observer.observe(image));

});
//------------------------------------------------ show comment form -----------------------------------------------------------------------------//
const review = document.getElementById("review");
const form = document.querySelector(".rating-form");
review.addEventListener("click", () => {
  form.classList.toggle("active");
});