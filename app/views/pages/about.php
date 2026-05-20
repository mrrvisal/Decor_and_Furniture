<?php require __DIR__ . '/../helpers.php'; ?>

<section class="about-hero">
    <div class="container">
        <div class="hero-content">
            <h1>About Luxery</h1>
            <p class="hero-subtitle">
                Your trusted destination for beautiful home decor and quality furniture since 2020.
                We believe every home tells a story, and we're here to help you tell yours.
            </p>
        </div>
    </div>
</section>

<!-- ===== ABOUT SECTION ===== -->
<section class="brand-story-section">
    <div class="layout-wrapper">
        <div class="story-layout">

            <div class="story-content">
                <h2 class="section-title">Our Story</h2>
                <p class="story-text">
                    Founded with a passion for transforming houses into homes, Decor & Furniture has been
                    serving customers with a curated collection of stylish and functional pieces.
                </p>
                <p class="story-text">
                    Every piece in our collection is carefully selected to meet the highest standards of
                    quality, design, and sustainability.
                </p>
                <a href="#" class="primary-btn">Discover More</a>
            </div>

            <div class="story-media">
                <img src="	https://cdn.pixabay.com/photo/2016/11/29/03/53/house-1867187_1280.jpg"
                    alt="Elegant living room" class="story-photo">
            </div>

        </div>
    </div>
</section>

<!-- ===== VALUES SECTION ===== -->
<section class="core-values-section">
    <div class="layout-wrapper">
        <h2 class="section-title center">Our Core Values</h2>

        <div class="values-layout">

            <div class="value-card">
                <div class="value-emoji">🌱</div>
                <h3 class="value-title">Sustainability</h3>
                <p class="value-description">
                    We are committed to eco-friendly practices and responsible sourcing.
                </p>
            </div>

            <div class="value-card">
                <div class="value-emoji">💝</div>
                <h3 class="value-title">Customer First</h3>
                <p class="value-description">
                    Your satisfaction is our priority through exceptional service.
                </p>
            </div>

            <div class="value-card">
                <div class="value-emoji">🏠</div>
                <h3 class="value-title">Premium Quality</h3>
                <p class="value-description">
                    Every product meets high standards of durability and craftsmanship.
                </p>
            </div>

        </div>
    </div>
</section>


<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Happy Customers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">100+</div>
                <div class="stat-label">Products Sold</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">4.9</div>
                <div class="stat-label">Average Rating</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">20</div>
                <div class="stat-label">Founded</div>
            </div>
        </div>
    </div>
</section>
<style>
/* ===== GLOBAL WRAPPER ===== */
.layout-wrapper {
    width: 90%;
    max-width: 1200px;
    margin: auto;
}

/* ===== SECTION SPACING ===== */
.brand-story-section {
    padding: 50px 0 0px;
}

.core-values-section {
    padding: 40px 0 40px;
}

/* ===== TITLES ===== */
.section-title {
    font-size: 38px;
    font-weight: 700;
    margin-bottom: 25px;
    color: #2c2c2c;
}

.section-title.center {
    text-align: center;
}

/* ===== STORY LAYOUT ===== */
.story-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: center;
}

.story-text {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
    margin-bottom: 20px;
}

.story-photo {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    transition: 0.4s ease;
}

.story-photo:hover {
    transform: scale(1.05);
}

/* ===== BUTTON ===== */
.primary-btn {
    display: inline-block;
    padding: 12px 28px;
    background: rgb(254, 107, 53);
    color: white;
    text-decoration: none;
    border-radius: 30px;
    transition: 0.3s;
}

.primary-btn:hover {
    background: #5436d8;
}

/* ===== VALUES ===== */
.values-layout {
    margin-top: 60px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 40px;
}

.value-card {
    background: #ffffff;
    padding: 40px 30px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    transition: 0.4s ease;
}

.value-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.value-emoji {
    font-size: 40px;
    margin-bottom: 20px;
}

.value-title {
    font-size: 20px;
    margin-bottom: 15px;
}

