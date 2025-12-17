<template>
  <div class="page">
    <div class="card">
      <h2 class="title">Create Subcategory</h2>

      <form class="form">
        <!-- Parent Category & Subcategory Name Row -->
        <div class="field-row">
          <div class="field half">
            <select>
              <option value="">Select Parent Category</option>
              <option value="1">Electronics</option>
              <option value="2">Clothing</option>
              <option value="3">Books</option>
            </select>
          </div>
          <div class="field half">
            <input type="text" placeholder="Subcategory Name" />
          </div>
        </div>

        <!-- Slug -->
        <div class="field">
          <input type="text" placeholder="Slug" />
        </div>

        <!-- Description -->
        <div class="field">
          <textarea rows="4" placeholder="Description"></textarea>
        </div>

        <!-- Status and Image Row -->
        <div class="field-row">
          <div class="field half">
            <select>
              <option value="">Select Status</option>
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
          <div class="field half">
            <input type="file" />
          </div>
        </div>
      </form>

      <!-- Button -->
      <div class="btn-wrapper">
        <button class="btn">Save Subcategory</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "SubcategoryCreate",
};
</script>

<style scoped>
/* ===== PAGE ===== */
.page {
  min-height: 80vh;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  padding: 40px 0;
  font-family: "Segoe UI", system-ui, Arial, sans-serif;
  background: #f9fafb;
}

/* ===== CARD ===== */
.card {
  width: 100%;
  max-width: 880px;
  background: #ffffff;
  padding: 30px 35px;
  border-radius: 8px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* ===== TITLE ===== */
.title {
  text-align: center;
  color: #222;
  font-size: 24px;
  font-weight: 600;
  margin-bottom: 25px;
}

/* ===== FORM ===== */
.form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* ===== FIELD ===== */
.field input,
.field textarea,
.field select {
  width: 100%;
  padding: 12px 14px;
  background: #ffffff; /* white background */
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

/* FILE */
.field input[type="file"] {
  padding: 8px;
}

/* SELECT */
.field select {
  background: #ffffff;
  color: #111;
}

/* FIELD ROW (side by side fields) */
.field-row {
  display: flex;
  gap: 40px; /* gap between fields */
}

.field-row .half {
  flex: 1;
}

/* ===== BUTTON ===== */
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

/* ===== RESPONSIVE ===== */
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