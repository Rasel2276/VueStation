<template>
  <div class="slider">
    <!-- SLIDES -->
    <div
      class="slides"
      :class="{ noTransition }"
      :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
    >
      <!-- REAL SLIDES -->
      <div
        class="slide"
        v-for="(slide, index) in slides"
        :key="index"
        :style="{ backgroundImage: `url(${slide.image})` }"
      >
        <div class="overlay">
          <h1>{{ slide.title }}</h1>
          <p>{{ slide.text }}</p>
          <button>Shop Now</button>
        </div>
      </div>

      <!-- CLONE FIRST SLIDE -->
      <div
        class="slide"
        :style="{ backgroundImage: `url(${slides[0].image})` }"
      >
        <div class="overlay">
          <h1>{{ slides[0].title }}</h1>
          <p>{{ slides[0].text }}</p>
          <button>Shop Now</button>
        </div>
      </div>
    </div>

    <!-- LEFT ARROW (BACK) -->
    <button class="nav left" @click="prevSlide">❮</button>

    <!-- RIGHT ARROW (NEXT) -->
    <button class="nav right" @click="nextSlide">❯</button>
  </div>
</template>

<script>
export default {
  name: "HomeSlider",

  data() {
    return {
      currentIndex: 0,
      interval: null,
      noTransition: false,
      slides: [
        {
          image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e",
          title: "Best Electronics",
          text: "Discover the latest gadgets & devices"
        },
        {
          image: "https://images.unsplash.com/photo-1521335629791-ce4aec67dd47",
          title: "Fashion Collection",
          text: "Trending styles for everyone"
        },
        {
          image: "https://images.unsplash.com/photo-1512820790803-83ca734da794",
          title: "Books & Knowledge",
          text: "Read, learn & grow smarter"
        }
      ]
    }
  },

  mounted() {
    this.startAutoSlide()
  },

  beforeUnmount() {
    clearInterval(this.interval)
  },

  methods: {
    startAutoSlide() {
      this.interval = setInterval(() => {
        this.nextSlide(true)
      }, 3500)
    },

    nextSlide(isAuto = false) {
      if (!isAuto) {
        clearInterval(this.interval)
      }

      this.currentIndex++

      // when clone slide reached
      if (this.currentIndex === this.slides.length) {
        setTimeout(() => {
          this.noTransition = true
          this.currentIndex = 0

          requestAnimationFrame(() => {
            this.noTransition = false
          })
        }, 1000)
      }

      if (!isAuto) {
        this.startAutoSlide()
      }
    },

    prevSlide() {
      clearInterval(this.interval)

      if (this.currentIndex === 0) {
        this.noTransition = true
        this.currentIndex = this.slides.length - 1

        requestAnimationFrame(() => {
          this.noTransition = false
        })
      } else {
        this.currentIndex--
      }

      this.startAutoSlide()
    }
  }
}
</script>

<style scoped>
/* ===== SLIDER ===== */
.slider {
  width: 100%;
  height: 45vh;
  overflow: hidden;
  position: relative;
}

/* ===== SLIDES ===== */
.slides {
  display: flex;
  height: 100%;
  transition: transform 1s ease-in-out;
}

.slides.noTransition {
  transition: none;
}

/* ===== SLIDE ===== */
.slide {
  min-width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  position: relative;
}

/* ===== OVERLAY ===== */
.overlay {
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.45);
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding-left: 8%;
  color: #fff;
}

.overlay h1 {
  font-size: 2.2rem;
  margin-bottom: 10px;
}

.overlay p {
  font-size: 1rem;
  margin-bottom: 16px;
  max-width: 420px;
}

.overlay button {
  width: 150px;
  padding: 10px;
  background: #3b82f6;
  border: none;
  color: white;
  border-radius: 6px;
  cursor: pointer;
}

/* ===== ARROWS ===== */
.nav {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0,0,0,0.5);
  color: white;
  border: none;
  font-size: 26px;
  padding: 10px 14px;
  cursor: pointer;
  border-radius: 50%;
  z-index: 10;
  transition: 0.3s;
}

.nav:hover {
  background: rgba(0,0,0,0.8);
}

.left {
  left: 20px;
}

.right {
  right: 20px;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
  .slider { height: 28vh; }
  .overlay h1 { font-size: 1.6rem; }
}

@media (max-width: 480px) {
  .slider { height: 24vh; }
  .overlay h1 { font-size: 1.3rem; }
}
</style>
