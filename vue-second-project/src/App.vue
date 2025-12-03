<template>
  <div class="container">
    <h2>Employee Table</h2>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Age</th>
          <th>Position</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="employee in employees" :key="employee.id">
          <td>{{ employee.id }}</td>
          <td>{{ employee.name }}</td>
          <td>{{ employee.age }}</td>
          <td>{{ employee.position }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import './assets/style.css'
export default {
  name: "App",
  data() {
    return {
      employees: [
        { id: 1, name: "Rahim", age: 25, position: "Developer" },
        { id: 2, name: "Karim", age: 30, position: "Designer" },
        { id: 3, name: "Jamil", age: 28, position: "Manager" },
        { id: 4, name: "Rasel", age: 26, position: "Admin" },
      ],
    };
  },
};
</script>

