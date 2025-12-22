<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Create Subcategory</h2>

      <form class="form" @submit.prevent="submitForm">

        <!-- Parent Category & Subcategory Name -->
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

        <!-- Status & Image -->
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

        <div class="btn-wrapper">
          <button class="btn" type="submit">Save Subcategory</button>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubcategoryCreate",
  data() {
    return {
      categories: [],
      form: {
        parent_category_id: "",
        subcategory_name: "",
        slug: "",
        description: "",
        status: "Active",
        subcategory_image: null
      }
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        const res = await this.$axios.get('/admin/categories');
        this.categories = res.data;
      } catch (err) {
        console.error(err.response?.data);
        alert('Failed to load categories');
      }
    },
    handleFileUpload(e) {
      this.form.subcategory_image = e.target.files[0];
    },
    async submitForm() {
      try {
        const formData = new FormData();
        formData.append("parent_category_id", this.form.parent_category_id);
        formData.append("subcategory_name", this.form.subcategory_name);
        formData.append(
          "slug",
          this.form.slug || this.form.subcategory_name.replace(/\s+/g, "-").toLowerCase()
        );
        formData.append("description", this.form.description);
        formData.append("status", this.form.status);

        if (this.form.subcategory_image) {
          formData.append("subcategory_image", this.form.subcategory_image);
        }

        const res = await this.$axios.post('/admin/subcategories', formData, {
          headers: { 'Content-Type': 'multipart/form-data' }
        });

        alert(res.data.message);

        // Reset form
        this.form = {
          parent_category_id: "",
          subcategory_name: "",
          slug: "",
          description: "",
          status: "Active",
          subcategory_image: null
        };

      } catch (err) {
        console.error(err.response?.data);
        alert(err.response?.data?.message || "Failed to create subcategory");
      }
    }
  }
};
</script>

<style scoped>
.page { min-height: 80vh; display: flex; justify-content: center; padding: 40px 0; background: #f9fafb; }
.card { width: 100%; max-width: 880px; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
.title { text-align: center; margin-bottom: 25px; }
.form { display: flex; flex-direction: column; gap: 20px; }
.field-row { display: flex; gap: 30px; }
.field-row .half { flex: 1; }
.field input, .field textarea, .field select { width: 100%; padding: 12px; border: 1px solid #d1d5db; border-radius: 6px; }
.btn-wrapper { display: flex; justify-content: center; }
.btn { width: 160px; background: #3b82f6; color: white; border: none; padding: 10px; border-radius: 6px; cursor: pointer; }
@media (max-width: 768px) { .field-row { flex-direction: column; } }
</style>
