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
const iconColorValue = getComputedStyle(icon).getPropertyValue('--color-white');
const cQtyColorValue = getComputedStyle(cQty).color;
const cQtyBackValue = getComputedStyle(cQty).backgroundColor;
const cpIt = document.querySelectorAll(".cart__product-item");
let cpItemsNum = cpIt.length;
cQty.innerHTML = cpItemsNum.toString();

icon.addEventListener("click", function() {
    cart.style.transform =  `translateX(${0}%)`;
    opCart.style.zIndex = "25";
    icon.style.color = "black";
    cQty.style.background = "white";
    cQty.style.color = "black";
    localStorage.setItem("cartOpen", "true"); // ✅ Zustand speichern
})

close.addEventListener("click", function() {
    cart.style.transform =  `translateX(${100}%)`;
    opCart.style.zIndex = "10";
    icon.style.color = iconColorValue;
    cQty.style.background = cQtyBackValue;
    cQty.style.color = cQtyColorValue;
    localStorage.setItem("cartOpen", "false"); // ✅ Zustand zurücksetzen
})
// =======================================  Add to cart button was clicked  ==========================================//
// Laden beim Seitenstart
document.addEventListener("DOMContentLoaded", function () {
    updateCartCount();

    // Alle Add-Buttons aktivieren
    document.querySelectorAll(".add-to-cart-btn").forEach(btn => {
      btn.addEventListener("click", function () {
        const product = btn.closest(".product-card");
        const id = product.dataset.id;
        const title = product.dataset.title;
        const price = parseInt(product.dataset.price);
        const image = product.dataset.image;

        addToCart({ id, title, price, image });
      });
    });


    // ✅ Check if cart should be open
    if (localStorage.getItem("cartOpen") === "true") {
      cart.style.transform = `translateX(0%)`;
      opCart.style.zIndex = "25";
      icon.style.color = "black";
      cQty.style.background = "white";
      cQty.style.color = "black";
    }
  });

  function addToCart(product) {
    if (!product.id) {
        console.warn("Product-ID invalid.", product);
        return;
      }
    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    if (cart[product.id]) {
      cart[product.id].qty += 1;
    } else {
      cart[product.id] = { ...product, qty: 1 };
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    location.reload();
  }

  function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem("cart")) || {};
    const totalItems = Object.values(cart).reduce((acc, p) => acc + p.qty, 0);
    document.getElementById("cart__qty").textContent = totalItems;
  }
//   localStorage.removeItem("cart");
// =======================================  Cart page  ==========================================//




  document.addEventListener("DOMContentLoaded", function () {
    const cart = JSON.parse(localStorage.getItem("cart")) || {};
    const container = document.getElementById("cp-items");

    if (Object.keys(cart).length === 0) {
        document.getElementById("cp-items").innerHTML += "<p>Ton panier est vide.</p>";
    } else {
      let html = "";
      for (let key in cart) {
        const p = cart[key];
        html += `
            <div class="cart__product-item" data-id="${p.id}">
            <a class="cart__pr_link" href="product.php?id=${p.id}"><img src="${p.image}"></a>
            <div class="cart__right">
                <div class="cart_description">
                    <p class="cart__pr__title"><strong>${p.title}</strong></p>
                    <p class="cart__pr__price">${p.price} CFA</p>
                </div>
                <div class="quantity">

                    <button class="qty-minus" data-id="${p.id}">-</button>
                    <button class="qty-display" data-id="${p.id}">${p.qty}</button>
                    <button class="qty-plus" data-id="${p.id}">+</button>
                </div>
                <button class="cart_delete" onclick="removeItem('${p.id}')">🗑</button>
            </div>
            </div>
        `;
      }
      container.innerHTML += html;
    }
  });

  function removeItem(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || {};
    delete cart[id];
    localStorage.setItem("cart", JSON.stringify(cart));
    location.reload();
  }




// =======================================  Quantity Logic for cart product items  ==========================================//
document.addEventListener("click", function (e) {
  if (e.target.classList.contains("qty-plus") || e.target.classList.contains("qty-minus")) {
    const id = e.target.dataset.id;
    let cart = JSON.parse(localStorage.getItem("cart")) || {};

    if (!cart[id]) return;

    if (e.target.classList.contains("qty-plus")) {
      cart[id].qty += 1;
    } else {
      cart[id].qty -= 1;
      if (cart[id].qty <= 0) {
        delete cart[id];
      }
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    location.reload();
     // oder updateCartHTML(); wenn du dynamisch arbeitest
  }
});

// =======================================  Charge selected thumbnail as first pic in product page  ==========================================//

document.querySelectorAll(".tn__image").forEach(input => {
    input.addEventListener("click", function (event) {
    const tnAll = document.querySelectorAll(".tn__image");
    tnAll.forEach(tn => tn.classList.remove("active"));
    event.target.classList.add("active");
    const tnImageSrc = event.target.src;
    const extratSrc = tnImageSrc.split("images/");
    document.querySelector(".main__prImage").src = "admin/images/" + extratSrc[1];
    });
  });