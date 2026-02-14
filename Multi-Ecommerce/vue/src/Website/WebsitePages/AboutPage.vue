<template>
  <div class="about-page">

    <section class="hero">
      <div class="hero-content">
        <h1>About Us</h1>
        <p>Learn more about our mission, vision, and values</p>
      </div>
    </section>

    <section class="about-content">
      <div class="container-modern">
        <div class="about-visual-box">
          <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" alt="About Image" class="main-img">
          <div class="experience-badge">
            <span class="exp-num">10+</span>
            <span class="exp-text">Years of <br> Excellence</span>
          </div>
        </div>
        
        <div class="about-text-box">
          <div class="section-tag">Who We Are</div>
          <h2>Crafting Solutions with <span class="highlight">Passion & Precision</span></h2>
          <p class="lead-text">
            We are more than just a company; we are a team of dreamers and doers dedicated to redefining industry standards.
          </p>
          <div class="mission-vision-grid">
            <div class="mv-item">
              <div class="mv-icon">🎯</div>
              <div class="mv-content">
                <h4>Our Mission</h4>
                <p>To deliver exceptional experiences through technology and creativity.</p>
              </div>
            </div>
            <div class="mv-item">
              <div class="mv-icon">👁️</div>
              <div class="mv-content">
                <h4>Our Vision</h4>
                <p>To lead the global market while maintaining integrity and sustainability.</p>
              </div>
            </div>
          </div>
          <button class="about-cta">Learn More →</button>
        </div>
      </div>
    </section>

    <section class="features">
      <div class="section-tag">Highlights</div>
      <h2>Why Choose Us?</h2>
      <div class="feature-cards">
        <div class="feature-card">
          <div class="icon">💎</div>
          <h3>Quality Products</h3>
          <p>We ensure the highest quality standards in all our offerings.</p>
        </div>
        <div class="feature-card">
          <div class="icon">🤝</div>
          <h3>Customer Focused</h3>
          <p>Your satisfaction is our top priority, always.</p>
        </div>
        <div class="feature-card">
          <div class="icon">💡</div>
          <h3>Innovative Solutions</h3>
          <p>We embrace creativity and innovation in everything we do.</p>
        </div>
      </div>
    </section>

  </div>
</template>

<style scoped>
/* ===== GLOBAL ===== */
.about-page {
  font-family: "Plus Jakarta Sans", "Segoe UI", sans-serif;
  color: #1a202c;
  overflow-x: hidden;
}

/* ===== HERO SECTION ===== */
.hero {
  background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)),
              url('https://images.unsplash.com/photo-1506765515384-028b60a970df?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;
  height: 45vh;
  display: flex;
  justify-content: center;
  align-items: center;
  text-align: center;
  padding: 0 20px;
}

.hero-content h1 {
  font-size: 3.5rem;
  font-weight: 800;
  color: #fff;
  margin-bottom: 10px;
  animation: fadeInDown 0.8s ease;
}

.hero-content p {
  font-size: 1.1rem;
  color: #e2e8f0;
  max-width: 600px;
}

/* ===== ABOUT SECTION ===== */
.about-content {
  padding: 100px 10%;
  background: #ffffff;
}

.container-modern {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 80px;
  align-items: center;
}

.about-visual-box {
  position: relative;
}

.main-img {
  width: 100%;
  border-radius: 24px;
  box-shadow: 20px 20px 60px #d1d9e6, -20px -20px 60px #ffffff;
}

.experience-badge {
  position: absolute;
  bottom: -20px;
  right: -20px;
  background: #3b82f6;
  color: white;
  padding: 20px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 12px;
  box-shadow: 0 15px 30px rgba(59, 130, 246, 0.4);
  animation: float 3s ease-in-out infinite;
  z-index: 2;
}

.exp-num { font-size: 2rem; font-weight: 800; }
.exp-text { font-size: 0.85rem; line-height: 1.2; font-weight: 500; }

.section-tag {
  color: #3b82f6;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 0.85rem;
  margin-bottom: 15px;
  display: block;
}

.about-text-box h2 {
  font-size: 2.8rem;
  line-height: 1.2;
  margin-bottom: 25px;
  color: #1a202c;
  font-weight: 800;
}

.highlight { color: #3b82f6; }

.lead-text {
  font-size: 1.1rem;
  color: #4a5568;
  margin-bottom: 30px;
  line-height: 1.7;
}

.mission-vision-grid {
  display: grid;
  gap: 15px;
  margin-bottom: 35px;
}

.mv-item {
  display: flex;
  gap: 15px;
  background: #f8fafc;
  padding: 18px;
  border-radius: 16px;
  transition: 0.3s;
}

.mv-item:hover { background: #f1f5f9; }
.mv-icon { font-size: 1.4rem; }
.mv-content h4 { margin-bottom: 4px; color: #1a202c; font-weight: 700; }
.mv-content p { font-size: 0.9rem; color: #4a5568; margin: 0; line-height: 1.5; }

.about-cta {
  background: #1a202c;
  color: white;
  padding: 14px 32px;
  border-radius: 12px;
  border: none;
  font-weight: 600;
  cursor: pointer;
  transition: 0.3s;
}

.about-cta:hover {
  background: #3b82f6;
  transform: translateY(-2px);
}

/* ===== FEATURES SECTION ===== */
.features {
  padding: 80px 10%;
  text-align: center;
  background: #f8fafc;
}

.features h2 { font-size: 2.5rem; font-weight: 800; margin-bottom: 40px; }

.feature-cards {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 24px;
}

.feature-card {
  background: #fff;
  padding: 40px 25px;
  border-radius: 24px;
  width: 320px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.03);
  transition: 0.3s;
}

.feature-card:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(0,0,0,0.08); }

/* ===== ANIMATIONS ===== */
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes fadeInDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }

/* ===== MOBILE RESPONSIVE OPTIMIZATION ===== */
@media (max-width: 1024px) {
  .about-content { padding: 80px 5%; }
  .container-modern { gap: 40px; }
  .about-text-box h2 { font-size: 2.2rem; }
}

@media (max-width: 768px) {
  .hero { height: 35vh; }
  .hero-content h1 { font-size: 2.2rem; }
  .hero-content p { font-size: 1rem; }

  .about-content { padding: 60px 20px; }
  .container-modern { grid-template-columns: 1fr; gap: 60px; text-align: center; }

  .about-visual-box {
    max-width: 90%;
    margin: 0 auto;
  }

  .experience-badge {
    right: 50%;
    transform: translateX(50%);
    bottom: -25px;
    padding: 15px 20px;
    width: fit-content;
    white-space: nowrap;
  }

  .about-text-box h2 { font-size: 1.8rem; margin-top: 10px; }
  .lead-text { font-size: 1rem; }

  .mv-item {
    text-align: left; /* Item content left aligned for readability */
    padding: 15px;
    align-items: flex-start;
  }

  .features { padding: 60px 20px; }
  .features h2 { font-size: 1.8rem; }
  .feature-card { width: 100%; }
}

@media (max-width: 480px) {
  .hero-content h1 { font-size: 1.8rem; }
  .exp-num { font-size: 1.6rem; }
  .exp-text { font-size: 0.75rem; }
}
</style>