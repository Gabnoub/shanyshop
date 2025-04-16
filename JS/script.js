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
})

close.addEventListener("click", function() {
    cart.style.transform =  `translateX(${100}%)`;
    opCart.style.zIndex = "10";
    icon.style.color = iconColorValue;
    cQty.style.background = cQtyBackValue;
    cQty.style.color = cQtyColorValue;
})
// =======================================  Quantity Button for product items  ==========================================//


document.querySelectorAll("#minus").forEach(input => {
    input.addEventListener("click", function (event) {
      const item = event.target.closest(".cart__product-item");
      const qty = event.target.nextElementSibling;
      const selectItem = event.target.closest(".cart__product-item");
      let qtySel = qty.innerHTML;
      let qtyNum = parseInt(qtySel);
      qtyNum--;
      
      qty.innerHTML = qtyNum.toString();
      if (qty.innerHTML === "0") {
          selectItem.remove();
          const cpIt = document.querySelectorAll(".cart__product-item");
          let cpItemsNum = cpIt.length;
          cQty.innerHTML = cpItemsNum.toString();
      }
    });
  });

document.querySelectorAll("#plus").forEach(input => {
    input.addEventListener("click", function (event) {
      const item = event.target.closest(".cart__product-item");
      const qty = event.target.previousElementSibling;
      const selectItem = event.target.closest(".cart__product-item");
      let qtySel = qty.innerHTML;
      let qtyNum = parseInt(qtySel);
      qtyNum++;
      qty.innerHTML = qtyNum.toString();
      let cpItemsNum = cpIt.children.length;
cQty.innerHTML = cpItemsNum.toString();
      if (qty.innerHTML === "0") {
          selectItem.style.display = "none";
      }
    });
});

// =======================================  Charge selected thumbnail as first pic in product page  ==========================================//

document.querySelectorAll(".tn__image").forEach(input => {
    input.addEventListener("click", function (event) {
    const tnAll = document.querySelectorAll(".tn__image");
    tnAll.forEach(tn => tn.classList.remove("active"));
    event.target.classList.add("active");
    const tnImageSrc = event.target.src;
    const extratSrc = tnImageSrc.split("images/");
    document.querySelector(".main__prImage").src = "images/" + extratSrc[1];
    });
  });