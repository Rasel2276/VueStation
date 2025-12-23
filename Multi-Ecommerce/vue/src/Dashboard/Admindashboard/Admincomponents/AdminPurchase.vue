<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Multi Product Purchase</h2>

      <form class="form" @submit.prevent="submitForm">
        <div v-for="(purchase, index) in purchases" :key="index" class="purchase-row">
          <div class="field-row">
            <div class="field">
              <label>Supplier</label>
              <select v-model="purchase.supplier_id">
                <option value="">Select Supplier</option>
                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                  {{ supplier.supplier_name }}
                </option>
              </select>
            </div>

            <div class="field">
              <label>Product</label>
              <select v-model="purchase.product_id">
                <option value="">Select Product</option>
                <option v-for="product in products" :key="product.id" :value="product.id">
                  {{ product.product_name }}
                </option>
              </select>
            </div>
          </div>

          <div class="field-row">
            <div class="field">
              <label>Quantity</label>
              <input type="number" v-model.number="purchase.quantity" min="0" />
            </div>

            <div class="field">
              <label>Purchase Price</label>
              <input type="number" v-model.number="purchase.purchase_price" min="0" step="0.01" />
            </div>

            <div class="field">
              <label>Vendor Sale Price</label>
              <input type="number" v-model.number="purchase.vendor_sale_price" min="0" step="0.01" />
            </div>

            <div class="field">
              <label>Total Price</label>
              <input type="number" :value="purchaseTotal(purchase)" readonly />
            </div>
          </div>

          <div class="btn-wrapper">
            <button type="button" class="add-btn" @click="addRow">+ Add Product</button>
          </div>

          <hr />
        </div>

        <div class="btn-wrapper">
          <button class="btn" type="submit">Submit Purchase</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import api from "../../../axios"; // Correct relative path

export default {
  name: "AdminPurchase",
  data() {
    return {
      suppliers: [],
      products: [],
      purchases: [
        {
          supplier_id: "",
          product_id: "",
          quantity: 0,
          purchase_price: 0,
          vendor_sale_price: 0,
        },
      ],
    };
  },
  mounted() {
    this.fetchSuppliers();
    this.fetchProducts();
  },
  methods: {
    addRow() {
      this.purchases.push({
        supplier_id: "",
        product_id: "",
        quantity: 0,
        purchase_price: 0,
        vendor_sale_price: 0,
      });
    },
    purchaseTotal(purchase) {
      return (purchase.quantity || 0) * (purchase.purchase_price || 0);
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
    submitForm() {
      this.$router.push({
        path: "/inventory/purchase-payment",
        query: { purchases: JSON.stringify(this.purchases) },
      });
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
  max-width: 950px;
  background: #fff;
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
  gap: 20px;
}

.field-row {
  display: flex;
  gap: 20px;
  flex-wrap: wrap;
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
.field select {
  padding: 12px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 14px;
}

.field input:focus,
.field select:focus {
  outline: none;
  border-color: #3b82f6;
}

.btn-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-top: 10px;
}

.btn,
.add-btn {
  background: #3b82f6;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  transition: 0.3s;
}

.btn:hover,
.add-btn:hover {
  background: #2563eb;
}

hr {
  margin: 20px 0;
  border: 0.5px solid #e5e7eb;
}

@media (max-width: 768px) {
  .field-row {
    flex-direction: column;
  }
}
</style>
