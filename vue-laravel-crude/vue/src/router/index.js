import { createRouter, createWebHistory } from 'vue-router'
import AddStudent from '@/components/AddStudent.vue'
import AllStudents from '@/components/AllStudents.vue'
import UpdateStudent from '@/components/UpdateStudent.vue'
import Home from '@/components/Home.vue'
 
const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path:'/',
      name:'home',
      component:Home
    },
    {
      path:'/addstudent',
      name:'addstudent',
      component:AddStudent
    },
    {
      path:'/allstudents',
      name:'AllStudents',
      component:AllStudents
    },
    {
      path:'/updateStudent/:id',    // <-- here
      name:'UpdateStudent',
      component:UpdateStudent,
      props: true                  // <-- important
    }
  ],
})

export default router
