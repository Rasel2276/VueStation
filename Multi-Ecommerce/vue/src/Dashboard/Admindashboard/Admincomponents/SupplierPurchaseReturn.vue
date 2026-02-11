<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Supplier Product Return</h2>

      <form class="form" @submit.prevent="submitForm">

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
            <select v-model="form.supplier_id" disabled class="disabled-select">
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

        <div class="field">
          <label>Return Reason</label>
          <textarea
            rows="4"
            placeholder="Write return reason (optional)"
            v-model="form.reason"
          ></textarea>
        </div>

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
      try {
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
      } catch (err) {
        console.error("Stock load error:", err);
      }
    },

    async loadProductsAndSuppliers() {
      try {
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

          productSupplier[p.product_id] = p.supplier_id;
        });

        this.products = Object.values(productMap);
        this.suppliers = Object.values(supplierMap);
        this.productSupplierMap = productSupplier;
      } catch (err) {
        console.error("Purchase data load error:", err);
      }
    },

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
/* 🔒 ডেস্কটপ ডিজাইন - আগের পিক্সেল পারফেকশন বজায় রাখা হয়েছে */
.page {
  min-height: 100vh; /* স্ক্রিন ভরাট রাখার জন্য */
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 15px;
  background-color: #f8fafc; /* হালকা ব্যাকগ্রাউন্ড */
}

.card {
  width: 100%;
  max-width: 900px;
  background: #ffffff;
  padding: 35px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
  box-sizing: border-box;
}

.title {
  text-align: center;
  font-size: 26px;
  margin-bottom: 30px;
  font-weight: 700;
  color: #1e293b;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 22px;
}

.field-row {
  display: flex;
  gap: 25px; /* ডেস্কটপে ইনপুটগুলোর মাঝখানের গ্যাপ */
}

.field {
  flex: 1;
  display: flex;
  flex-direction: column;
}

.field label {
  font-size: 14px;
  margin-bottom: 8px;
  font-weight: 600;
  color: #4b5563;
}

.field input,
.field select,
.field textarea {
  padding: 12px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 14px;
  width: 100%;
  box-sizing: border-box;
  transition: border-color 0.2s;
}

.field input:focus,
.field select:focus,
.field textarea:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.disabled-select {
  background-color: #f3f4f6;
  cursor: not-allowed;
}

.btn-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 10px;
}

.btn {
  background: #3b82f6;
  color: #fff;
  padding: 14px 40px;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 700;
  font-size: 16px;
  transition: 0.3s;
  width: 100%; /* মোবাইলে ডিফল্ট ফুল উইডথ হবে */
  max-width: 300px; /* ডেস্কটপে যাতে বেশি বড় না হয় */
}

.btn:hover {
  background: #2563eb;
  transform: translateY(-1px);
}

/* 📱 মোবাইল এবং iPhone SE রেসপন্সিভনেস */
@media (max-width: 768px) {
  .page {
    padding: 20px 10px;
  }
  
  .card {
    padding: 25px 15px;
  }

  .title {
    font-size: 22px;
  }

  .field-row {
    flex-direction: column; /* মোবাইলে ইনপুটগুলো একটার নিচে একটা আসবে */
    gap: 15px;
  }

  .btn {
    max-width: 100%; /* মোবাইলে বাটন ফুল উইডথ */
  }
}

/* iPhone SE স্পেসিফিক ফিক্স */
@media (max-width: 375px) {
  .card {
    padding: 20px 10px;
  }
  .title {
    font-size: 20px;
  }
}
</style>