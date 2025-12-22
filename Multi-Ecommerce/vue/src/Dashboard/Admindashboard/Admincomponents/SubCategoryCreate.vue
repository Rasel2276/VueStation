<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Create Subcategory</h2>

      <form class="form" @submit.prevent="submitForm">

        <!-- Parent Category & Subcategory Name Row -->
        <div class="field-row">
          <div class="field half">
            <select v-model="form.parent_category_id" required>
              <option value="">Select Parent Category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.category_name }}
              </option>
            </select>
          </div>
          <div class="field half">
            <input type="text" placeholder="Subcategory Name" v-model="form.subcategory_name" required />
          </div>
        </div>

        <!-- Slug -->
        <div class="field">
          <input type="text" placeholder="Slug (optional)" v-model="form.slug" />
        </div>

        <!-- Description -->
        <div class="field">
          <textarea rows="4" placeholder="Description" v-model="form.description"></textarea>
        </div>

        <!-- Status and Image Row -->
        <div class="field-row">
          <div class="field half">
            <select v-model="form.status" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="field half">
            <input type="file" @change="handleFileUpload" />
          </div>
        </div>

        <!-- Button -->
        <div class="btn-wrapper">
          <button class="btn" type="submit">Save Subcategory</button>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: "SubcategoryCreate",
  data() {
    return {
      categories: [],
      form: {
        parent_category_id: '',
        subcategory_name: '',
        slug: '',
        description: '',
        status: 'Active',
        subcategory_image: null
      },
      token: ''
    };
  },
  mounted() {
    // Login token fetch
    this.token = localStorage.getItem('token')?.trim();
    this.fetchCategories();
  },
  methods: {
    // Fetch categories from API
    async fetchCategories() {
      try {
        const res = await axios.get('/api/admin/categories', {
          headers: {
            Authorization: `Bearer ${this.token}`,
            Accept: 'application/json'
          }
        });
        this.categories = res.data;
      } catch (error) {
        console.error(error.response?.data);
        alert('Failed to load categories');
      }
    },

    // File upload handler
    handleFileUpload(event) {
      this.form.subcategory_image = event.target.files[0];
    },

    // Submit form
    async submitForm() {
      try {
        let formData = new FormData();
        formData.append('parent_category_id', this.form.parent_category_id);
        formData.append('subcategory_name', this.form.subcategory_name);
        formData.append('slug', this.form.slug || this.form.subcategory_name.replace(/\s+/g, '-').toLowerCase());
        formData.append('description', this.form.description);
        formData.append('status', this.form.status);
        if(this.form.subcategory_image) {
          formData.append('subcategory_image', this.form.subcategory_image);
        }

        const res = await axios.post('/api/admin/subcategories', formData, {
          headers: {
            Authorization: `Bearer ${this.token}`,
            'Content-Type': 'multipart/form-data',
            Accept: 'application/json'
          }
        });

        alert(res.data.message);

        // Clear form
        this.form = {
          parent_category_id: '',
          subcategory_name: '',
          slug: '',
          description: '',
          status: 'Active',
          subcategory_image: null
        };

      } catch (error) {
        console.error(error.response?.data);
        alert('Error: ' + (error.response?.data.message || 'Failed to create subcategory'));
      }
    }
  }
};
</script>

<style scoped>
.page { min-height: 80vh; display: flex; justify-content: center; align-items: flex-start; padding: 40px 0; font-family: "Segoe UI", system-ui, Arial, sans-serif; background: #f9fafb; }
.card { width: 100%; max-width: 880px; background: #fff; padding: 30px 35px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.title { text-align: center; color: #222; font-size: 24px; font-weight: 600; margin-bottom: 25px; }
.form { display: flex; flex-direction: column; gap: 20px; }
.field input, .field textarea, .field select { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; outline: none; transition: 0.25s; }
.field input:focus, .field textarea:focus, .field select:focus { border-color: #3b82f6; }
.field input[type="file"] { padding: 8px; }
.field-row { display: flex; gap: 40px; }
.field-row .half { flex: 1; }
.btn-wrapper { display: flex; justify-content: center; margin-top: 28px; }
.btn { width: 140px; padding: 10px 0; background: #3b82f6; border: none; border-radius: 6px; font-size: 15px; font-weight: 600; color: white; cursor: pointer; transition: 0.3s; }
.btn:hover { background: #2563eb; }
@media (max-width: 768px) { .field-row { flex-direction: column; gap: 15px; } .btn { width: 100%; } }
</style>
