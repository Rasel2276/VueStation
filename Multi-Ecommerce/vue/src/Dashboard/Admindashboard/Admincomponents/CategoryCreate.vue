<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Create Category</h2>

      <form class="form" @submit.prevent="submitForm">

        <div class="field-row">
          <div class="field">
            <label>Category Name</label>
            <input
              type="text"
              placeholder="Enter category name"
              v-model="form.category_name"
            />
          </div>

          <div class="field">
            <label>Slug</label>
            <input
              type="text"
              placeholder="Auto-generated if empty"
              v-model="form.slug"
            />
          </div>
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
            <select v-model="form.status">
              <option value="">Select Status</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>

          <div class="field">
            <label>Category Image</label>
            <input type="file" @change="handleImage" class="file-input" />
          </div>
        </div>

        <div class="btn-wrapper">
          <button class="btn" type="submit">
            Save Category
          </button>
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

        this.form = {
          category_name: "",
          slug: "",
          description: "",
          status: "",
          category_image: null,
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
/* ডেক্সটপ ডিজাইন - হুবহু আগের মতোই */
.page {
  min-height: 50vh;
  display: flex;
  justify-content: center;
  padding: 40px 15px;
  box-sizing: border-box; /* padding ও বর্ডার যেন সাইজ না বাড়ায় */
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
  box-sizing: border-box; /* ইনপুট যেন কার্ডের বাইরে না যায় */
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

/* --- FULLY MOBILE RESPONSIVE (iPhone SE & Others) --- */
@media (max-width: 768px) {
  .page {
    padding: 20px 10px; /* মোবাইলে প্যাডিং কমানো হয়েছে */
  }

  .card {
    padding: 20px 15px; /* কার্ডের ভেতরের প্যাডিং কমানো হয়েছে */
  }

  .title {
    font-size: 20px;
    margin-bottom: 20px;
  }

  .field-row {
    flex-direction: column; /* রো থেকে কলামে কনভার্ট */
    gap: 15px;
  }

  .form {
    gap: 15px;
  }

  .btn {
    width: 100%; /* মোবাইলে বাটন ফুল উইডথ হবে */
    padding: 14px;
  }
  
  /* ফাইল ইনপুট মোবাইলে সুন্দর দেখানোর জন্য */
  .field input[type="file"] {
    padding: 8px 0;
    border: none;
  }
}

/* অতি ক্ষুদ্র স্ক্রিনের জন্য (যেমন: iPhone SE) */
@media (max-width: 375px) {
  .card {
    padding: 15px 10px;
  }
  .title {
    font-size: 18px;
  }
}
</style>