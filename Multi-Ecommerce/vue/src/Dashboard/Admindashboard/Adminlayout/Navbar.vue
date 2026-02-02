<template>
    <header class="navbar">
        <div class="navbar-left">
            <span class="page-title">{{ pageTitle }}</span>
        </div>

        <div class="navbar-right">
            <div class="user-dropdown" @click="toggleDropdown">
                <span class="user-info">
                    👤 Welcome, {{ adminName }}
                    <span class="arrow">{{ dropdownOpen ? '▾' : '▸' }}</span>
                </span>

                <div v-if="dropdownOpen" class="dropdown-menu">
                    <router-link to="/"><span class="icon">🏠</span> Visit Website</router-link>
                    <a href="#"><span class="icon">⚙️</span> Settings</a>

                    <a href="#" @click.stop.prevent="logout">
                      <span class="icon">🔓</span> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>
</template>


<script setup>
    import { ref, computed, onMounted } from 'vue'
    import { useRoute, useRouter } from 'vue-router'

    const route = useRoute()
    const router = useRouter()

    const pageTitle = computed(() => {
        return route.meta.title || '📊 Dashboard'
    })

    const dropdownOpen = ref(false)
    const adminName = ref('Admin') // ডিফল্ট নাম

    // লোকাল স্টোরেজ থেকে নাম তুলে আনার লজিক
    onMounted(() => {
        const userData = localStorage.getItem('user')
        if (userData) {
            try {
                const user = JSON.parse(userData)
                adminName.value = user.name // ডাটাবেজ থেকে আসা নাম সেট হবে
            } catch (e) {
                console.error("User data parse error", e)
            }
        }
    })

    const toggleDropdown = () => {
        dropdownOpen.value = !dropdownOpen.value
    }

    const logout = () => {
        if (confirm("Are you sure you want to logout?")) {
            localStorage.removeItem("token")
            localStorage.removeItem("role")
            localStorage.removeItem("user")

            router.push("/")
            dropdownOpen.value = false
        }
   }

    window.addEventListener('click', (e) => {
    const dropdown = document.querySelector('.user-dropdown')
        if (dropdown && !dropdown.contains(e.target)) {
            dropdownOpen.value = false
        }
    })
</script>

<style scoped>
    /* আপনার সিএসএস হুবহু রাখা হয়েছে, এক পিক্সেলও পরিবর্তন হয়নি */
    .navbar {
        height: 60px;
        background-color: #f1f5f9;
        border-bottom: 1px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 1rem;
        box-sizing: border-box;
        flex-wrap: wrap;
    }

    .page-title {
        font-size: 1.1rem;
        font-weight: bold;
        color: #1e293b;
        white-space: nowrap;
    }

    .user-dropdown {
        position: relative;
        cursor: pointer;
    }

    .user-info {
        font-size: 0.95rem;
        color: #334155;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        white-space: nowrap;
    }

    .arrow {
        font-size: 1.5rem;
        margin-left: 0.25rem;
    }

    .dropdown-menu {
        position: absolute;
        right: 0;
        top: 120%;
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        padding: 0.5rem 0;
        min-width: 160px;
        z-index: 10;
    }

    .dropdown-menu a, .dropdown-menu router-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        color: #334155;
        text-decoration: none;
        transition: background-color 0.2s;
        cursor: pointer;
    }

    .dropdown-menu a:hover {
        background-color: #f1f5f9;
    }

    .icon {
        font-size: 1rem;
    }

    @media (max-width: 600px) {
        .navbar {
            flex-direction: column;
            align-items: flex-start;
            height: auto;
            padding: 0.5rem 1rem;
        }

        .navbar-left, .navbar-right {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .page-title {
            font-size: 1rem;
        }

        .user-info {
            font-size: 0.9rem;
        }

        .dropdown-menu {
            right: 0;
            top: 100%;
        }
    }
</style>