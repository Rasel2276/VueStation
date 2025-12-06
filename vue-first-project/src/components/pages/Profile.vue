<template>
  <div class="profile-wrapper">
    <div class="profile-card-box">
      <div class="profile-avatar"></div>

      <h2 class="profile-title">David Smith</h2>
      <p class="profile-role">UI/UX Designer</p>

      <div class="profile-info">
        <div class="profile-info-row">
          <span class="info-label">Email:</span>
          <span class="info-value">david@example.com</span>
        </div>

        <div class="profile-info-row">
          <span class="info-label">Phone:</span>
          <span class="info-value">01234-567890</span>
        </div>

        <div class="profile-info-row">
          <span class="info-label">Location:</span>
          <span class="info-value">Dhaka, Bangladesh</span>
        </div>
      </div>

      <button class="profile-edit-btn">Edit Profile</button>
    </div>
  </div>
</template>

<script>
export default {
  name: "Profile",
};
</script>

<style>
.profile-wrapper {
  display: flex;
  justify-content: center;
  margin-top: 30px;
}

.profile-card-box {
  width: 350px;
  background: white;
  padding: 25px;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
  text-align: center;
  animation: fadeIn 0.5s ease;
}

.profile-avatar {
  width: 100px;
  height: 100px;
  background: #cccccc;
  border-radius: 50%;
  margin: 0 auto 15px;
  border: 4px solid #0aaed8;
}

.profile-title {
  font-size: 22px;
  margin-bottom: 5px;
  color: #0f6bbf;
}

.profile-role {
  color: #666;
  margin-bottom: 18px;
}

.profile-info {
  text-align: left;
  margin-bottom: 20px;
}

.profile-info-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 10px;
}

.info-label {
  font-weight: bold;
  color: #444;
}

.info-value {
  color: #555;
}

.profile-edit-btn {
  width: 100%;
  padding: 10px;
  background: linear-gradient(to right, #0f6bbf, #0aaed8);
  border: none;
  color: white;
  border-radius: 8px;
  cursor: pointer;
  font-size: 15px;
  transition: 0.3s;
}

.profile-edit-btn:hover {
  opacity: 0.9;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
