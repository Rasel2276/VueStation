<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Add Product</h2>

      <form class="form" @submit.prevent="submitForm">

        <!-- Product Name & SKU -->
        <div class="field-row">
          <div class="field">
            <label>Product Name</label>
            <input
              type="text"
              placeholder="Enter product name"
              v-model="form.product_name"
              required
            />
          </div>

          <div class="field">
            <label>SKU</label>
            <input
              type="text"
              placeholder="Enter SKU"
              v-model="form.sku"
            />
          </div>
        </div>

        <!-- Category & Subcategory -->
        <div class="field-row">
          <div class="field">
            <label>Category</label>
            <select v-model="form.category_id" required>
              <option value="">Select Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.category_name }}
              </option>
            </select>
          </div>

          <div class="field">
            <label>Subcategory</label>
            <select v-model="form.subcategory_id" required>
              <option value="">Select Subcategory</option>
              <option v-for="sub in subcategories" :key="sub.id" :value="sub.id">
                {{ sub.subcategory_name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Supplier & Base Price -->
        <div class="field-row">
          <div class="field">
            <label>Supplier</label>
            <select v-model="form.supplier_id">
              <option value="">Select Supplier</option>
              <option v-for="sup in suppliers" :key="sup.id" :value="sup.id">
                {{ sup.supplier_name }}
              </option>
            </select>
          </div>

          <div class="field">
            <label>Base Price</label>
            <input
              type="number"
              placeholder="Enter base price"
              v-model="form.base_price"
            />
          </div>
        </div>

        <!-- Description -->
        <div class="field">
          <label>Description</label>
          <textarea
            rows="4"
            placeholder="Write product description"
            v-model="form.description"
          ></textarea>
        </div>

        <!-- Image, Color & Size -->
        <div class="field-row">
          <div class="field">
            <label>Product Image</label>
            <input type="file" @change="handleImage" />
          </div>

          <div class="field">
            <label>Color</label>
            <input
              type="text"
              placeholder="Enter color"
              v-model="form.color"
            />
          </div>

          <div class="field">
            <label>Size</label>
            <input
              type="text"
              placeholder="Enter size"
              v-model="form.size"
            />
          </div>
        </div>

        <!-- Featured & Status -->
        <div class="field-row">
          <div class="field">
            <label>Featured</label>
            <select v-model="form.featured">
              <option value="No">No</option>
              <option value="Yes">Yes</option>
            </select>
          </div>

          <div class="field">
            <label>Status</label>
            <select v-model="form.status">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <!-- Button -->
        <div class="btn-wrapper">
          <button class="btn" type="submit">
            Save Product
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ProductCreate",

  data() {
    return {
      categories: [],
      subcategories: [],
      suppliers: [],
      form: {
        product_name: "",
        sku: "",
        category_id: "",
        subcategory_id: "",
        supplier_id: "",
        base_price: "",
        description: "",
        product_image: null,
        color: "",
        size: "",
        featured: "No",
        status: "Active",
      },
    };
  },

  mounted() {
    this.fetchCategories();
    this.fetchSubcategories();
    this.fetchSuppliers();
  },

  methods: {
    handleImage(event) {
      this.form.product_image = event.target.files[0];
    },

    async fetchCategories() {
      try {
        const res = await axios.get(
          "http://127.0.0.1:8000/api/admin/categories",
          { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
        );
        this.categories = res.data;
      } catch (err) {
        console.error(err);
      }
    },

    async fetchSubcategories() {
      try {
        const res = await axios.get(
          "http://127.0.0.1:8000/api/admin/subcategories",
          { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
        );
        this.subcategories = res.data;
      } catch (err) {
        console.error(err);
      }
    },

    async fetchSuppliers() {
      try {
        const res = await axios.get(
          "http://127.0.0.1:8000/api/admin/suppliers",
          { headers: { Authorization: `Bearer ${localStorage.getItem("token")}` } }
        );
        this.suppliers = res.data;
      } catch (err) {
        console.error(err);
      }
    },

    async submitForm() {
      const formData = new FormData();
      for (let key in this.form) {
        if (this.form[key] !== null) {
          formData.append(key, this.form[key]);
        }
      }

      try {
        await axios.post(
          "http://127.0.0.1:8000/api/admin/products",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        alert("Product added successfully!");

        // Reset form
        this.form = {
          product_name: "",
          sku: "",
          category_id: "",
          subcategory_id: "",
          supplier_id: "",
          base_price: "",
          description: "",
          product_image: null,
          color: "",
          size: "",
          featured: "No",
          status: "Active",
        };
      } catch (error) {
        alert(
          error.response?.data?.message ||
          JSON.stringify(error.response?.data) ||
          "Unknown error"
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
