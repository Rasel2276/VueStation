<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Create Subcategory</h2>

      <form class="form" @submit.prevent="submitForm">

        <div class="field-row">
          <div class="field">
            <label>Parent Category</label>
            <select v-model="form.parent_category_id" required>
              <option value="">Select Parent Category</option>
              <option
                v-for="cat in categories"
                :key="cat.id"
                :value="cat.id"
              >
                {{ cat.category_name }}
              </option>
            </select>
          </div>

          <div class="field">
            <label>Subcategory Name</label>
            <input
              type="text"
              placeholder="Enter subcategory name"
              v-model="form.subcategory_name"
              required
            />
          </div>
        </div>

        <div class="field">
          <label>Slug</label>
          <input
            type="text"
            placeholder="Auto-generated if empty"
            v-model="form.slug"
          />
        </div>

        <div class="field">
          <label>Description</label>
          <textarea
            rows="4"
            placeholder="Write short description"
            v-model="form.description"
          ></textarea>
        </div>

        <div class="field-row">
          <div class="field">
            <label>Status</label>
            <select v-model="form.status" required>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

          <div class="field">
            <label>Subcategory Image</label>
            <input type="file" @change="handleFileUpload" />
          </div>
        </div>

        <div class="btn-wrapper">
          <button class="btn" type="submit">
            Save Subcategory
          </button>
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
      form: this.defaultForm()
    };
  },

  mounted() {
    this.fetchCategories();
  },

  methods: {
    defaultForm() {
      return {
        parent_category_id: "",
        subcategory_name: "",
        slug: "",
        description: "",
        status: "Active",
        subcategory_image: null
      };
    },

    async fetchCategories() {
      try {
        const res = await this.$axios.get("/admin/categories");
        this.categories = res.data;
      } catch (error) {
        console.error(error);
        alert("Failed to load categories");
      }
    },

    handleFileUpload(event) {
      this.form.subcategory_image = event.target.files[0];
    },

    async submitForm() {
      try {
        const formData = new FormData();

        formData.append("parent_category_id", this.form.parent_category_id);
        formData.append("subcategory_name", this.form.subcategory_name);
        formData.append(
          "slug",
          this.form.slug ||
            this.form.subcategory_name
              .trim()
              .replace(/\s+/g, "-")
              .toLowerCase()
        );
        formData.append("description", this.form.description);
        formData.append("status", this.form.status);

        if (this.form.subcategory_image) {
          formData.append(
            "subcategory_image",
            this.form.subcategory_image
          );
        }

        const res = await this.$axios.post(
          "/admin/subcategories",
          formData,
          { headers: { "Content-Type": "multipart/form-data" } }
        );

        alert(res.data.message);
        this.form = this.defaultForm();

      } catch (error) {
        console.error(error);
        alert(
          error.response?.data?.message ||
          "Failed to create subcategory"
        );
      }
    }
  }
};
</script>

<style scoped>
/* ডেক্সটপ ডিজাইন - ১ পিক্সেলও পরিবর্তন নেই */
.page {
  min-height: 60vh;
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
  width: 100%;
  box-sizing: border-box;
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

/* 📱 FULLY MOBILE RESPONSIVE (iPhone SE & Others) */
@media (max-width: 768px) {
  .page {
    padding: 20px 10px;
  }

  .card {
    padding: 25px 15px;
  }

  .title {
    font-size: 20px;
    margin-bottom: 20px;
  }

  .field-row {
    flex-direction: column; /* মোবাইলে রো কলাম হয়ে যাবে */
    gap: 15px;
  }

  .form {
    gap: 15px;
  }

  .btn {
    width: 100%; /* মোবাইলে বাটন বড় হবে */
    padding: 14px;
  }
}

/* iPhone SE এবং খুব ছোট ফোনের জন্য */
@media (max-width: 375px) {
  .card {
    padding: 20px 10px;
  }
}
</style>