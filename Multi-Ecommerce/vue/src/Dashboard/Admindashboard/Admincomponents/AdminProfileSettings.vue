<template>
  <div class="profile-settings">
    <div class="settings-card">
      <form @submit.prevent="updateProfile" class="modern-form">
        <div class="avatar-center-box">
          <div class="avatar-edit-wrapper">
            <img :src="previewImage || (form.profile_picture ? `${BASE_URL}/${form.profile_picture}` : '/default-avatar.png')" class="main-avatar" />
            <label class="camera-icon">
              <i class="fas fa-camera"></i>
              <input type="file" @change="onFileChange" hidden />
            </label>
          </div>
          <h3 class="profile-title">{{ form.full_name || 'My Profile' }}</h3>
          <span class="avatar-hint">Click camera to change photo</span>
        </div>

        <div class="form-grid-layout">
          <div class="input-field">
            <label><i class="fas fa-user-circle"></i> Full Name</label>
            <input v-model="form.full_name" type="text" placeholder="Enter your name" required />
          </div>

          <div class="input-field">
            <label><i class="fas fa-phone-alt"></i> Phone Number</label>
            <input v-model="form.phone" type="text" placeholder="017xxxxxxxx" required />
          </div>

          <div v-if="userRole === 'vendor'" class="input-field">
            <label><i class="fas fa-store"></i> Shop Name</label>
            <input v-model="form.shop_name" type="text" placeholder="Enter shop name" />
          </div>
          
          <div class="input-field">
            <label><i class="fas fa-city"></i> Thana / Upazila</label>
            <input v-model="form.thana_upazila" type="text" placeholder="Your Thana" />
          </div>

          <div class="input-field">
            <label><i class="fas fa-map-marker-alt"></i> Area / Village</label>
            <input v-model="form.area_village" type="text" placeholder="Your Area" />
          </div>

          <div class="input-field">
             <label><i class="fas fa-home"></i> Road/Apartment</label>
             <input v-model="form.home_road_apartment" type="text" placeholder="House/Road No" />
          </div>

          <div class="input-field">
             <label><i class="fas fa-shield-alt"></i> New Password</label>
             <input v-model="form.password" type="password" placeholder="Leave blank to keep same" />
          </div>
        </div>

        <div class="form-actions-centered">
          <button type="submit" class="prime-btn-gradient" :disabled="loading">
            <div class="btn-content" v-if="!loading">
              <i class="fas fa-paper-plane"></i>
              <span>Save Profile Changes</span>
            </div>
            <span v-else class="loader-dot"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import api, { BASE_URL } from '../../../axios';

const userRole = ref(localStorage.getItem('role'));
const token = localStorage.getItem('token') || localStorage.getItem('vendortoken');
const loading = ref(false);
const previewImage = ref(null);
const selectedFile = ref(null);

const form = ref({
  full_name: '', phone: '', shop_name: '', thana_upazila: '',
  area_village: '', home_road_apartment: '', profile_picture: null, password: ''
});

const loadProfile = async () => {
  try {
    const res = await api.get('/user/profile', {
      headers: { Authorization: `Bearer ${token}` }
    });
    form.value.full_name = res.data.profile?.full_name || res.data.name;
    if (res.data.profile) {
      form.value.phone = res.data.profile.phone;
      form.value.shop_name = res.data.profile.shop_name;
      form.value.thana_upazila = res.data.profile.thana_upazila;
      form.value.area_village = res.data.profile.area_village;
      form.value.home_road_apartment = res.data.profile.home_road_apartment;
      form.value.profile_picture = res.data.profile.profile_picture;
    }
  } catch (err) { console.error(err); }
};

const onFileChange = (e) => {
  selectedFile.value = e.target.files[0];
  if (selectedFile.value) {
    previewImage.value = URL.createObjectURL(selectedFile.value);
  }
};

const updateProfile = async () => {
  loading.value = true;
  const formData = new FormData();
  formData.append('full_name', form.value.full_name || '');
  formData.append('phone', form.value.phone || '');
  formData.append('thana_upazila', form.value.thana_upazila || '');
  formData.append('area_village', form.value.area_village || '');
  formData.append('home_road_apartment', form.value.home_road_apartment || '');
  formData.append('password', form.value.password || '');
  if (userRole.value === 'vendor') formData.append('shop_name', form.value.shop_name || '');
  if (selectedFile.value) formData.append('profile_picture', selectedFile.value);

  try {
    const res = await api.post('/user/profile-update', formData, {
      headers: { Authorization: `Bearer ${token}`, 'Content-Type': 'multipart/form-data' }
    });
    alert(res.data.message);
    form.value.password = '';
    loadProfile();
  } catch (err) {
    alert("Error: " + (err.response?.data?.message || "Update failed!"));
  } finally { loading.value = false; }
};

