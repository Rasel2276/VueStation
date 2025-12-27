<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Supplier Product Return</h2>

      <form class="form" @submit.prevent="submitForm">

        <!-- Product & Supplier -->
        <div class="field-row">
          <div class="field">
            <label>Product</label>
            <select v-model="form.product_id" @change="autoSelectSupplier">
              <option value="">Select Product</option>
              <option
                v-for="product in products"
                :key="product.id"
                :value="product.id"
              >
                {{ product.name }} (Stock: {{ product.stock }})
              </option>
            </select>
          </div>

          <div class="field">
            <label>Supplier (Auto)</label>
            <select v-model="form.supplier_id" disabled>
              <option value="">Auto Selected</option>
              <option
                v-for="supplier in suppliers"
                :key="supplier.id"
                :value="supplier.id"
              >
                {{ supplier.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Quantity -->
        <div class="field-row">
          <div class="field">
            <label>Return Quantity</label>
            <input
              type="number"
              min="1"
              placeholder="Enter return quantity"
              v-model="form.quantity"
            />
          </div>
        </div>

        <!-- Reason -->
        <div class="field">
          <label>Return Reason</label>
          <textarea
            rows="4"
            placeholder="Write return reason (optional)"
            v-model="form.reason"
          ></textarea>
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
      productSupplierMap: {},
      stocks: {},
      form: {
        product_id: "",
        supplier_id: "",
        quantity: "",
        reason: "",
      },
    };
  },

  mounted() {
    this.loadStocks();
    this.loadProductsAndSuppliers();
  },

  methods: {
    async loadStocks() {
      const res = await axios.get("http://127.0.0.1:8000/api/admin/stock", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });

      const map = {};
      res.data.forEach(s => {
        map[s.product_id] = (map[s.product_id] || 0) + Number(s.quantity);
      });

      this.stocks = map;
    },

    async loadProductsAndSuppliers() {
      const res = await axios.get("http://127.0.0.1:8000/api/admin/purchase", {
        headers: {
          Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
      });

      const productMap = {};
      const supplierMap = {};
      const productSupplier = {};

      res.data.forEach(p => {
        productMap[p.product_id] = {
          id: p.product_id,
          name: p.product?.product_name || "Unknown Product",
          stock: this.stocks[p.product_id] || 0,
        };

        supplierMap[p.supplier_id] = {
          id: p.supplier_id,
          name: p.supplier?.supplier_name || "Unknown Supplier",
        };

        // ⭐ map product -> supplier
        productSupplier[p.product_id] = p.supplier_id;
      });

      this.products = Object.values(productMap);
      this.suppliers = Object.values(supplierMap);
      this.productSupplierMap = productSupplier;
    },

    // ⭐ AUTO SUPPLIER SELECT
    autoSelectSupplier() {
      this.form.supplier_id =
        this.productSupplierMap[this.form.product_id] || "";
    },

    async submitForm() {
      try {
        await axios.post(
          "http://127.0.0.1:8000/api/admin/purchase/return",
          this.form,
          {
            headers: {
              Accept: "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        alert("Product returned to supplier successfully!");

        this.form = {
          product_id: "",
          supplier_id: "",
          quantity: "",
          reason: "",
        };
      } catch (error) {
        alert(error.response?.data?.message || "Something went wrong");
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
}
.field input,
.field select,
.field textarea {
  padding: 12px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
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
}
</style>
