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


const originalPos = opCart.getBoundingClientRect();

// optional speichern
const savedPosition = {
  top: originalPos.top + window.scrollY,
  right: originalPos.right + window.scrollX
};


let cpItemsNum = cpIt.length;
cQty.innerHTML = cpItemsNum.toString();

icon.addEventListener("click", function() {
    cart.style.transform =  `translateX(${0}%)`;
    // opCart.style.zIndex = "25";
    icon.style.color = "black";
    cQty.style.background = "white";
    cQty.style.color = "black";
    document.body.style.overflow = "hidden"; // 🔒 Scroll sperren
    sessionStorage.setItem("cartOpen", "true"); // ✅ Zustand speichern
    
    
})

close.addEventListener("click", function() {
    cart.style.transform =  `translateX(${100}%)`;
    // opCart.style.zIndex = "10";
    icon.style.color = iconColorValue;
    cQty.style.background = cQtyBackValue;
    cQty.style.color = cQtyColorValue;  
    document.body.style.overflow = "auto"; // ✅ Scroll erlauben
    sessionStorage.setItem("cartOpen", "false"); // ✅ Zustand zurücksetzen
    // clone cart icon
    // while (newbtn.hasChildNodes()) {
    //   newbtn.removeChild(newbtn.firstChild);
    // }
    
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
    if (sessionStorage.getItem("cartOpen") === "true") {
      cart.style.transform = `translateX(0%)`;
      opCart.style.zIndex = "25";
      icon.style.color = "black";
      cQty.style.background = "white";
      cQty.style.color = "black";
      opCart.style.zIndex = "10";
      document.body.style.overflow = "hidden"; // 🔒 Scroll sperren
      // clone cart icon
   
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
    const cartContainer = document.getElementById("cartCont");
    const totalDiv = document.getElementById("cart__total");
    const checkoutBtn = document.getElementById("whatsappCheckout");
    let message = "Nouvelle commande:\n";
    let totalPrice = 0;
    // 💰 Gesamtpreis berechnen
    let total_price = Object.values(cart).reduce((sum, p) => sum + p.qty * p.price, 0);
    let summe = total_price.toLocaleString('de-DE') + " CFA";


    // clone cart icon
    const node1 = document.getElementById("cart__icon");
    const clone1 = node1.cloneNode(true);
    const node2 = document.getElementById("cart__qty");
    const clone2 = node2.cloneNode(true);
    const newbtn = document.getElementById("new__cart");
    newbtn.appendChild(clone1);
    newbtn.appendChild(clone2);


    // product item generator + check out button 
    if (Object.keys(cart).length === 0) {
      let html = `<div style="color:black; width:200px; text-align:center; font-size:medium"><strong>Votre panier est actuellement vide.</strong></div>`;
      container.innerHTML = html;
    } else {
      let html = "";
      for (let key in cart) {
        const p = cart[key];
        const lineTotal = p.qty * p.price;
        totalPrice += lineTotal;
        message += `• ${p.title} x${p.qty} = ${lineTotal.toLocaleString('de-DE')} CFA\n`;

        html += `
            <div class="cart__product-item" data-id="${p.id}">
            <a class="cart__pr_link" href="product.php?id=${p.id}"><img src="${p.image}"></a>
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
            </div>
        `;
      }

      html += `
              <div class="testo">
              <div id="total" style="margin-top:1rem; color:black; width:250px; text-align:center; font-size:small; font-weight:600;">Total de votre commande</div>
              <p id="cart__total" style="color:black; text-align:center; font-size:small"></p>
              <button id="whatsappCheckout">Commander via <i class="uil uil-whatsapp"></i> Whatsapp</button>
              </div>
      `;
      
      container.innerHTML += html;
      const totalEl = document.getElementById("cart__total");
      if (totalEl) {
        totalEl.textContent = summe;
      } else {
        console.warn("Element #cart__total not found");
      }
    }
    document.getElementById("whatsappCheckout").addEventListener("click", function () {
      whatsappCheckout(message, summe);
    });
  });
  
// Whatsapp Checkout
function whatsappCheckout(mes, sum) {
  const phone = "+237652042276"; // deine WhatsApp-Nummer
  const final_message = `${mes}\n• Total: ${sum}`;
  // console.log(final_message);
  const whatsappUrl = `https://wa.me/${phone}?text=${encodeURIComponent(final_message)}`;
  window.open(whatsappUrl, "_blank");
}

// Cart remove item function
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