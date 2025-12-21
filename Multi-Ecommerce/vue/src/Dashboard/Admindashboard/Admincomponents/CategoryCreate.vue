<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Create Category</h2>

      <form class="form" @submit.prevent="submitForm">
        <!-- Name & Slug -->
        <div class="field-row">
          <div class="field half">
            <input
              type="text"
              placeholder="Category Name"
              v-model="form.category_name"
            />
          </div>
          <div class="field half">
            <input
              type="text"
              placeholder="Slug"
              v-model="form.slug"
            />
          </div>
        </div>

        <!-- Description -->
        <div class="field">
          <textarea
            rows="4"
            placeholder="Description"
            v-model="form.description"
          ></textarea>
        </div>

        <!-- Status & Image -->
        <div class="field-row">
          <div class="field half">
            <select v-model="form.status">
              <option value="">Select Status</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

          <div class="field half">
            <input type="file" @change="handleImage" />
          </div>
        </div>

        <!-- Button -->
        <div class="btn-wrapper">
          <button class="btn" type="submit">Save Category</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "CategoryCreate",

  data() {
    return {
      form: {
        category_name: "",
        slug: "",
        description: "",
        status: "",
        category_image: null,
      },
    };
  },

  methods: {
    handleImage(event) {
      this.form.category_image = event.target.files[0];
    },

    async submitForm() {
      const formData = new FormData();
      formData.append("category_name", this.form.category_name);
      formData.append("slug", this.form.slug);
      formData.append("description", this.form.description);
      formData.append("status", this.form.status);

      if (this.form.category_image) {
        formData.append("category_image", this.form.category_image);
      }

      try {
        await axios.post(
          "http://127.0.0.1:8000/api/admin/categories",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        alert("Category added successfully!");

        // Reset form
        this.form = {
          category_name: "",
          slug: "",
          description: "",
          status: "",
          category_image: null,
        };
      } catch (error) {
        console.log("STATUS:", error.response?.status);
        console.log("DATA:", error.response?.data);
        console.log("FULL ERROR:", error);

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
  min-height: 60vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 30px 0;
  font-family: "Segoe UI", system-ui, Arial, sans-serif;
  background: #f9fafb;
}

.card {
  width: 100%;
  max-width: 880px;
  background: #ffffff;
  padding: 30px 35px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.title {
  text-align: center;
  color: #222;
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 25px;
}

.form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.field input,
.field textarea,
.field select {
  width: 100%;
  padding: 12px 14px;
  background: #ffffff;
  border: 1px solid #d1d5db;
  color: #111;
  font-size: 14px;
  border-radius: 6px;
  outline: none;
  transition: 0.25s;
}

.field input::placeholder,
.field textarea::placeholder {
  color: #6b7280;
}

.field input:focus,
.field textarea:focus,
.field select:focus {
  border-color: #3b82f6;
  background: #ffffff;
}

.field input[type="file"] {
  padding: 8px;
}

.field-row {
  display: flex;
  gap: 40px;
}

.field-row .half {
  flex: 1;
}

.btn-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 28px;
}

.btn {
  width: 140px;
  padding: 10px 0;
  background: #3b82f6;
  border: none;
  border-radius: 6px;
  font-size: 15px;
  font-weight: 600;
  color: white;
  cursor: pointer;
  transition: 0.3s;
}

.btn:hover {
  background: #2563eb;
}

@media (max-width: 768px) {
  .field-row {
    flex-direction: column;
    gap: 15px;
  }

  .btn {
    width: 100%;
  }
}
</style>