onMounted(loadProfile);
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

.profile-settings {
  background: #f8fafc;
  min-height: 100vh;
  padding: 30px 20px;
  font-family: 'Outfit', sans-serif;
  display: flex;
  justify-content: center;
  align-items: flex-start;
  box-sizing: border-box;
}

.settings-card {
  max-width: 1000px;
  width: 100%;
  background: #ffffff;
  border-radius: 30px;
  box-shadow: 0 10px 50px rgba(0,0,0,0.04);
  border: 1px solid #f1f5f9;
  overflow: hidden;
  box-sizing: border-box;
}

.modern-form { 
  padding: 40px 50px; 
  box-sizing: border-box;
  width: 100%;
}

.avatar-center-box {
  display: flex; flex-direction: column; align-items: center;  
  margin-bottom: 30px;
}
.avatar-edit-wrapper {
  position: relative; width: 120px; height: 120px;
  border-radius: 50%; padding: 6px;  
  background: linear-gradient(135deg, #6366f1, #a855f7);
  box-shadow: 0 15px 30px rgba(99, 102, 241, 0.2);
}
.main-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid #fff; }
.camera-icon {
  position: absolute; bottom: 5px; right: 5px; background: #1e293b; color: #fff; width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 3px solid #fff; transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.camera-icon:hover { background: #6366f1; transform: scale(1.1) rotate(15deg); }
.profile-title { margin-top: 15px; font-size: 20px; font-weight: 700; color: #1e293b; text-align: center; }
.avatar-hint { margin-top: 4px; font-size: 12px; color: #94a3b8; font-weight: 400; text-align: center; }

.form-grid-layout {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  width: 100%;
}

.input-field { display: flex; flex-direction: column; gap: 6px; }
.input-field label {
  display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; color: #64748b; letter-spacing: 0.3px;
}
.input-field label i { color: #6366f1; font-size: 14px; }

.input-field input {
  padding: 12px 20px;
  background: #f8fafc; border: 2px solid #f1f5f9; border-radius: 16px; font-size: 15px; color: #1e293b; font-weight: 500; transition: 0.3s;
  box-sizing: border-box;
  width: 100%;
}
.input-field input:focus {
  background: #fff; border-color: #6366f1; box-shadow: 0 8px 20px rgba(99, 102, 241, 0.06); outline: none;
}

.form-actions-centered {
  margin-top: 40px;
  display: flex;
  justify-content: center;
  width: 100%;
}
.prime-btn-gradient {
  background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
  color: #fff;
  border: none;
  padding: 15px 45px;
  border-radius: 20px;
  font-size: 16px;
  font-weight: 700;
  cursor: pointer;
  transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  box-shadow: 0 10px 25px rgba(30, 41, 59, 0.2);
  min-width: 280px;
  box-sizing: border-box;
}
.prime-btn-gradient:hover {
  background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(99, 102, 241, 0.3);
}

.btn-content { display: flex; align-items: center; justify-content: center; gap: 12px; }

.loader-dot {
  width: 20px; height: 20px; border: 3px solid rgba(255,255,255,0.3); border-top-color: #fff; border-radius: 50%; animation: spin 0.8s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* --- RESPONSIVE ADJUSTMENTS --- */
@media (max-width: 992px) {
  .form-grid-layout { grid-template-columns: repeat(2, 1fr); }
  .modern-form { padding: 40px 30px; }
}

@media (max-width: 650px) {
  .profile-settings { padding: 20px 15px; } 
  .settings-card { border-radius: 24px; }
  .modern-form { padding: 30px 20px; }
  .form-grid-layout { grid-template-columns: 1fr; gap: 18px; }
  .prime-btn-gradient { width: 100%; min-width: unset; }
  .profile-title { font-size: 19px; }
}

@media (max-width: 375px) {
  .profile-settings { padding: 15px 10px; }
  .modern-form { padding: 25px 15px; }
  .input-field input { padding: 12px 15px; font-size: 14px; }
}
</style>