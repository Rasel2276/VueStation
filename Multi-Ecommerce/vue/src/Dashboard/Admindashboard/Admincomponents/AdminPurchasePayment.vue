<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Payment Summary</h2>

      <div class="table-wrapper">
        <div class="table-responsive">
          <table class="custom-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Supplier</th>
                <th>Quantity</th>
                <th>Purchase Price</th>
                <th>Vendor Sale Price</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in purchasesData" :key="index">
                <td data-label="Product" class="bold-text">{{ getProductName(item.product_id) }}</td>
                <td data-label="Supplier">{{ getSupplierName(item.supplier_id) }}</td>
                <td data-label="Quantity">{{ item.quantity }}</td>
                <td data-label="Purchase Price">{{ item.purchase_price.toFixed(2) }}</td>
                <td data-label="Vendor Sale Price">{{ item.vendor_sale_price.toFixed(2) }}</td>
                <td data-label="Total" class="total-cell">{{ itemTotal(item).toFixed(2) }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-field">
          <label>Grand Total:</label>
          <span class="grand-total-amount">{{ grandTotal.toFixed(2) }}</span>
        </div>

        <div class="summary-field">
          <label>Payment Method:</label>
          <select v-model="payment_method" class="payment-select">
            <option value="">Select Method</option>
            <option value="cash">Cash</option>
            <option value="bank">Bank</option>
            <option value="card">Card</option>
          </select>
        </div>

        <div class="btn-wrapper">
          <button class="pay-btn" @click="submitPayment">Pay Now</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api, { BASE_URL } from '../../../axios';

export default {
  name: "AdminPurchasePayment",
  data() {
    return {
      suppliers: [],
      products: [],
      purchasesData: [],
      payment_method: "",
    };
  },
  created() {
    const purchasesQuery = this.$route.query.purchases;
    this.purchasesData = purchasesQuery ? JSON.parse(purchasesQuery) : [];
    this.fetchSuppliers();
    this.fetchProducts();
  },
  computed: {
    grandTotal() {
      return this.purchasesData.reduce((sum, item) => sum + this.itemTotal(item), 0);
    },
  },
  methods: {
    itemTotal(item) {
      return (item.quantity || 0) * (item.purchase_price || 0);
    },
    async fetchSuppliers() {
      try {
        const res = await api.get("/admin/suppliers");
        this.suppliers = res.data;
      } catch (err) {
        console.error(err);
      }
    },
    async fetchProducts() {
      try {
        const res = await api.get("/admin/products");
        this.products = res.data;
      } catch (err) {
        console.error(err);
      }
    },
    getProductName(id) {
      const product = this.products.find((p) => p.id === id);
      return product ? product.product_name : "N/A";
    },
    getSupplierName(id) {
      const supplier = this.suppliers.find((s) => s.id === id);
      return supplier ? supplier.supplier_name : "N/A";
    },
    async submitPayment() {
      if (!this.payment_method) {
        alert("Please select a payment method!");
        return;
      }

      try {
        await api.post("/admin/purchase", {
          purchases: this.purchasesData,
          payment_method: this.payment_method,
        });

        alert("Purchase and Payment completed successfully!");
        this.$router.push("/inventory/Purchase");
      } catch (err) {
        console.error("FULL ERROR:", err);
        if (err.response) {
          alert(JSON.stringify(err.response.data, null, 2));
        } else {
          alert("Network / Axios error");
        }
      }
    },
  },
};
</script>

<style scoped>
/* 🔒 DESKTOP & GENERAL STYLES */
.page {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 15px;
  background-color: #f8fafc;
}

.card {
  width: 100%;
  max-width: 950px;
  background: #fff;
  padding: 35px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 30px;
  font-weight: 700;
  color: #1e293b;
}

.table-responsive {
  overflow-x: auto;
  margin-bottom: 30px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
}

.custom-table th,
.custom-table td {
  padding: 14px 15px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
  font-size: 14px;
}

.custom-table th {
  background-color: #f8fafc;
  font-weight: 600;
  color: #475569;
}

.total-cell {
  font-weight: 700;
  color: #2563eb;
}

/* SUMMARY SECTION */
.summary-card {
  background: #f8fafc;
  padding: 20px;
  border-radius: 10px;
  border: 1px solid #e2e8f0;
  max-width: 400px;
  margin-left: auto;
}

.summary-field {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 15px;
}

.summary-field label {
  font-weight: 600;
  color: #475569;
}

.grand-total-amount {
  font-size: 20px;
  font-weight: 800;
  color: #1e293b;
}

.payment-select {
  padding: 8px 12px;
  border-radius: 6px;
  border: 1px solid #cbd5e1;
  outline: none;
  width: 180px;
}

.pay-btn {
  background: #1e293b;
  color: #fff;
  padding: 12px 25px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  font-weight: 700;
  width: 100%;
  transition: 0.3s;
}

.pay-btn:hover {
  background: #000;
  transform: translateY(-2px);
}

/* 📱 MOBILE RESPONSIVE (Label-Value Format) */
@media (max-width: 768px) {
  .page { padding: 15px 10px; }
  .card { padding: 20px 15px; }

  .custom-table thead { display: none; } /* টেবিল হেডার হাইড */

  .custom-table tr {
    display: block;
    border: 1px solid #e2e8f0;
    margin-bottom: 15px;
    border-radius: 10px;
    padding: 10px;
    background: #fff;
  }

  .custom-table td {
    display: flex;
    justify-content: flex-start;
    padding: 10px 5px;
    border-bottom: 1px solid #f1f5f9;
  }

  .custom-table td:last-child { border-bottom: none; }

  .custom-table td::before {
    content: attr(data-label);
    font-weight: 700;
    color: #64748b;
    font-size: 11px;
    text-transform: uppercase;
    width: 40%;
    flex-shrink: 0;
  }

  .summary-card {
    max-width: 100%;
    margin-top: 20px;
  }

  .payment-select {
    width: 60%;
  }
}

@media (max-width: 375px) {
  .title { font-size: 20px; }
}
</style>