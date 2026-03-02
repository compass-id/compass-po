/**
 * Compass App Logic - FIXED FOR NaN ERRORS
 */

const App = {
  state: {
    cart: JSON.parse(localStorage.getItem("compass_cart")) || [],
    settings: {},
    csrf: document.querySelector('meta[name="csrf-token"]')?.content,
  },

  init: async function () {
    await this.loadSettings();
    this.renderGlobalUI();

    // If on Cart Page
    if (document.getElementById("cart-items-container")) {
      this.renderCartPage();
    }

    // If on Catalog Page
    if (document.querySelector(".product-card")) {
      this.syncCatalogUI();
    }
  },

  loadSettings: async function () {
    try {
      const res = await fetch("api.php?action=get_settings");
      this.state.settings = await res.json();
    } catch (e) {
      console.error("Settings Load Error", e);
    }
  },

  updateQty: function (id, change, price = 0, title = "", img = "") {
    // 1. Force Price to be a Number (Fixes NaN)
    price = parseFloat(price) || 0;

    const index = this.state.cart.findIndex((i) => i.id === id);

    if (index > -1) {
      this.state.cart[index].qty += change;
      if (this.state.cart[index].qty <= 0) {
        this.state.cart.splice(index, 1);
      }
    } else if (change > 0) {
      this.state.cart.push({ id, qty: 1, price, title, img });
    }

    this.saveCart();
    this.renderGlobalUI();
    this.syncCatalogUI();

    if (document.getElementById("cart-items-container")) {
      this.renderCartPage();
    }
  },

  saveCart: function () {
    localStorage.setItem("compass_cart", JSON.stringify(this.state.cart));
  },

  getTotals: function () {
    const s = this.state.settings;
    const MOQ2 = parseInt(s.moq_tier_2 || 20);
    const MOQ3 = parseInt(s.moq_tier_3 || 200);
    const DISC2 = parseFloat(s.discount_tier_2 || 10) / 100;
    const DISC3 = parseFloat(s.discount_tier_3 || 20) / 100;

    // 2. Safe Calculation (Prevents NaN)
    let totalQty = 0;
    let grossTotal = 0;

    this.state.cart.forEach((item) => {
      const qty = parseInt(item.qty) || 0;
      const price = parseFloat(item.price) || 0;
      totalQty += qty;
      grossTotal += price * qty;
    });

    // Determine Tier
    let discountPct = 0;
    let tierName = "Retail";
    let nextTier = 0;

    if (totalQty >= MOQ3) {
      discountPct = DISC3;
      tierName = "Partner";
      nextTier = 100;
    } else if (totalQty >= MOQ2) {
      discountPct = DISC2;
      tierName = "Class Set";
      nextTier = (totalQty / MOQ3) * 100;
    } else {
      nextTier = (totalQty / MOQ2) * 100;
    }

    // Early Bird Date Check
    const today = new Date().toISOString().split("T")[0];
    const deadline = s.early_bird_deadline || "2026-04-30";
    if (today <= deadline) {
      discountPct += 0.05;
    }

    return {
      totalQty,
      grossTotal,
      discountPct,
      netTotal: grossTotal * (1 - discountPct),
      tierName,
      nextTier,
    };
  },

  // ... inside App object ...

  renderGlobalUI: function () {
    const t = this.getTotals();

    // 1. Update Badges
    document
      .querySelectorAll("#cart-badge, #header-cart-count, #fab-count")
      .forEach((b) => {
        if (b) {
          b.innerText = t.totalQty;
          // Hide badge if 0
          b.style.display = t.totalQty > 0 ? "flex" : "none";
        }
      });

    // 2. Update Drawer Content (The Floating Component)
    const drawerItems = document.getElementById("drawer-items");
    const drawerTotal = document.getElementById("drawer-total");

    if (drawerItems && drawerTotal) {
      drawerTotal.innerText = "Rp " + t.grossTotal.toLocaleString();

      if (this.state.cart.length === 0) {
        drawerItems.innerHTML =
          '<p style="text-align:center; color:#999; margin-top:40px;">Your cart is empty.</p>';
      } else {
        drawerItems.innerHTML = this.state.cart
          .map(
            (i) => `
                    <div class="mini-item">
                        <img src="${i.img || "https://via.placeholder.com/50"}" class="mini-img">
                        <div class="mini-details">
                            <div style="font-weight:600; margin-bottom:4px;">${i.title}</div>
                            <div style="font-size:12px; color:#666;">Qty: ${i.qty}</div>
                            <div class="mini-price">Rp ${(i.price * i.qty).toLocaleString()}</div>
                        </div>
                        <button onclick="App.updateQty(${i.id}, -${i.qty})" style="background:none; border:none; color:#ff3b30; font-size:18px; cursor:pointer;">&times;</button>
                    </div>
                `,
          )
          .join("");
      }
    }
  },

  // ... rest of App object ...

  syncCatalogUI: function () {
    // Syncs +/- buttons on Catalog cards
    document.querySelectorAll(".product-card").forEach((card) => {
      const id = parseInt(card.dataset.id);
      const item = this.state.cart.find((i) => i.id === id);
      const qty = item ? item.qty : 0;

      const btnAdd = card.querySelector(".btn-add");
      const qtyControl = card.querySelector(".qty-control");
      const qtyVal = card.querySelector(".qty-val");

      if (btnAdd && qtyControl && qtyVal) {
        if (qty > 0) {
          btnAdd.style.display = "none";
          qtyControl.style.display = "flex";
          qtyVal.innerText = qty;
        } else {
          btnAdd.style.display = "block";
          qtyControl.style.display = "none";
        }
      }
    });
  },

  renderCartPage: function () {
    const container = document.getElementById("cart-items-container");
    const emptyState = document.getElementById("cart-empty-state");
    const content = document.getElementById("cart-content");

    if (!container) return;

    if (this.state.cart.length === 0) {
      if (emptyState) emptyState.style.display = "block";
      if (content) content.style.display = "none";
      return;
    }

    if (emptyState) emptyState.style.display = "none";
    if (content) content.style.display = "grid"; // Grid for side-by-side layout

    // Render Items
    container.innerHTML = this.state.cart
      .map(
        (i) => `
            <div class="cart-item">
                <img src="${i.img || "https://via.placeholder.com/60"}" class="cart-img">
                <div class="cart-details">
                    <div style="font-weight:700;">${i.title}</div>
                    <div style="color:var(--primary);">Rp ${parseInt(i.price).toLocaleString()}</div>
                </div>
                <div class="qty-control" style="width:100px; margin:0;">
                    <button class="qty-btn" onclick="App.updateQty(${i.id}, -1)">-</button>
                    <span class="qty-val">${i.qty}</span>
                    <button class="qty-btn" onclick="App.updateQty(${i.id}, 1)">+</button>
                </div>
            </div>
        `,
      )
      .join("");

    // Render Summary
    const t = this.getTotals();
    const setVal = (id, val) => {
      const el = document.getElementById(id);
      if (el) el.innerText = val;
    };

    setVal("summary-qty", t.totalQty);
    setVal("summary-gross", "Rp " + t.grossTotal.toLocaleString());
    setVal("summary-net", "Rp " + t.netTotal.toLocaleString());
    setVal(
      "summary-discount",
      "- Rp " + (t.grossTotal - t.netTotal).toLocaleString(),
    );
    setVal("summary-tier-name", t.tierName.toUpperCase());

    const bar = document.getElementById("tier-bar");
    if (bar) bar.style.width = Math.min(100, t.nextTier) + "%";
  },

  checkout: async function () {
    const addrSelect = document.getElementById("addr-select");

    if (this.state.cart.length === 0) {
      alert("Cart is empty");
      return;
    }

    if (!addrSelect || addrSelect.value === "") {
      alert("Please add a shipping address first in your Profile.");
      window.location.href = "?page=profile_settings";
      return;
    }

    if (!confirm("Confirm Purchase?")) return;

    try {
      const res = await fetch("api.php?action=place_order", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          cart: this.state.cart,
          address_id: addrSelect.value,
          csrf_token: this.state.csrf,
        }),
      });
      const json = await res.json();

      if (json.status === "success") {
        alert("Order Placed Successfully! Order ID: " + json.order_id);
        this.state.cart = [];
        this.saveCart();
        window.location = "?page=profile";
      } else {
        alert("Error: " + (json.message || "Unknown error"));
        if (json.message?.includes("Login")) window.location = "?page=login";
      }
    } catch (e) {
      alert("System Error: Check console");
      console.error(e);
    }
  },
};

