<template>
  <div class="profile-page">
    <div v-if="loading" class="loader-container">
      <div class="spinner"></div>
    </div>

    <div v-else class="sleek-card">
      <div class="side-brand">
        <div class="avatar-wrapper">
          <img :src="form.profile_picture ? `${BASE_URL}/${form.profile_picture}` : '/default-avatar.png'" class="avatar-img" />
          <div class="badge-online"></div>
        </div>
        <h2 class="user-title">{{ form.full_name || 'User Name' }}</h2>
        <span class="user-role">{{ userRole }}</span>
        
        <router-link to="/profile-edit" class="btn-minimal">
          <i class="fas fa-edit"></i> Edit Profile
        </router-link>
      </div>

      <div class="main-content">
        <div v-if="userRole === 'vendor' && form.shop_name" class="top-store-section">
          <i class="fas fa-store"></i>
          <span class="store-name-text">{{ form.shop_name }}</span>
        </div>

        <div class="info-row-top">
          <div class="data-block">
            <label>Official Email</label>
            <div class="value-box">
              <i class="fas fa-envelope-open"></i>
              <span>{{ form.email }}</span>
            </div>
          </div>
          <div class="data-block">
            <label>Mobile Number</label>
            <div class="value-box">
              <i class="fas fa-phone-alt"></i>
              <span>{{ form.phone || 'Not Linked' }}</span>
            </div>
          </div>
        </div>

        <div class="address-board">
          <div class="board-header">
            <span class="dot"></span>
            <h4>Primary Location</h4>
          </div>
          
          <div class="address-flex-table">
            <div class="addr-col">
              <span class="addr-label">Thana/Upazila</span>
              <span class="addr-value">{{ form.thana_upazila || '—' }}</span>
            </div>
            <div class="addr-divider"></div>
            <div class="addr-col">
              <span class="addr-label">Village/Area</span>
              <span class="addr-value">{{ form.area_village || '—' }}</span>
            </div>
            <div class="addr-divider"></div>
            <div class="addr-col lg">
              <span class="addr-label">Street Details</span>
              <span class="addr-value line-clamp">{{ form.home_road_apartment || 'No details added.' }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import api, { BASE_URL } from '../../../axios';

const userRole = ref(localStorage.getItem('role'));
const token = localStorage.getItem('token') || localStorage.getItem('vendortoken');
const loading = ref(true);

const form = ref({
  full_name: '', email: '', phone: '', shop_name: '', thana_upazila: '',
  area_village: '', home_road_apartment: '', profile_picture: null
});

const loadProfile = async () => {
  try {
    const res = await api.get('/user/profile', {
      headers: { Authorization: `Bearer ${token}` }
    });
    form.value.email = res.data.email;
    form.value.full_name = res.data.name;
    if (res.data.profile) {
      form.value.full_name = res.data.profile.full_name || res.data.name;
      form.value.phone = res.data.profile.phone;
      form.value.shop_name = res.data.profile.shop_name;
      form.value.thana_upazila = res.data.profile.thana_upazila;
      form.value.area_village = res.data.profile.area_village;
      form.value.home_road_apartment = res.data.profile.home_road_apartment;
      form.value.profile_picture = res.data.profile.profile_picture;
    }
  } catch (err) { console.error(err); }
  finally { loading.value = false; }
};
onMounted(loadProfile);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

.profile-page {
  background: #f8fafc;
  min-height: 40vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  font-family: 'Outfit', sans-serif;
  box-sizing: border-box;
}

.sleek-card {
  display: flex;
  background: #ffffff;
  width: 100%;
  max-width: 1050px;
  height: 380px; 
  border-radius: 20px;
  box-shadow: 0 15px 45px rgba(0,0,0,0.05);
  overflow: hidden;
  border: 1px solid #edf2f7;
}

.side-brand {
  background: #0f172a;
  width: 28%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 30px;
}

.avatar-wrapper { position: relative; margin-bottom: 15px; }
.avatar-img {
  width: 130px; height: 130px; border-radius: 50%;
  object-fit: cover; border: 4px solid #1e293b;
}
.badge-online {
  position: absolute; bottom: 8px; right: 8px;
  width: 15px; height: 15px;
  background: #10b981; border: 3px solid #0f172a; border-radius: 50%;
}

.user-title { color: #fff; font-size: 22px; font-weight: 700; margin-bottom: 5px; text-align: center; }
.user-role { background: #3b82f6; color: #fff; font-size: 10px; padding: 2px 12px; border-radius: 50px; text-transform: uppercase; font-weight: 700; }

.main-content {
  width: 72%;
  padding: 30px 50px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  box-sizing: border-box;
}

.top-store-section {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 15px;
  color: #3b82f6;
}
.top-store-section i { font-size: 20px; }
.store-name-text { font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }

.info-row-top { 
  display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 25px;
}

.data-block label { 
  font-size: 11px; color: #94a3b8; font-weight: 700; 
  text-transform: uppercase; margin-bottom: 5px; display: block;
}

.value-box { display: flex; align-items: center; gap: 10px; color: #1e293b; font-size: 16px; font-weight: 600; }
.value-box i { color: #3b82f6; }

.address-board {
  background: #f8fafc;
  border: 1px solid #edf2f7;
  border-radius: 15px;
  padding: 20px 25px;
}

.board-header { display: flex; align-items: center; gap: 10px; margin-bottom: 12px; }
.board-header .dot { width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; }
.board-header h4 { font-size: 13px; color: #64748b; font-weight: 800; text-transform: uppercase; margin: 0; }

.address-flex-table { display: flex; align-items: center; justify-content: space-between; }
.addr-col { display: flex; flex-direction: column; gap: 4px; }
.addr-label { font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; }
.addr-value { font-size: 15px; color: #1e293b; font-weight: 600; }
.addr-divider { width: 1px; height: 30px; background: #e2e8f0; margin: 0 15px; }

.btn-minimal { margin-top: 20px; background: rgba(255,255,255,0.08); color: #fff; text-decoration: none; font-size: 12px; padding: 7px 20px; border-radius: 10px; transition: 0.3s; }
.btn-minimal:hover { background: #3b82f6; }

.loader-container { display: flex; justify-content: center; align-items: center; height: 100vh; width: 100%; }
.spinner { width: 30px; height: 30px; border: 3px solid #f3f3f3; border-top: 3px solid #3b82f6; border-radius: 50%; animation: spin 1s linear infinite; }
@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

/* --- RESPONSIVE FIXES --- */
@media (max-width: 900px) {
  .sleek-card { 
    flex-direction: column; 
    height: auto; 
    max-width: 100%;
  }
  .side-brand { 
    width: 100%; 
    padding: 40px 20px; 
    box-sizing: border-box;
  }
  .main-content { 
    width: 100%; 
    padding: 30px 25px; 
    box-sizing: border-box;
  }
  .top-store-section {
    justify-content: center;
    margin-bottom: 25px;
  }
  .info-row-top { 
    grid-template-columns: 1fr; 
    gap: 20px; 
    margin-bottom: 30px; 
  }
  .address-flex-table { 
    flex-direction: column; 
    align-items: flex-start; 
    gap: 15px; 
  }
  .addr-divider { display: none; }
  .addr-col.lg { 
    padding-left: 0; 
    width: 100%; 
  }
  .value-box { font-size: 15px; word-break: break-all; }
}

@media (max-width: 480px) {
  .profile-page { padding: 10px; }
  .main-content { padding: 25px 20px; }
  .address-board { padding: 20px; }
  .user-title { font-size: 19px; }
  .store-name-text { font-size: 20px; }
}
</style>