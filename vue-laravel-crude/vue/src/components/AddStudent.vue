<template>
  <div class="form-container">
    <div class="form-card">
      <h3>Student Registration</h3>
      <form @submit.prevent="submitForm">

        <!-- Name -->
        <div class="form-group">
          <label>Name</label>
          <input type="text" v-model="student.name" placeholder="Enter student name" />
        </div>

        <!-- Course -->
        <div class="form-group">
          <label>Course</label>
          <input type="text" v-model="student.course" placeholder="Enter course name" />
        </div>

        <!-- Email -->
        <div class="form-group">
          <label>Email</label>
          <input type="email" v-model="student.email" placeholder="Enter email address" />
        </div>

        <!-- Phone -->
        <div class="form-group">
          <label>Phone</label>
          <input type="text" v-model="student.phone" placeholder="Enter phone number" />
        </div>

        <button type="submit">Submit</button>

      </form>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { reactive } from "vue";

export default {
  name: "AddStudent",
  setup() {

    const student = reactive({
      name: "",
      course: "",
      email: "",
      phone: "",
    });

    const submitForm = async () => {
      try {
        const response = await axios.post("http://localhost:8000/api/students", student);
        alert("Student added successfully!");
        student.name = "";
        student.course = "";
        student.email = "";
        student.phone = "";
      } catch (error) {
        console.error(error);
        alert("Failed to add student. Please check the console for details.");
      }
    };

    return {
      student,
      submitForm,
    };
  },
};
</script>





<style>
/* Make all elements include padding & border in width */
*, *::before, *::after {
  box-sizing: border-box;
}

/* Container center */
.form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #f0f4f8;
  padding: 30px;
}

/* Card styling */
.form-card {
  background: #fff;
  padding: 40px 30px;
  border-radius: 14px;
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
  width: 100%;
  max-width: 500px;
}

.form-card h3 {
  text-align: center;
  margin-bottom: 30px;
  font-weight: 700;
  color: #333;
}

/* Form groups */
.form-group {
  margin-bottom: 22px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #555;
}

.form-group input {
  width: 100%;
  padding: 14px 16px;
  border-radius: 10px;
  border: 1px solid #ccc;
  font-size: 16px;
  outline: none;
  transition: all 0.3s ease;
  display: block; /* Ensure input takes full width */
}

.form-group input:focus {
  border-color: #007bff;
  box-shadow: 0 0 6px rgba(0, 123, 255, 0.3);
}

/* Button styling */
button {
  width: 100%;
  padding: 16px;
  margin-top: 10px;
  background-color: #007bff;
  color: #fff;
  font-size: 18px;
  font-weight: 600;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  transition: all 0.3s ease;
}

button:hover {
  background-color: #0056b3;
}
</style>

