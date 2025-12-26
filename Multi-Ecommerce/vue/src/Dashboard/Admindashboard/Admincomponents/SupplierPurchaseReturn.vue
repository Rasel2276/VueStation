<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Supplier Product Return</h2>

      <form class="form" @submit.prevent="submitForm">

        <!-- Purchase ID & Product -->
        <div class="field-row">
          <div class="field">
            <label>Admin Purchase ID</label>
            <input type="number" placeholder="Enter admin purchase id" v-model="form.admin_purchase_id" />
          </div>

          <div class="field">
            <label>Product</label>
            <select v-model="form.product_id">
              <option value="">Select Product</option>
              <option v-for="product in products" :key="product.id" :value="product.id">
                {{ product.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Supplier & Quantity -->
        <div class="field-row">
          <div class="field">
            <label>Supplier</label>
            <select v-model="form.supplier_id">
              <option value="">Select Supplier</option>
              <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                {{ supplier.name }}
              </option>
            </select>
          </div>

          <div class="field">
            <label>Return Quantity</label>
            <input type="number" placeholder="Enter return quantity" v-model="form.quantity" />
          </div>
        </div>

        <!-- Reason -->
        <div class="field">
          <label>Return Reason</label>
          <textarea rows="4" placeholder="Write return reason (optional)" v-model="form.reason"></textarea>
        </div>

        <!-- Button -->
        <div class="btn-wrapper">
          <button class="btn" type="submit">Submit Return</button>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "SupplierPurchaseReturn",

  data() {
    return {
      products: [],
      suppliers: [],
      form: {
        admin_purchase_id: "",
        product_id: "",
        supplier_id: "",
        quantity: "",
        reason: "",
      },
    };
  },

  mounted() {
    this.loadProducts();
    this.loadSuppliers();
  },

  methods: {
    async loadProducts() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/admin/purchase", {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });
        // products map: only purchased products
        this.products = res.data.map(p => ({
          id: p.product_id,
          name: p.product.name // assuming relation exists
        }));
      } catch (err) {
        alert("Failed to load products");
      }
    },

    async loadSuppliers() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/admin/suppliers", {
          headers: { Authorization: `Bearer ${localStorage.getItem("token")}` },
        });
        this.suppliers = res.data;
      } catch (err) {
        alert("Failed to load suppliers");
      }
    },

    async submitForm() {
      try {
        await axios.post(
          "http://127.0.0.1:8000/api/admin/purchase/return",
          this.form,
          { headers: { Accept: "application/json", Authorization: `Bearer ${localStorage.getItem("token")}` } }
        );

        alert("Product returned to supplier successfully!");

        this.form = {
          admin_purchase_id: "",
          product_id: "",
          supplier_id: "",
          quantity: "",
          reason: "",
        };
      } catch (error) {
        alert(
          error.response?.data?.message ||
          JSON.stringify(error.response?.data) ||
          "Something went wrong"
        );
      }
    },
  },
};
</script>


<style scoped>
.page {
  min-height: 50vh;
  display: flex;
  justify-content: center;
  padding: 40px 15px;
}

.card {
  width: 100%;
  max-width: 900px;
  background: #ffffff;
  padding: 35px;
  border-radius: 10px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.title {
  text-align: center;
  font-size: 24px;
  margin-bottom: 30px;
  font-weight: 600;
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
  color: #374151;
}

.field input,
.field select,
.field textarea {
  padding: 12px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 14px;
}

.field input:focus,
.field select:focus,
.field textarea:focus {
  outline: none;
  border-color: #3b82f6;
}

.btn-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 10px;
}

.btn {
  background: #3b82f6;
  color: #ffffff;
  padding: 12px 28px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 15px;
  transition: 0.3s;
}

.btn:hover {
  background: #2563eb;
}

@media (max-width: 768px) {
  .field-row {
    flex-direction: column;
  }
}
</style>
