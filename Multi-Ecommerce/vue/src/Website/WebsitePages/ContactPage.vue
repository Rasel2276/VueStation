<template>
  <div class="contact-page">

    <!-- HERO SECTION -->
    <section class="hero">
      <div class="hero-content">
        <h1>Contact Us</h1>
        <p>We are here to help and answer any question you might have.</p>
      </div>
    </section>

    <!-- CONTACT SECTION -->
    <section class="contact-section">
      <div class="contact-container">

        <!-- CONTACT INFO -->
        <div class="contact-info">
          <h2>Get in Touch</h2>
          <p>Feel free to reach out to us via the form or using the contact info below.</p>
          <div class="info-box">
            <h3>Phone</h3>
            <p>+880 1910192276</p>
          </div>
          <div class="info-box">
            <h3>Email</h3>
            <p>mdraselhossain2276@gmail.com</p>
          </div>
          <div class="info-box">
            <h3>Address</h3>
            <p>42/2c RND Road, Dhaka, Bangladesh</p>
          </div>
        </div>

        <!-- CONTACT FORM -->
        <div class="contact-form">
          <h2>Send a Message</h2>
          <form @submit.prevent="submitForm">
            <input type="text" placeholder="Your Name" v-model="form.name" required>
            <input type="email" placeholder="Your Email" v-model="form.email" required>
            <input type="text" placeholder="Subject" v-model="form.subject" required>
            <textarea rows="5" placeholder="Your Message" v-model="form.message" required></textarea>
            <button type="submit">Send Message</button>
          </form>
        </div>

      </div>
    </section>

  </div>
</template>

<script>
export default {
  name: "ContactPage",
  data() {
    return {
      form: {
        name: "",
        email: "",
        subject: "",
        message: ""
      }
    };
  },
  methods: {
    submitForm() {
      alert(`Thank you, ${this.form.name}! Your message has been sent.`);
      // Reset form
      this.form.name = "";
      this.form.email = "";
      this.form.subject = "";
      this.form.message = "";
    }
  }
};
</script>

<style scoped>
/* ===== GLOBAL ===== */
.contact-page {
  font-family: "Segoe UI", Arial, sans-serif;
  color: #222;
}

/* ===== HERO SECTION ===== */
.hero {
  background: url('https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;
  height: 40vh;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
  text-align: center;
}

.hero::before {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.45);
}

.hero-content {
  position: relative;
  color: #fff;
  animation: fadeInDown 1s ease forwards; /* About page-এর মতো animation */
}

.hero-content h1 {
  font-size: 2.8rem;
  margin-bottom: 0.5rem;
}

.hero-content p {
  font-size: 1.2rem;
}

/* ===== CONTACT SECTION ===== */
.contact-section {
  padding: 60px 10%;
  background: #f9fafb;
}

.contact-container {
  display: flex;
  gap: 50px;
  flex-wrap: wrap;
}

/* CONTACT INFO */
.contact-info {
  flex: 1 1 350px;
}

.contact-info h2 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #3b82f6;
}

.contact-info p {
  font-size: 1rem;
  margin-bottom: 25px;
  color: #4b5563;
}

.info-box {
  margin-bottom: 20px;
}

.info-box h3 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 5px;
  color: #222;
}

.info-box p {
  font-size: 0.95rem;
  color: #4b5563;
}

/* CONTACT FORM */
.contact-form {
  flex: 1 1 450px;
}

.contact-form h2 {
  font-size: 2rem;
  margin-bottom: 20px;
  color: #3b82f6;
}

.contact-form form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

/* All input fields same width */
input, textarea {
  width: 100%;
  padding: 12px 15px;
  border-radius: 6px;
  border: 1px solid #d1d5db;
  font-size: 1rem;
  outline: none;
  transition: border 0.3s, box-shadow 0.3s;
}

input:focus, textarea:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 8px rgba(59,130,246,0.3);
}

button {
  padding: 12px 20px;
  background: #3b82f6;
  color: #fff;
  font-weight: 600;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  transition: 0.3s;
}

button:hover {
  background: #2563eb;
}

/* ===== ANIMATION ===== */
@keyframes fadeInDown {
  0% { opacity: 0; transform: translateY(-20px); }
  100% { opacity: 1; transform: translateY(0); }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .contact-container {
    gap: 30px;
  }
}

@media (max-width: 768px) {
  .hero-content h1 { font-size: 2rem; }
  .hero-content p { font-size: 1rem; }
  .contact-container {
    flex-direction: column;
    gap: 30px;
  }
}

@media (max-width: 480px) {
  .hero { height: 30vh; }
  .contact-form h2, .contact-info h2 { font-size: 1.6rem; }
}
</style>
