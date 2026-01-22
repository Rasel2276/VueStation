<template>
  <div class="slider-wrapper">
    <div class="slider">
      <div
        class="slides"
        :class="{ noTransition }"
        :style="{ transform: `translateX(-${currentIndex * 100}%)` }"
      >
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

      </div>
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
          image: "/assets/product-images/productslider.jpg",
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
.slider-wrapper {
  max-width: 1500px; 
  margin: 0 auto;
  padding: 0 5%; 
  background-color: #ffffff; 
}

/* ===== SLIDER ===== */
.slider {
  width: 100%;
  height: 50vh; /* Fixed height for slider */
  overflow: hidden;
  position: relative;
   
  margin-top: 1px;
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
  height: 100%; /* Slider-er height-er sathe exact match */
  background-size: 100% 100%; /* Image height/width 1 pixel-o barbe na, slider-e fit hobe */
  background-repeat: no-repeat;
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
  font-weight: 800;
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

/* ===== RESPONSIVE ===== */
@media (max-width: 1024px) {
  .slider-wrapper { padding: 0 5%; }
}

@media (max-width: 768px) {
  .slider-wrapper { padding: 0 15px; }
  .slider { height: 28vh; }
  .overlay h1 { font-size: 1.6rem; }
}

@media (max-width: 480px) {
  .slider { height: 24vh; }
  .overlay h1 { font-size: 1.3rem; }
}
</style>