.value-description {
    font-size: 15px;
    color: #666;
    line-height: 1.6;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 992px) {
    .story-layout {
        grid-template-columns: 1fr;
    }

    .values-layout {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 600px) {
    .values-layout {
        grid-template-columns: 1fr;
    }
}

.about-hero {
    padding: 6rem 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: white !important;
    background-image: url('https://cdn.pixabay.com/photo/2020/09/14/09/48/living-room-5570509_1280.jpg');
    background-position: center;
    background-size: cover;
    border-radius: 14px;
    position: relative;
    overflow: hidden;
    text-align: center;
    text-shadow: 2px 2px 5px rgb(150, 150, 150);
}

.about-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #ff6b35;
}

.hero-content h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    color: white;
}

.hero-subtitle {
    font-size: 1.2rem;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
    opacity: 0.9;
}

.about-content {
    padding: 6rem 0;
    background: #f8fafc;
}

.about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
}

.about-text h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 0.5rem;
}

.about-text h2::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 80px;
    height: 4px;
    background: #ff6b35;
    border-radius: 2px;
}

.about-text p {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #64748b;
    margin-bottom: 1.5rem;
}

.about-image {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 4px solid #ff6b35;
    position: relative;
}

.about-image::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 20px;
    right: -20px;
    bottom: -20px;
    background: #ff6b35;
    opacity: 0.1;
    border-radius: 16px;
    z-index: -1;
}

.about-image,
.story-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.about-image:hover .story-image {
    transform: scale(1.05);
}

.values-section {
    padding: 6rem 0;
    background: white;
}

.values-section h2 {
    font-size: 2.5rem;
    font-weight: 700;
    color: #0f172a;
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
    display: inline-block;
    left: 50%;
    transform: translateX(-50%);
}

.values-section h2::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: #ff6b35;
    border-radius: 2px;
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.value-item {
    text-align: center;
    padding: 2.5rem;
    background: #f8fafc;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.value-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #ff6b35;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.value-item:hover::before {
    transform: scaleX(1);
}

.value-item:hover {
    transform: translateY(-5px);
    border-color: #ff6b35;
    box-shadow: 0 10px 30px rgba(255, 107, 53, 0.15);
}

.value-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
}

.value-item h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 1rem;
}

.value-item p {
    font-size: 1rem;
    color: #64748b;
    line-height: 1.6;
}

.stats-section {
    padding: 6rem 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 14px;
    color: white;
    position: relative;
    background-image: url('https://cdn.pixabay.com/photo/2020/06/25/10/21/architecture-5339245_1280.jpg');
    background-position: center;
    background-size: cover;
}

.stats-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #ff6b35;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.stat-item {
    text-align: center;
    padding: 2rem;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 16px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #ff6b35;
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.stat-item:hover::before {
    transform: scaleX(1);
}

.stat-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-5px);
    border-color: rgba(255, 107, 53, 0.3);
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #ff6b35 0%, #ff8e53 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
}

.stat-number::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 3px;
    background: #ff6b35;
    border-radius: 2px;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
    font-weight: 500;
    margin-top: 1rem;
}

/* Buttons - for any future CTA buttons */
.btn-primary {
    background: linear-gradient(135deg, #ff6b35 0%, #ff8e53 100%);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e55a2b 0%, #e57a40 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(255, 107, 53, 0.3);
}

.btn-outline-primary {
    background: transparent;
    border: 2px solid #ff6b35;
    color: #ff6b35;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #ff6b35;
    color: white;
    transform: translateY(-2px);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .hero-content h1 {
        font-size: 2.5rem;
    }

    .about-grid {
        gap: 3rem;
    }
}

@media (max-width: 768px) {
    .about-hero {
        padding: 4rem 0;
    }

    .hero-content h1 {
        font-size: 2.2rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
        padding: 0 1rem;
    }

    .about-content,
    .values-section,
    .stats-section {
        padding: 4rem 0;
    }

    .about-grid {
        grid-template-columns: 1fr;
        gap: 2rem;
        padding: 0 1rem;
    }

    .about-text h2,
    .values-section h2 {
        font-size: 2rem;
    }

    .story-image {
        height: 400px;
    }

    .values-grid {
        grid-template-columns: 1fr;
        padding: 0 1rem;
    }

    .value-item {
        padding: 2rem;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        padding: 0 1rem;
        gap: 1.5rem;
    }

    .stat-number {
        font-size: 2.5rem;
    }
}

@media (max-width: 480px) {
    .hero-content h1 {
        font-size: 1.8rem;
    }

    .about-text h2 {
        font-size: 1.8rem;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .value-icon {
        font-size: 2.5rem;
    }
}
</style>