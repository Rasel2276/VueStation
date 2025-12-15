<template>
  <div class="contact-page">

    <!-- HERO SECTION -->
    <section class="hero">
      <div class="hero-content">
        <h1>Customer Help</h1>
        <p>
          If talking to a real-life human is more your thing, you can reach our
          Customer Happiness Team via email (below).
        </p>
      </div>
    </section>

    <!-- MAIN CONTENT -->
    <section class="contact-container">

      <!-- LEFT LINKS -->
      <aside class="help-links">
        <h4>How Can We Help?</h4>
        <ul>
          <li>FAQs</li>
          <li>Ordering</li>
          <li>Shipping</li>
          <li>Returns + Exchanges</li>
          <li>International</li>
          <li>Sustainability</li>
          <li class="active">Contact</li>
        </ul>
      </aside>

      <!-- CENTER FORM -->
      <div class="contact-form">
        <h2>Contact Us</h2>

        <input type="text" placeholder="Subject" />
        <input type="text" placeholder="Name" />
        <input type="email" placeholder="Email" />
        <textarea placeholder="Enter your message here..."></textarea>

        <div class="captcha">
          <input type="checkbox" />
          <span>I'm not a robot</span>
        </div>

        <button>SUBMIT</button>
      </div>

      <!-- RIGHT INFO -->
      <aside class="support-info">
        <h4>Support Hours:</h4>
        <p>Mon–Fri 9:00am – 5:00pm PST</p>
        <p class="muted">*Excludes Holidays</p>

        <p class="spaced">
          Looking for more info on products, shipping, fabric, and more?
        </p>

        <a href="#">VIEW FAQ</a>
      </aside>

    </section>

    <!-- BOTTOM CTA -->
    <section class="bottom-cta">
      <div class="cta-content">
        <span class="icon">✉</span>
        <h2>Get in Touch</h2>
        <p>Have questions about your order, or a general inquiry?</p>
        <button>Email Us</button>
      </div>
    </section>

  </div>
</template>

<script>
export default {
  name: "ContactPage",
};
</script>

<style scoped>
/* =====================
   GLOBAL
===================== */
.contact-page {
  font-family: "Georgia", serif;
  color: #222;
}

/* =====================
   HERO
===================== */
.hero {
  height: 320px;
  background-image: url("https://images.unsplash.com/photo-1520975916090-3105956dac38");
  background-size: cover;
  background-position: center;
  display: flex;
  align-items: center;
  padding: 0 80px;
}

.hero-content {
  max-width: 600px;
  color: white;
}

.hero h1 {
  font-size: 48px;
  margin-bottom: 10px;
}

.hero p {
  font-size: 14px;
  line-height: 1.6;
}

/* =====================
   CONTACT LAYOUT
===================== */
.contact-container {
  display: grid;
  grid-template-columns: 1fr 2fr 1fr;
  gap: 60px;
  padding: 120px;
  background: #fff;
}

/* LEFT */
.help-links h4 {
  margin-bottom: 20px;
}

.help-links ul {
  list-style: none;
  padding: 0;
}

.help-links li {
  margin-bottom: 12px;
  font-size: 14px;
  color: #666;
  cursor: pointer;
}

.help-links li.active {
  font-weight: bold;
  color: #000;
}

/* CENTER FORM */
.contact-form {
  max-width: 480px;
}

.contact-form h2 {
  font-size: 36px;
  margin-bottom: 30px;
}

.contact-form input,
.contact-form textarea {
  width: 100%;
  border: none;
  border-bottom: 1px solid #ccc;
  padding: 10px 0;
  margin-bottom: 25px;
  font-size: 14px;
  outline: none;
}

.contact-form textarea {
  resize: none;
  height: 100px;
}

.captcha {
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid #ddd;
  padding: 12px;
  width: fit-content;
  margin-bottom: 30px;
  font-size: 13px;
}

.contact-form button {
  background: #bdbdbd;
  border: none;
  padding: 12px 30px;
  font-size: 14px;
  cursor: pointer;
}

/* RIGHT */
.support-info {
  font-size: 14px;
}

.support-info h4 {
  margin-bottom: 10px;
}

.support-info .muted {
  color: #888;
  font-size: 13px;
}

.support-info .spaced {
  margin: 25px 0;
}

.support-info a {
  text-decoration: underline;
  color: #000;
  font-weight: 500;
}

/* =====================
   BOTTOM CTA
===================== */
.bottom-cta {
  background: #f4f4f4;
  padding: 80px 20px;
  text-align: center;
}

.cta-content {
  max-width: 500px;
  margin: auto;
}

.cta-content .icon {
  font-size: 28px;
  display: block;
  margin-bottom: 15px;
}

.cta-content h2 {
  font-size: 32px;
  margin-bottom: 10px;
}

.cta-content p {
  font-size: 14px;
  margin-bottom: 25px;
}

.cta-content button {
  background: #222;
  color: white;
  border: none;
  padding: 12px 30px;
  cursor: pointer;
}

/* =====================
   RESPONSIVE
===================== */
@media (max-width: 1024px) {
  .contact-container {
    grid-template-columns: 1fr;
    gap: 50px;
  }

  .hero {
    padding: 0 40px;
  }
}

@media (max-width: 600px) {
  .hero {
    height: 240px;
    padding: 0 20px;
  }

  .hero h1 {
    font-size: 32px;
  }

  .contact-container {
    padding: 50px 20px;
  }

  .contact-form h2 {
    font-size: 28px;
  }
}
</style>
