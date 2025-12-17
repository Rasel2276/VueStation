<template>
  <div class="about-page">

    <!-- HERO SECTION -->
    <section class="hero">
      <div class="hero-content">
        <h1>About Us</h1>
        <p>Learn more about our mission, vision, and values</p>
      </div>
    </section>

    <!-- ABOUT CONTENT SECTION -->
    <section class="about-content">
      <div class="about-image">
        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f" alt="About Image">
      </div>
      <div class="about-text">
        <h2>Who We Are</h2>
        <p>
          We are committed to providing the best quality products and services to our customers. 
          Our team is passionate, skilled, and dedicated to innovation and excellence. 
          We believe in building a community where our customers feel valued and inspired.
        </p>
        <p>
          Our mission is to combine creativity, technology, and customer service to deliver an 
          exceptional experience. We aim to lead the industry while maintaining integrity and sustainability.
        </p>
      </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features">
      <h2>Our Highlights</h2>
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

<script>
export default {
  name: "AboutPage",
};
</script>

<style scoped>
/* ===== GLOBAL ===== */
.about-page {
  font-family: "Segoe UI", Arial, sans-serif;
  color: #222;
}

/* ===== HERO SECTION ===== */
.hero {
  background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
              url('https://images.unsplash.com/photo-1506765515384-028b60a970df?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;
  height: 55vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  text-align: center;
}

.hero-content {
  position: relative;
  color: #fff;
  animation: fadeInDown 1s ease forwards;
}

.hero-content h1 {
  font-size: 3rem;
  margin-bottom: 0.5rem;
}

.hero-content p {
  font-size: 1.2rem;
}

/* ===== ABOUT CONTENT ===== */
.about-content {
  display: flex;
  align-items: center;
  gap: 50px;
  padding: 60px 10%;
  flex-wrap: wrap;
}

.about-image {
  flex: 1 1 400px;
  overflow: hidden;
  border-radius: 12px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.about-image img {
  width: 100%;
  transition: transform 0.5s ease;
}

.about-image:hover img {
  transform: scale(1.05);
}

.about-text {
  flex: 1 1 400px;
}

.about-text h2 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #3b82f6;
}

.about-text p {
  font-size: 1rem;
  margin-bottom: 16px;
  line-height: 1.6;
  color: #4b5563;
}

/* ===== FEATURES SECTION ===== */
.features {
  padding: 60px 10%;
  text-align: center;
  background: linear-gradient(135deg, #f3f4f6, #e0f2fe);
}

.features h2 {
  font-size: 2rem;
  margin-bottom: 40px;
  color: #222;
  position: relative;
}

.feature-cards {
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 30px;
}

.feature-card {
  background: #fff;
  padding: 30px 20px;
  border-radius: 12px;
  width: 280px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.08);
  transition: transform 0.3s, box-shadow 0.3s;
  cursor: pointer;
  text-align: center;
}

.feature-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 24px rgba(0,0,0,0.15);
}

.feature-card .icon {
  font-size: 2.5rem;
  margin-bottom: 12px;
}

.feature-card h3 {
  font-size: 1.3rem;
  margin-bottom: 12px;
  color: #3b82f6;
}

.feature-card p {
  font-size: 0.95rem;
  color: #4b5563;
  line-height: 1.5;
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInDown {
  0% { opacity: 0; transform: translateY(-20px); }
  100% { opacity: 1; transform: translateY(0); }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .about-content { padding: 50px 5%; gap: 40px; }
  .features { padding: 50px 5%; }
}

@media (max-width: 768px) {
  .hero-content h1 { font-size: 2.2rem; }
  .hero-content p { font-size: 1rem; }
  .about-content { flex-direction: column; gap: 30px; }
  .feature-cards { gap: 20px; justify-content: center; }
}

@media (max-width: 480px) {
  .hero { height: 35vh; }
  .about-text h2 { font-size: 1.6rem; }
  .about-text p { font-size: 0.95rem; }
  .feature-card { width: 100%; max-width: 280px; }
}
</style>
