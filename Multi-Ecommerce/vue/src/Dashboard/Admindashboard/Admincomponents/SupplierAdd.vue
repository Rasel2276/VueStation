<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Create Supplier</h2>

      <form class="form" @submit.prevent="submitForm">

        <!-- Supplier Name & Email -->
        <div class="field-row">
          <div class="field">
            <label>Supplier Name</label>
            <input
              type="text"
              placeholder="Enter supplier name"
              v-model="form.supplier_name"
              required
            />
          </div>

          <div class="field">
            <label>Email</label>
            <input
              type="email"
              placeholder="Enter supplier email"
              v-model="form.email"
            />
          </div>
        </div>

        <!-- Phone & Contact Person -->
        <div class="field-row">
          <div class="field">
            <label>Phone</label>
            <input
              type="text"
              placeholder="Enter phone number"
              v-model="form.phone"
            />
          </div>

          <div class="field">
            <label>Contact Person</label>
            <input
              type="text"
              placeholder="Enter contact person"
              v-model="form.contact_person"
            />
          </div>
        </div>

        <!-- Address & Status -->
        <div class="field-row">
          <div class="field">
            <label>Address</label>
            <textarea
              rows="3"
              placeholder="Enter supplier address"
              v-model="form.address"
            ></textarea>
          </div>

          <div class="field">
            <label>Status</label>
            <select v-model="form.status" required>
              <option value="">Select Status</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <!-- Button -->
        <div class="btn-wrapper">
          <button class="btn" type="submit">
            Save Supplier
          </button>
        </div>

      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "SupplierCreate",

  data() {
    return {
      form: {
        supplier_name: "",
        email: "",
        phone: "",
        contact_person: "",
        address: "",
        status: "",
      },
    };
  },

  methods: {
    async submitForm() {
      try {
        await axios.post(
          "http://127.0.0.1:8000/api/admin/suppliers",
          this.form,
          {
            headers: {
              "Content-Type": "application/json",
              Authorization: `Bearer ${localStorage.getItem("token")}`,
            },
          }
        );

        alert("Supplier added successfully!");

        // Reset form
        this.form = {
          supplier_name: "",
          email: "",
          phone: "",
          contact_person: "",
          address: "",
          status: "",
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
  padding: 20px 15px;
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
