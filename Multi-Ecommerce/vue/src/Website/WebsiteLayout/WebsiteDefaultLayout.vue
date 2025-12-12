<template>
    <div class="layout-for-website">

        <div class="content-wrapper-website">
            <!-- Top navigation bar -->
            <Navbar />

            <!-- Main content area -->
            <main class="main-content-website">
                <router-view />
            </main>
            <!-- Footer -->
            <Footer />
        </div>
    </div>
</template>

<script setup>
  import Navbar from '../WebsiteLayoutComponent/Navbar.vue'
  import Footer from '../WebsiteLayoutComponent/Footer.vue'
</script>