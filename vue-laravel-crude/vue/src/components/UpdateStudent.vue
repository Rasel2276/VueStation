<template>
  <div class="update-container">
    <div class="update-card">
      <h2>Update Student</h2>

      <form @submit.prevent="updateStudent">

        <div class="form-group">
          <label>Name</label>
          <input v-model="student.name" type="text" placeholder="Enter Name" />
        </div>

        <div class="form-group">
          <label>Course</label>
          <input v-model="student.course" type="text" placeholder="Enter Course" />
        </div>

        <div class="form-group">
          <label>Email</label>
          <input v-model="student.email" type="email" placeholder="Enter Email" />
        </div>

        <div class="form-group">
          <label>Phone</label>
          <input v-model="student.phone" type="text" placeholder="Enter Phone" />
        </div>

        <button type="submit">Update Student</button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";

const route = useRoute();
const router = useRouter();

const student = reactive({
  name: "",
  course: "",
  email: "",
  phone: ""
});

const id = route.params.id;

// Fetch single student data
onMounted(async () => {
  try {
    const res = await axios.get(`http://localhost:8000/api/students/${id}`);
    if(res.data.status === 200){
      Object.assign(student, res.data.student);
    } else {
      alert("Student not found");
      router.push("/students");
    }
  } catch (error) {
    console.error(error);
    alert("Failed to load student data");
    router.push("/students");
  }
});

// Update student
const updateStudent = async () => {
  try {
    const res = await axios.put(`http://localhost:8000/api/students/${id}`, student);
    alert("Student updated successfully!");
    router.push("/students"); // Redirect back to student list
  } catch (error) {
    console.error(error);
    alert("Update failed!");
  }
};
</script>

<style>
.update-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background: #f5f7fa;
  padding: 20px;
}

.update-card {
  background: #fff;
  padding: 30px 25px;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 450px;
}

h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #333;
}

.form-group {
  margin-bottom: 18px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 600;
  color: #444;
}

.form-group input {
  width: 100%;
  padding: 12px 14px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-size: 15px;
  transition: all 0.3s ease;
}

.form-group input:focus {
  border-color: #007bff;
  box-shadow: 0 0 5px rgba(0,123,255,0.3);
  outline: none;
}

button {
  width: 100%;
  padding: 14px;
  background: #007bff;
  border: none;
  color: #fff;
  font-size: 16px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
}

button:hover {
  background: #0056b3;
}
</style>
