 <script setup>
  import AddStudent from './AddStudent.vue';
  import AllStudents from './AllStudents.vue';
 </script>
 <template>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <!-- Brand on left -->
    <a class="navbar-brand" href="#">Navbar</a>

    <!-- Toggler for small screens -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav links on right -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <router-link class="nav-link active" aria-current="page" to="/">Home</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/addstudent">Add Student</router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/AllStudents">All Student</router-link>
        </li>
      </ul>
    </div>
  </div>
</nav>
</template>