// --- INITIALIZE ---
document.addEventListener("DOMContentLoaded", () => App.init());

// --- CATALOG HELPERS ---
function filterCatalog() {
  const term = document.getElementById("catalog-search").value.toLowerCase();
  const cards = document.querySelectorAll(".product-card");

  cards.forEach((card) => {
    const text = (
      card.dataset.title +
      " " +
      card.dataset.isbn +
      " " +
      card.dataset.category
    ).toLowerCase();
    card.style.display = text.includes(term) ? "flex" : "none";
  });
}

function sortCatalog() {
  const type = document.getElementById("catalog-sort").value;
  const grid = document.getElementById("book-grid");
  const cards = Array.from(document.querySelectorAll(".product-card"));

  cards.sort((a, b) => {
    const priceA = parseFloat(a.dataset.price);
    const priceB = parseFloat(b.dataset.price);

    if (type === "title") {
      return a.dataset.title.localeCompare(b.dataset.title);
    } else if (type === "price_low") {
      return priceA - priceB;
    } else if (type === "price_high") {
      return priceB - priceA;
    }
  });

  grid.innerHTML = "";
  cards.forEach((card) => grid.appendChild(card));
}

function showBookDetails(book) {
  document.getElementById("modal-img").src = book.cover_image;
  document.getElementById("modal-title").innerText = book.title;
  document.getElementById("modal-meta").innerText =
    `${book.category} • ISBN: ${book.isbn}`;
  document.getElementById("modal-price").innerText =
    "Rp " + parseInt(book.base_price).toLocaleString();

  document.getElementById("book-modal").style.display = "flex";
}

// Toggle the Sliding Drawer
function toggleCartDrawer() {
  const drawer = document.getElementById("cart-drawer");
  const backdrop = document.getElementById("cart-backdrop");

  if (drawer.classList.contains("open")) {
    drawer.classList.remove("open");
    backdrop.classList.remove("active");
  } else {
    drawer.classList.add("open");
    backdrop.classList.add("active");
    // Refresh UI just in case
    App.renderGlobalUI();
  }
}
