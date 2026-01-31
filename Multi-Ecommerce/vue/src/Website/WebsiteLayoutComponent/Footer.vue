<template>
  <footer class="footer">
    <div class="footer-container">

      <!-- CONTACT -->
      <div class="footer-column">
        <h3>CONTACT</h3>
        <p>Street: 42/2c RND Road Lalbagh</p>
        <p>City: Dhaka</p>
        <p>State Full: Bangladesh</p>
        <p>Zip Code: 1211</p>
        <p>Phone Number: 01910192276</p>
        <p>Mobile Number: 01910192276</p>
      </div>

      <!-- MENU -->
      <div class="footer-column">
        <h3>MENU</h3>
        <a href="#">Home</a>
        <a href="#">Books</a>
        <a href="#">About</a>
        <a href="#">Courses</a>
        <a href="#">Our blog</a>
        <a href="#">Pricing</a>
      </div>

      <!-- RECENT POSTS -->
      <div class="footer-column">
        <h3>RECENT POSTS</h3>
        <a href="#">Breaking Down Barriers</a>
        <a href="#">A Celebration of Success</a>
        <a href="#">A World of Opportunities</a>
      </div>

      <!-- NEWSLETTER -->
      <div class="footer-column">
        <h3>NEWSLETTER</h3>

        <div class="newsletter-box">
          <input type="text" placeholder="Your email address" />
          <button>SIGN UP</button>
        </div>

        <div class="social-icons">
          <i class="fa-brands fa-facebook-f"></i>
          <i class="fa-brands fa-twitter"></i>
          <i class="fa-brands fa-instagram"></i>
          <i class="fa-brands fa-linkedin"></i>
          <i class="fa-brands fa-youtube"></i>
        </div>
      </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="copyright">
      <p>© 2026 RYZE. Built for Excellence and Premium Shopping Experience.</p>
    </div>
  </footer>
</template>

<script>
export default {
  name: "Footer",
};
</script>

<style scoped>
.footer {
  background: #050e3c;
  color: white;
  padding: 50px 20px 20px 20px;
  font-family: Arial, sans-serif;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  gap: 40px;
  flex-wrap: wrap;
  max-width: 1200px;
  margin: auto;
}

/* Column */
.footer-column {
  display: flex;
  flex-direction: column;
  min-width: 200px;
}

.footer-column h3 {
  font-size: 15px;
  font-weight: bold;
  margin-bottom: 15px;
}

.footer-column p,
.footer-column a {
  color: #ccc;
  font-size: 14px;
  margin: 5px 0;
  text-decoration: none;
}

.footer-column a:hover {
  color: white;
}

/* Newsletter */
.newsletter-box {
  display: flex;
  margin-bottom: 15px;
  background: white;
  border-radius:5px
}

.newsletter-box input {
  padding: 8px;
  flex: 1;
  border: none;
  border-radius: 3px 0 0 3px;
}

.newsletter-box button {
  padding: 8px 15px;
  background: #e4002b;
  border: none;
  color: white;
  font-size: 14px;
  border-radius: 0 3px 3px 0;
  cursor: pointer;
}

.newsletter-box button:hover {
  background: #ff1a3c;
}

/* Social Icons */
.social-icons {
  display: flex;
  gap: 10px;
  margin-top: 10px;
}

.social-icons i {
  font-size: 18px;
  cursor: pointer;
  color: #ccc;
  transition: 0.3s;
}

.social-icons i:hover {
  color: white;
}

/* COPYRIGHT */
.copyright {
  text-align: center;
  color: #bbb;
  font-size: 13px;
  padding: 20px 0 0 0;
  border-top: 1px solid #333;
  margin-top: 30px;
}

/* RESPONSIVE  *****/
@media (max-width: 900px) {
  .footer-container {
    flex-direction: column;
  }
}

@media (max-width: 600px) {
  .newsletter-box {
    flex-direction: column;
  }

  .newsletter-box input {
    border-radius: 3px;
    margin-bottom: 10px;
  }

  .newsletter-box button {
    border-radius: 3px;
  }
}
</style>
