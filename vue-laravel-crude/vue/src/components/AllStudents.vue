<template>

  <div class="container">
    <h3 class="text-center mb-4">Students List</h3>

    <p v-if="loading" class="text-center">Loading...</p>

    <table v-else-if="students.length" class="students-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Course</th>
          <th>Email</th>
          <th>Phone</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="student in students" :key="student.id">
          <td>{{ student.id }}</td>
          <td>{{ student.name }}</td>
          <td>{{ student.course }}</td>
          <td>{{ student.email }}</td>
          <td>{{ student.phone }}</td>
        </tr>
      </tbody>
    </table>

    <p v-else class="text-center">No students found.</p>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";

export default {
  name: "AllStudents",
  setup() {
    const students = ref([]);
    const loading = ref(true);

    const fetchStudents = async () => {
      try {
        const res = await axios.get("http://localhost:8000/api/students");
        if (res.data.status === 200) {
          students.value = res.data.students;
        } else {
          students.value = [];
        }
      } catch (error) {
        console.error("Failed to load students", error);
        students.value = [];
      } finally {
        loading.value = false;
      }
    };

    onMounted(() => {
      fetchStudents();
    });

    return {
      students,
      loading,
    };
  },
};
</script>

<style>
.container {
  max-width: 900px;
  margin: 50px auto;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 12px;
}

h3 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.students-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.students-table th,
.students-table td {
  padding: 12px 15px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

.students-table th {
  background-color: #007bff;
  color: #fff;
  font-weight: 600;
}

.students-table tr:hover {
  background-color: #f1f1f1;
}

p.text-center {
  text-align: center;
  font-style: italic;
  color: #555;
}
</style>
