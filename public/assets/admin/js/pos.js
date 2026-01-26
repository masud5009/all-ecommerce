(() => {
    "use strict";

    // ----------------------------
    // Config (Required endpoints)
    // ----------------------------
    // Blade থেকে inject করুন:
    // <script>
    // window.POS_CONFIG = {
    //   endpoints: {
    //     bootstrap: "/user/pos/bootstrap", // tables, categories, products (optional if already in Blade)
    //     stats: "/user/pos/stats",
    //     held: "/user/pos/held",           // GET list, POST create, PUT resume, DELETE remove
    //     kitchen: "/user/pos/kitchen",     // GET list, POST create, PUT done
    //     history: "/user/pos/history",     // GET list
    //     checkout: "/user/pos/checkout",   // POST
    //     bill: "/user/pos/bill"            // GET /bill/{id}
    //   }
    // }
    // </script>

    const CONFIG = window.POS_CONFIG || {};
    const ENDPOINTS = (CONFIG && CONFIG.endpoints) || {};

    // ----------------------------
    // Helpers
    // ----------------------------
    const $ = (sel, root = document) => root.querySelector(sel);
    const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

    const escapeHtml = (s) =>
        String(s ?? "")
            .replaceAll("&", "&amp;")
            .replaceAll("<", "&lt;")
            .replaceAll(">", "&gt;")
            .replaceAll('"', "&quot;")
            .replaceAll("'", "&#039;");

    const getCSRFToken = () => {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute("content") : "";
    };

    const fetchJSON = async (url, { method = "GET", body } = {}) => {
        const headers = {
            Accept: "application/json",
            "Content-Type": "application/json",
        };
        const csrf = getCSRFToken();
        if (csrf) headers["X-CSRF-TOKEN"] = csrf;

        const res = await fetch(url, {
            method,
            headers,
            body: body ? JSON.stringify(body) : undefined,
            credentials: "same-origin",
        });

        const text = await res.text();
        let data = null;
        try {
            data = text ? JSON.parse(text) : null;
        } catch {
            data = text;
        }

        if (!res.ok) {
            const msg = (data && data.message) || `Request failed (${res.status})`;
            throw new Error(msg);
        }
        return data;
    };

    const money = (n) => {
        const num = Number(n || 0);
        return num % 1 === 0 ? String(num) : num.toFixed(2);
    };

    const toast = (message, type = "info") => {
        const map = {
            success: { heading: "Success", icon: "success" },
            info: { heading: "Info", icon: "info" },
            warning: { heading: "Warning", icon: "warning" },
            danger: { heading: "Error", icon: "error" },
            error: { heading: "Error", icon: "error" },
        };

        const t = map[type] || map.info;

        if (window.$ && $.toast) {
            $.toast({
                heading: t.heading,
                text: message,
                showHideTransition: "plain",
                icon: t.icon,
                allowToastClose: true,
                position: "top-right",
                hideAfter: 4000,
            });
            return;
        }
    };


    // ----------------------------
    // DOM
    // ----------------------------
    const el = {
        todaySales: $("#todaySales"),
        todayOrders: $("#todayOrders"),
        occupiedTables: $("#occupiedTables"),
        totalTables: $("#totalTables"),
        kitchenOrders: $("#kitchenOrders"),

        tableBadge: $("#tableBadge"),
        cartCount: $("#cartCount"),
        cartItems: $("#cartItems"),
        orderNotes: $("#orderNotes"),

        subtotal: $("#subtotal"),
        serviceCharge: $("#serviceCharge"),
        vat: $("#vat"),
        discount: $("#discount"),
        grandTotal: $("#grandTotal"),

        orderType: $("#orderType"),
        tableSelectionWrap: $("#tableSelection"),
        selectedTable: $("#selectedTable"),
        paymentMethod: $("#paymentMethod"),

        searchProduct: $("#searchProduct"),

        heldBadge: $("#heldBadge"),

        customerInfo: $("#customerInfo"),

        heldBillsList: $("#heldBillsList"),
        orderHistoryList: $("#orderHistoryList"),
        kitchenOrdersList: $("#kitchenOrdersList"),

        billOrderId: $("#billOrderId"),
        billDetailsContent: $("#billDetailsContent"),
    };

    const modals = {
        customer: null,
        held: null,
        history: null,
        kitchen: null,
        bill: null,
    };

    const initModals = () => {
        if (!window.bootstrap) return;
        modals.customer = new bootstrap.Modal($("#customerModal"));
        modals.held = new bootstrap.Modal($("#heldBillsModal"));
        modals.history = new bootstrap.Modal($("#orderHistoryModal"));
        modals.kitchen = new bootstrap.Modal($("#kitchenOrdersModal"));
        modals.bill = new bootstrap.Modal($("#billDetailsModal"));
    };
    const state = {
        selectedTableId: null,
        selectedTableName: null,

        customerId: null,
        customerName: "Walk-in Customer",

        orderType: "dine-in",
        paymentMethod: "cash",

        cart: [], // {id,name,price,image,cooking_time,is_veg,qty}
        discount: 0,
        orderNotes: "",
    };

    // Cached lists (from API)
    let tablesCache = []; // [{id,name,status}]
    let heldCache = [];
    let kitchenCache = [];
    let historyCache = [];

    // ----------------------------
    // Totals
    // ----------------------------
    const calculateTotals = () => {
        const subtotal = state.cart.reduce((sum, x) => sum + Number(x.price) * Number(x.qty), 0);
        const serviceCharge = subtotal * 0.10;
        const vat = subtotal * 0.05;
        const discount = Math.max(0, Number(state.discount || 0));
        const grand = Math.max(0, subtotal + serviceCharge + vat - discount);
        return { subtotal, serviceCharge, vat, discount, grand };
    };

    const renderTotals = () => {
        const { subtotal, serviceCharge, vat, grand } = calculateTotals();
        const prefix = el.subtotal?.textContent?.trim()?.match(/^\D+/)?.[0] || "";

        const setText = (node, val) => {
            if (!node) return;
            node.textContent = `${prefix}${money(val)}`;
        };

        setText(el.subtotal, subtotal);
        setText(el.serviceCharge, serviceCharge);
        setText(el.vat, vat);
        setText(el.grandTotal, grand);
    };

    // ----------------------------
    // Cart
    // ----------------------------
    const findCartItem = (id) => state.cart.find((x) => String(x.id) === String(id));

    const renderCart = () => {
        if (!el.cartItems) return;

        const count = state.cart.reduce((sum, x) => sum + Number(x.qty), 0);
        if (el.cartCount) el.cartCount.textContent = String(count);

        if (el.tableBadge) el.tableBadge.textContent = state.selectedTableName || "No Table";
        if (el.customerInfo) el.customerInfo.textContent = state.customerName || "Walk-in Customer";

        if (el.discount) el.discount.value = String(state.discount || 0);
        if (el.orderNotes) el.orderNotes.value = state.orderNotes || "";

        if (state.cart.length === 0) {
            el.cartItems.innerHTML = `
        <div class="p-3 text-center text-muted">
          <i class="fas fa-shopping-cart fa-2x mb-2"></i>
          <div>No items in cart</div>
          <small>Add items from menu</small>
        </div>
      `;
            renderTotals();
            return;
        }

        el.cartItems.innerHTML = state.cart
            .map((x) => {
                const total = Number(x.price) * Number(x.qty);
                return `
          <div class="p-3 border-bottom">
            <div class="d-flex align-items-start gap-2">
              <img src="${x.image || ""}" alt="${escapeHtml(x.name)}"
                   style="width:52px;height:52px;object-fit:cover;border-radius:8px;"
                   onerror="this.style.display='none'">
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between">
                  <div>
                    <div class="fw-semibold">${escapeHtml(x.name)}</div>
                    <small class="text-muted">
                      ${x.cooking_time ? `<i class="fas fa-clock"></i> ${x.cooking_time}m • ` : ""}
                      ${x.is_veg ? `<span class="badge bg-success">Veg</span>` : `<span class="badge bg-danger">Non-Veg</span>`}
                    </small>
                  </div>
                  <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${x.id})">
                    <i class="fas fa-times"></i>
                  </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                  <div class="input-group input-group-sm" style="width: 120px;">
                    <button class="btn btn-outline-secondary" onclick="decQty(${x.id})">-</button>
                    <input type="number" class="form-control text-center" min="1" value="${x.qty}"
                      onchange="setQty(${x.id}, this.value)">
                    <button class="btn btn-outline-secondary" onclick="incQty(${x.id})">+</button>
                  </div>
                  <div class="fw-bold">${money(total)}</div>
                </div>
              </div>
            </div>
          </div>
        `;
            })
            .join("");

        renderTotals();
    };

    const clearCartInternal = () => {
        state.cart = [];
        state.discount = 0;
        state.orderNotes = "";
        renderCart();
    };

    // ----------------------------
    // Table select
    // ----------------------------
    const populateTableSelect = () => {
        if (!el.selectedTable) return;

        const options = [
            `<option value="">Select Table</option>`,
            ...tablesCache.map((t) => `<option value="${t.id}">${escapeHtml(t.name)}</option>`),
        ];
        el.selectedTable.innerHTML = options.join("");
        el.selectedTable.value = state.selectedTableId ? String(state.selectedTableId) : "";
    };

    window.toggleTableSelection = () => {
        if (!el.orderType || !el.tableSelectionWrap) return;

        state.orderType = el.orderType.value || "dine-in";

        const show = state.orderType === "dine-in";
        el.tableSelectionWrap.style.display = show ? "" : "none";

        if (!show) {
            state.selectedTableId = null;
            state.selectedTableName = null;
            if (el.selectedTable) el.selectedTable.value = "";
            if (el.tableBadge) el.tableBadge.textContent = "No Table";
        }
        renderTotals();
    };

    // ----------------------------
    // API payload
    // ----------------------------
    const makeOrderPayload = () => {
        const totals = calculateTotals();

        return {
            table_id: state.orderType === "dine-in" ? state.selectedTableId : null,
            order_type: state.orderType,

            customer_id: state.customerId,
            customer_name: state.customerName,

            payment_method: state.paymentMethod,
            discount: Number(state.discount || 0),
            notes: state.orderNotes || "",

            items: state.cart.map((x) => ({
                product_id: x.id,
                name: x.name,
                price: Number(x.price),
                qty: Number(x.qty),
                cooking_time: Number(x.cooking_time || 0),
                is_veg: !!x.is_veg,
            })),

            totals: {
                subtotal: totals.subtotal,
                service_charge: totals.serviceCharge,
                vat: totals.vat,
                grand_total: totals.grand,
            },
        };
    };

    const requireCart = () => {
        if (!state.cart.length) {
            toast("Cart is empty.", "warning");
            return false;
        }
        if (state.orderType === "dine-in" && !state.selectedTableId) {
            toast("Please select a table for Dine In.", "warning");
            return false;
        }
        return true;
    };

    // ----------------------------
    // Global functions (Blade onclick)
    // ----------------------------
    window.selectTable = (tableId) => {
        const t = tablesCache.find((x) => String(x.id) === String(tableId));
        state.selectedTableId = Number(tableId);
        state.selectedTableName = t?.name || `Table ${tableId}`;

        if (el.selectedTable) el.selectedTable.value = String(tableId);
        renderCart();
        toast(`Selected: ${state.selectedTableName}`, "success");
    };

    window.addToCart = (id, name, price, image, cookingTime, isVeg) => {
        const existing = findCartItem(id);
        if (existing) existing.qty += 1;
        else {
            state.cart.push({
                id,
                name,
                price: Number(price),
                image: image || "",
                cooking_time: Number(cookingTime || 0),
                is_veg: !!isVeg,
                qty: 1,
            });
        }
        renderCart();
        toast(`${name} added`, "success");
    };

    window.incQty = (id) => {
        const item = findCartItem(id);
        if (!item) return;
        item.qty += 1;
        renderCart();
    };

    window.decQty = (id) => {
        const item = findCartItem(id);
        if (!item) return;
        if (item.qty <= 1) return;
        item.qty -= 1;
        renderCart();
    };

    window.setQty = (id, val) => {
        const item = findCartItem(id);
        if (!item) return;
        item.qty = Math.max(1, Number(val || 1));
        renderCart();
    };

    window.removeFromCart = (id) => {
        state.cart = state.cart.filter((x) => String(x.id) !== String(id));
        renderCart();
        toast("Item removed", "info");
    };

    window.clearCart = () => {
        clearCartInternal();
        toast("Cart cleared", "info");
    };

    window.showCustomerModal = () => modals.customer?.show();

    window.selectCustomer = (id, name) => {
        state.customerId = id ? Number(id) : null;
        state.customerName = name || "Walk-in Customer";
        renderCart();
        modals.customer?.hide();
        toast(`Customer: ${state.customerName}`, "success");
    };

    // ---- Held bills (API) ----
    window.holdBill = async () => {
        if (!requireCart()) return;
        if (!ENDPOINTS.held) {
            toast("Held endpoint not configured.", "danger");
            return;
        }

        // sync inputs
        state.discount = Number(el.discount?.value || state.discount || 0);
        state.orderNotes = el.orderNotes?.value || state.orderNotes;
        state.paymentMethod = el.paymentMethod?.value || state.paymentMethod;

        const payload = makeOrderPayload();

        try {
            const data = await fetchJSON(ENDPOINTS.held, { method: "POST", body: payload });
            toast(`Bill held (#${data?.id ?? "OK"})`, "success");
            clearCartInternal();
            await loadHeldBills(); // refresh badge/list cache
        } catch (e) {
            toast(e.message || "Failed to hold bill", "danger");
        }
    };

    window.showHeldBills = async () => {
        await loadHeldBills(true);
        modals.held?.show();
    };

    window.resumeHeldBill = async (id) => {
        if (!ENDPOINTS.held) return;
        try {
            // PUT /held/{id}/resume (example)
            const data = await fetchJSON(`${ENDPOINTS.held}/${id}/resume`, { method: "PUT" });

            // server returns payload to restore cart
            const p = data?.payload || data;
            restoreFromPayload(p);

            toast(`Resumed bill (#${id})`, "success");
            modals.held?.hide();
            await loadHeldBills();
        } catch (e) {
            toast(e.message || "Failed to resume held bill", "danger");
        }
    };

    window.deleteHeldBill = async (id) => {
        if (!ENDPOINTS.held) return;
        try {
            await fetchJSON(`${ENDPOINTS.held}/${id}`, { method: "DELETE" });
            toast(`Deleted held bill (#${id})`, "info");
            await loadHeldBills(true);
        } catch (e) {
            toast(e.message || "Failed to delete held bill", "danger");
        }
    };

    // ---- Kitchen (API) ----
    window.sendToKitchen = async () => {
        if (!requireCart()) return;
        if (!ENDPOINTS.kitchen) {
            toast("Kitchen endpoint not configured.", "danger");
            return;
        }

        state.discount = Number(el.discount?.value || state.discount || 0);
        state.orderNotes = el.orderNotes?.value || state.orderNotes;

        const payload = makeOrderPayload();

        try {
            const data = await fetchJSON(ENDPOINTS.kitchen, { method: "POST", body: payload });
            toast(`Sent to kitchen (#${data?.id ?? "OK"})`, "success");
            await loadKitchenOrders(); // refresh stats/cache
        } catch (e) {
            toast(e.message || "Failed to send to kitchen", "danger");
        }
    };

    window.showKitchenOrders = async () => {
        await loadKitchenOrders(true);
        modals.kitchen?.show();
    };

    window.markKitchenDone = async (id) => {
        if (!ENDPOINTS.kitchen) return;
        try {
            await fetchJSON(`${ENDPOINTS.kitchen}/${id}/done`, { method: "PUT" });
            toast(`Kitchen order done (#${id})`, "success");
            await loadKitchenOrders(true);
        } catch (e) {
            toast(e.message || "Failed to mark done", "danger");
        }
    };

    // ---- Checkout (API) ----
    window.checkout = async () => {
        if (!requireCart()) return;
        if (!ENDPOINTS.checkout) {
            toast("Checkout endpoint not configured.", "danger");
            return;
        }

        state.paymentMethod = el.paymentMethod?.value || state.paymentMethod;
        state.discount = Number(el.discount?.value || state.discount || 0);
        state.orderNotes = el.orderNotes?.value || state.orderNotes;

        const payload = makeOrderPayload();

        try {
            const data = await fetchJSON(ENDPOINTS.checkout, { method: "POST", body: payload });
            const orderId = data?.id || data?.order_id;
            toast(`Checkout complete (#${orderId ?? "OK"})`, "success");

            clearCartInternal();
            await loadStats();
            await loadHistory(false);

            if (orderId) {
                window.viewBillDetails(orderId);
            }
        } catch (e) {
            toast(e.message || "Checkout failed", "danger");
        }
    };

    // ---- History (API) ----
    window.showOrderHistory = async () => {
        await loadHistory(true);
        modals.history?.show();
    };

    // ---- Bill (API) ----
    window.viewBillDetails = async (orderId) => {
        if (!ENDPOINTS.bill) {
            toast("Bill endpoint not configured.", "danger");
            return;
        }

        try {
            const data = await fetchJSON(`${ENDPOINTS.bill}/${orderId}`, { method: "GET" });
            renderBillModal(orderId, data?.payload || data);
            modals.bill?.show();
        } catch (e) {
            toast(e.message || "Failed to load bill", "danger");
        }
    };

    window.printBill = () => {
        const id = el.billOrderId ? el.billOrderId.textContent : "";
        const content = el.billDetailsContent ? el.billDetailsContent.innerHTML : "<div>—</div>";

        const w = window.open("", "_blank");
        if (!w) {
            toast("Popup blocked. Please allow popups for printing.", "warning");
            return;
        }

        w.document.write(`
      <html>
      <head>
        <title>Bill #${id}</title>
        <meta charset="utf-8">
        <style>
          body{font-family: Arial, sans-serif; padding:16px;}
          table{width:100%; border-collapse: collapse;}
          th, td{border-bottom:1px solid #ddd; padding:6px; font-size: 13px;}
          th{text-align:left;}
          .text-end{text-align:right;}
          hr{border:none; border-top:1px solid #ddd; margin:12px 0;}
        </style>
      </head>
      <body>
        <h3>Bill #${id}</h3>
        ${content}
        <script>
          window.onload = function(){ window.print(); window.close(); };
        </script>
      </body>
      </html>
    `);
        w.document.close();
    };

    // ----------------------------
    // Renderers (Held/History/Kitchen/Bill)
    // ----------------------------
    const renderHeldBills = (list) => {
        if (!el.heldBillsList) return;

        if (!list.length) {
            el.heldBillsList.innerHTML = `<div class="text-center text-muted py-4">No held bills</div>`;
            return;
        }

        el.heldBillsList.innerHTML = `
      <div class="list-group">
        ${list
                .map((h) => {
                    const total = h.total ?? h.payload?.totals?.grand_total ?? 0;
                    const customer = h.customer_name ?? h.payload?.customer_name ?? "Walk-in Customer";
                    const type = h.order_type ?? h.payload?.order_type ?? "dine-in";
                    const tableId = h.table_id ?? h.payload?.table_id;
                    const table = tableId ? `Table #${tableId}` : "No Table";

                    return `
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fw-semibold">Held Bill #${h.id}</div>
                    <small class="text-muted">${escapeHtml(customer)} • ${escapeHtml(type)} • ${escapeHtml(table)}</small>
                  </div>
                  <div class="text-end">
                    <div class="fw-bold">${money(total)}</div>
                    <div class="btn-group btn-group-sm mt-2">
                      <button class="btn btn-outline-primary" onclick="resumeHeldBill(${h.id})">
                        <i class="fas fa-play"></i> Resume
                      </button>
                      <button class="btn btn-outline-danger" onclick="deleteHeldBill(${h.id})">
                        <i class="fas fa-trash"></i> Delete
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            `;
                })
                .join("")}
      </div>
    `;
    };

    const renderKitchenOrders = (list) => {
        if (!el.kitchenOrdersList) return;

        if (!list.length) {
            el.kitchenOrdersList.innerHTML = `<div class="text-center text-muted py-4">No kitchen orders</div>`;
            return;
        }

        el.kitchenOrdersList.innerHTML = `
      <div class="list-group">
        ${list
                .map((o) => {
                    const items = o.items ?? o.payload?.items ?? [];
                    const customer = o.customer_name ?? o.payload?.customer_name ?? "Walk-in Customer";
                    const type = o.order_type ?? o.payload?.order_type ?? "";
                    const tableId = o.table_id ?? o.payload?.table_id;
                    const table = tableId ? `Table #${tableId}` : "No Table";
                    const totalTime =
                        o.max_cooking_time ??
                        items.reduce((mx, it) => Math.max(mx, Number(it.cooking_time || 0)), 0);

                    return `
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fw-semibold">Kitchen Order #${o.id}</div>
                    <small class="text-muted">${escapeHtml(customer)} • ${escapeHtml(type)} • ${escapeHtml(table)}</small>
                    <div class="mt-2">
                      ${items
                            .map((it) => `<span class="badge bg-light text-dark me-1 mb-1">${escapeHtml(it.name)} × ${it.qty}</span>`)
                            .join("")}
                    </div>
                  </div>
                  <div class="text-end">
                    <div class="badge bg-warning text-dark">
                      <i class="fas fa-clock"></i> ${totalTime ? `${totalTime}m` : "—"}
                    </div>
                    <div class="btn-group btn-group-sm mt-2">
                      <button class="btn btn-outline-success" onclick="markKitchenDone(${o.id})">
                        <i class="fas fa-check"></i> Done
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            `;
                })
                .join("")}
      </div>
    `;
    };

    const renderHistory = (list) => {
        if (!el.orderHistoryList) return;

        if (!list.length) {
            el.orderHistoryList.innerHTML = `<div class="text-center text-muted py-4">No orders found</div>`;
            return;
        }

        el.orderHistoryList.innerHTML = `
      <div class="list-group">
        ${list
                .map((o) => {
                    const total = o.total ?? o.payload?.totals?.grand_total ?? 0;
                    const customer = o.customer_name ?? o.payload?.customer_name ?? "Walk-in Customer";
                    const type = o.order_type ?? o.payload?.order_type ?? "";
                    const tableId = o.table_id ?? o.payload?.table_id;
                    const table = tableId ? `Table #${tableId}` : "No Table";
                    return `
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-start">
                  <div>
                    <div class="fw-semibold">Order #${o.id}</div>
                    <small class="text-muted">${escapeHtml(customer)} • ${escapeHtml(type)} • ${escapeHtml(table)}</small>
                  </div>
                  <div class="text-end">
                    <div class="fw-bold">${money(total)}</div>
                    <div class="btn-group btn-group-sm mt-2">
                      <button class="btn btn-outline-secondary" onclick="viewBillDetails(${o.id})">
                        <i class="fas fa-eye"></i> View
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            `;
                })
                .join("")}
      </div>
    `;
    };

    const renderBillModal = (orderId, payload) => {
        if (el.billOrderId) el.billOrderId.textContent = String(orderId);
        if (!el.billDetailsContent) return;

        const p = payload || {};
        const totals = p.totals || {};
        const items = p.items || [];

        const type = p.order_type || "";
        const table = p.table_id ? `Table #${p.table_id}` : "—";
        const customer = p.customer_name || "Walk-in Customer";
        const notes = p.notes || "";

        el.billDetailsContent.innerHTML = `
      <div class="mb-2">
        <div class="d-flex justify-content-between">
          <div>
            <div class="fw-semibold">${escapeHtml(customer)}</div>
            <small class="text-muted">${escapeHtml(type)} • ${escapeHtml(table)}</small>
          </div>
        </div>
      </div>

      <hr>

      <div class="table-responsive">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>Item</th>
              <th class="text-end">Qty</th>
              <th class="text-end">Price</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            ${items
                .map((it) => {
                    const line = Number(it.price) * Number(it.qty);
                    return `
                  <tr>
                    <td>${escapeHtml(it.name)}</td>
                    <td class="text-end">${it.qty}</td>
                    <td class="text-end">${money(it.price)}</td>
                    <td class="text-end">${money(line)}</td>
                  </tr>
                `;
                })
                .join("")}
          </tbody>
        </table>
      </div>

      <div class="mt-2">
        <div class="d-flex justify-content-between"><span>Subtotal</span><strong>${money(totals.subtotal || 0)}</strong></div>
        <div class="d-flex justify-content-between"><span>Service Charge</span><strong>${money(totals.service_charge || 0)}</strong></div>
        <div class="d-flex justify-content-between"><span>VAT</span><strong>${money(totals.vat || 0)}</strong></div>
        <div class="d-flex justify-content-between"><span>Discount</span><strong>${money(p.discount || 0)}</strong></div>
        <hr>
        <div class="d-flex justify-content-between fs-5"><span>Total</span><strong>${money(totals.grand_total || 0)}</strong></div>
      </div>

      ${notes ? `<hr><div><small class="text-muted">Notes: ${escapeHtml(notes)}</small></div>` : ""}
    `;
    };

    const restoreFromPayload = (p) => {
        state.orderType = p.order_type || "dine-in";
        state.selectedTableId = p.table_id || null;
        state.selectedTableName = p.table_id
            ? (tablesCache.find(t => String(t.id) === String(p.table_id))?.name || `Table ${p.table_id}`)
            : null;

        state.customerId = p.customer_id || null;
        state.customerName = p.customer_name || "Walk-in Customer";

        state.paymentMethod = p.payment_method || "cash";
        state.discount = p.discount || 0;
        state.orderNotes = p.notes || "";

        state.cart = (p.items || []).map((it) => ({
            id: it.product_id,
            name: it.name,
            price: it.price,
            qty: it.qty,
            cooking_time: it.cooking_time || 0,
            is_veg: !!it.is_veg,
            image: "", // optional
        }));

        if (el.orderType) el.orderType.value = state.orderType;
        window.toggleTableSelection();

        if (el.selectedTable) el.selectedTable.value = state.selectedTableId ? String(state.selectedTableId) : "";
        renderCart();
    };

    // ----------------------------
    // Loaders (API)
    // ----------------------------
    const loadStats = async () => {
        if (!ENDPOINTS.stats) return;
        try {
            const data = await fetchJSON(ENDPOINTS.stats, { method: "GET" });
            if (el.todaySales) el.todaySales.textContent = money(data?.todaySales ?? 0);
            if (el.todayOrders) el.todayOrders.textContent = String(data?.todayOrders ?? 0);
            if (el.occupiedTables) el.occupiedTables.textContent = String(data?.occupiedTables ?? 0);
            if (el.totalTables) el.totalTables.textContent = String(data?.totalTables ?? el.totalTables?.textContent ?? 0);
            if (el.kitchenOrders) el.kitchenOrders.textContent = String(data?.kitchenOrders ?? 0);
        } catch (e) {
            toast(e.message || "Failed to load stats", "warning");
        }
    };

    const loadHeldBills = async (render = false) => {
        if (!ENDPOINTS.held) return;
        try {
            const data = await fetchJSON(ENDPOINTS.held, { method: "GET" });
            heldCache = data?.held || data || [];
            if (el.heldBadge) el.heldBadge.textContent = String(heldCache.length);
            if (render) renderHeldBills(heldCache);
        } catch (e) {
            toast(e.message || "Failed to load held bills", "warning");
        }
    };

    const loadKitchenOrders = async (render = false) => {
        if (!ENDPOINTS.kitchen) return;
        try {
            const data = await fetchJSON(ENDPOINTS.kitchen, { method: "GET" });
            kitchenCache = data?.orders || data || [];
            if (el.kitchenOrders) el.kitchenOrders.textContent = String(kitchenCache.length);
            if (render) renderKitchenOrders(kitchenCache);
        } catch (e) {
            toast(e.message || "Failed to load kitchen orders", "warning");
        }
    };

    const loadHistory = async (render = false) => {
        if (!ENDPOINTS.history) return;
        try {
            const data = await fetchJSON(ENDPOINTS.history, { method: "GET" });
            historyCache = data?.orders || data || [];
            if (render) renderHistory(historyCache);
        } catch (e) {
            toast(e.message || "Failed to load history", "warning");
        }
    };

    const cacheTablesFromGrid = () => {
        const cards = $$("#tableGrid .table-card");
        const tables = cards
            .map((card) => {
                const title = $(".card-title", card)?.textContent?.trim() || "Table";
                const idMatch = card.getAttribute("onclick")?.match(/selectTable\((\d+)\)/);
                const id = idMatch ? Number(idMatch[1]) : null;
                const badge = $(".badge", card)?.textContent?.trim()?.toLowerCase() || "available";
                return { id, name: title, status: badge };
            })
            .filter((t) => t.id !== null);

        tablesCache = tables;
        populateTableSelect();
    };

    // ----------------------------
    // Events
    // ----------------------------
    const bindEvents = () => {
        // Product search (client-side filter using DOM)
        if (el.searchProduct) {
            el.searchProduct.addEventListener("input", () => {
                const q = (el.searchProduct.value || "").trim().toLowerCase();
                $$(".product-item").forEach((item) => {
                    const name = (item.dataset.name || "").toLowerCase();
                    item.style.display = !q || name.includes(q) ? "" : "none";
                });
            });
        }

        // Discount
        if (el.discount) {
            el.discount.addEventListener("input", () => {
                state.discount = Number(el.discount.value || 0);
                renderTotals();
            });
        }

        // Notes
        if (el.orderNotes) {
            el.orderNotes.addEventListener("input", () => {
                state.orderNotes = el.orderNotes.value || "";
            });
        }

        // Payment method
        if (el.paymentMethod) {
            el.paymentMethod.addEventListener("change", () => {
                state.paymentMethod = el.paymentMethod.value || "cash";
            });
        }

        // Order type
        if (el.orderType) {
            el.orderType.addEventListener("change", () => {
                window.toggleTableSelection();
            });
        }

        // Table dropdown
        if (el.selectedTable) {
            el.selectedTable.addEventListener("change", () => {
                const id = el.selectedTable.value ? Number(el.selectedTable.value) : null;
                if (!id) {
                    state.selectedTableId = null;
                    state.selectedTableName = null;
                } else {
                    const t = tablesCache.find((x) => String(x.id) === String(id));
                    state.selectedTableId = id;
                    state.selectedTableName = t?.name || `Table ${id}`;
                }
                renderCart();
            });
        }
    };

    // ----------------------------
    // Init
    // ----------------------------
    const init = async () => {
        initModals();

        // Table list already in Blade, so derive from DOM
        cacheTablesFromGrid();

        // initial UI state
        if (el.orderType) el.orderType.value = state.orderType;
        if (el.paymentMethod) el.paymentMethod.value = state.paymentMethod;
        window.toggleTableSelection();
        renderCart();
        bindEvents();

        // Load dynamic server data
        await loadStats();
        await loadHeldBills(false);
        await loadKitchenOrders(false);
        await loadHistory(false);

        // refresh stats periodically
        if (ENDPOINTS.stats) setInterval(loadStats, 30000);
    };

    document.addEventListener("DOMContentLoaded", init);
})();
