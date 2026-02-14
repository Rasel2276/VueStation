<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Vendor Product Return</h2>

      <form class="form" @submit.prevent="submitForm">
        <div class="field-row">
          <div class="field">
            <label>Product</label>
            <select v-model="form.product_id" @change="autoSelectSupplier">
              <option value="">Select Product</option>
              <option
                v-for="stock in stocks"
                :key="stock.admin_stock_id"
                :value="stock.admin_stock_id"
              >
                {{ stock.product_name }} (Stock: {{ stock.quantity }})
              </option>
            </select>
          </div>

          <div class="field">
            <label>Supplier (Auto)</label>
            <input type="text" :value="selectedSupplierName" disabled placeholder="Auto Selected" />
          </div>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Return Quantity</label>
            <input type="number" min="1" :max="selectedStockQuantity" placeholder="Enter quantity" v-model="form.quantity" />
            <small v-if="selectedStockQuantity !== null">Available Stock: {{ selectedStockQuantity }}</small>
          </div>
        </div>

        <div class="field">
          <label>Return Reason</label>
          <textarea rows="4" placeholder="Write reason (optional)" v-model="form.reason"></textarea>
        </div>

        <div class="btn-wrapper">
          <button class="btn" type="submit" :disabled="loading">
            {{ loading ? 'Processing...' : 'Submit Return' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import api, { BASE_URL } from '../../../axios';

export default {
  data() {
    return {
      stocks: [],
      suppliers: [],
      loading: false,
      form: {
        product_id: "",
        supplier_id: "",
        quantity: "",
        reason: ""
      },
      token: localStorage.getItem("vendortoken") || localStorage.getItem("token")
    }
  },
  computed: {
    selectedStockQuantity() {
      const stock = this.stocks.find(s => s.admin_stock_id == this.form.product_id);
      return stock ? stock.quantity : null;
    },
    selectedSupplierName() {
      const supplier = this.suppliers.find(s => s.id == this.form.supplier_id);
      return supplier ? supplier.name : '';
    }
  },
  mounted() {
    this.fetchStocks();
    this.fetchSuppliers();
  },
  methods: {
    async fetchStocks() {
      try {
        const res = await api.get("/vendor/my-stocks", {
          headers: { Authorization: `Bearer ${this.token}` }
        });
        this.stocks = res.data.map(s => ({
          admin_stock_id: s.admin_stock_id,
          product_name: s.admin_stock?.product?.product_name || "Unknown",
          quantity: s.quantity,
          supplier_id: s.admin_stock?.admin_id || 1
        }));
      } catch (err) { console.error(err); }
    },
    async fetchSuppliers() {
      try {
        const res = await api.get("/admin/suppliers", {
          headers: { Authorization: `Bearer ${this.token}` }
        });
        this.suppliers = res.data.map(s => ({ id: s.id, name: s.supplier_name }));
      } catch (err) { console.error(err); }
    },
    autoSelectSupplier() {
      const stock = this.stocks.find(s => s.admin_stock_id == this.form.product_id);
      this.form.supplier_id = stock ? stock.supplier_id : "";
    },
    async submitForm() {
      if (this.form.quantity > this.selectedStockQuantity) {
        alert("Stock quantity exceeded!"); return;
      }
      this.loading = true;
      try {
        const res = await api.post("/vendor/returns", this.form, {
          headers: { Authorization: `Bearer ${this.token}` }
        });
        alert(res.data.message);
        this.form = { product_id: "", supplier_id: "", quantity: "", reason: "" };
        this.fetchStocks();
      } catch (err) {
        alert(err.response?.data?.message || "Error");
      } finally { this.loading = false; }
    }
  }
}
</script>

<style scoped>
/* 🔒 DESKTOP DESIGN - UNCHANGED */
.page {
  min-height: 35vh;
  display: flex;
  justify-content: center;
  padding: 40px 15px;
  box-sizing: border-box;
}
.card {
  width: 100%;
  max-width: 900px;
  background: #ffffff;
  padding: 35px;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  box-sizing: border-box;
}
.title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 30px;
}
.form {
  display: flex;
  flex-direction: column;
  gap: 22px;
}
.field-row {
  display: flex;
  gap: 25px;
}
.field {
  flex: 1;
  display: flex;
  flex-direction: column;
}
.field label {
  font-size: 14px;
  margin-bottom: 6px;
  font-weight: 500;
}
.field input,
.field select,
.field textarea {
  padding: 12px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  width: 100%;
  box-sizing: border-box;
  font-size: 14px;
}
.field small {
  font-size: 12px;
  color: #555;
  margin-top: 4px;
}
.btn-wrapper {
  display: flex;
  justify-content: center;
}
.btn {
  background: #3b82f6;
  color: #fff;
  padding: 12px 28px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
  transition: background 0.2s;
}
.btn:hover {
  background: #2563eb;
}
.btn:disabled {
  background: #94a3b8;
  cursor: not-allowed;
}

/* 📱 MOBILE RESPONSIVE - ADDED FOR PERFECT FIT */
@media (max-width: 768px) {
  .page {
    padding: 20px 12px;
  }
  .card {
    padding: 20px;
    border-radius: 8px;
  }
  .title {
    font-size: 20px;
    margin-bottom: 20px;
  }
  .field-row {
    flex-direction: column; /* মোবাইলে পাশাপাশি থেকে ওপর-নিচ হবে */
    gap: 15px;
  }
  .form {
    gap: 15px;
  }
  .btn {
    width: 100%; /* মোবাইলে বাটন ফুল উইথ হবে সহজে প্রেস করার জন্য */
  }
}

/* iPhone SE Specific */
@media (max-width: 380px) {
  .card {
    padding: 15px;
  }
  .field label {
    font-size: 13px;
  }
}
</style>