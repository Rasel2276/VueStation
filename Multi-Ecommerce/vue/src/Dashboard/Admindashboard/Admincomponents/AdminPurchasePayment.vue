<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Payment</h2>

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
              <td>{{ getProductName(item.product_id) }}</td>
              <td>{{ getSupplierName(item.supplier_id) }}</td>
              <td>{{ item.quantity }}</td>
              <td>{{ item.purchase_price.toFixed(2) }}</td>
              <td>{{ item.vendor_sale_price.toFixed(2) }}</td>
              <td>{{ itemTotal(item).toFixed(2) }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="summary">
        <div class="field">
          <label>Grand Total:</label>
          <span>{{ grandTotal.toFixed(2) }}</span>
        </div>

        <div class="field">
          <label>Payment Method:</label>
          <select v-model="payment_method">
            <option value="">Select Method</option>
            <option value="cash">Cash</option>
            <option value="bank">Bank</option>
            <option value="card">Card</option>
          </select>
        </div>

        <div class="btn-wrapper">
          <button class="btn" @click="submitPayment">Pay Now</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from "../../../axios";

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
          console.error("STATUS:", err.response.status);
          console.error("DATA:", err.response.data);
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

.table-responsive {
  overflow-x: auto;
  margin-bottom: 20px;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
}

.custom-table th,
.custom-table td {
  padding: 12px 15px;
  border: 1px solid #e5e7eb;
  text-align: left;
  font-size: 14px;
}

.custom-table th {
  background-color: #f3f4f6;
  font-weight: 600;
}

.summary {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.summary .field {
  display: flex;
  justify-content: space-between;
  font-size: 16px;
}

.btn-wrapper {
  display: flex;
  justify-content: flex-end;
}

.btn {
  background: #3b82f6;
  color: #fff;
  padding: 10px 20px;
  border-radius: 6px;
  border: none;
  cursor: pointer;
}

.btn:hover {
  background: #2563eb;
}
</style>
