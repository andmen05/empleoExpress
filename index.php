<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmpleoExpress</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #ffffff;
            color: #333;
            overflow-x: hidden;
        }
        
        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
        }
        
        .navbar.scrolled {
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        
        .hero {
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            color: white;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="white" opacity="0.1"><polygon points="0,100 1000,0 1000,100"/></svg>');
            background-size: cover;
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            animation: fadeInUp 1s ease-out;
        }
        
        .hero p {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out 0.2s both;
        }
        
        .hero .btn {
            animation: fadeInUp 1s ease-out 0.4s both;
            padding: 12px 30px;
            font-size: 1.1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        
        .hero .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
        
        .services {
            background: #f8f9fa;
            padding: 80px 0;
        }
        
        .card {
            border: none;
            border-radius: 20px;
            transition: all 0.3s ease;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        .card .fas {
            color: #4c8bca;
            transition: all 0.3s ease;
        }
        
        .card:hover .fas {
            transform: scale(1.1);
        }
        
        .testimonials {
            background: white;
            padding: 80px 0;
        }
        
        .testimonial-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s ease;
            border: none;
        }
        
        .testimonial-card:hover {
            transform: scale(1.02);
            box-shadow: 0 15px 35px rgba(76, 139, 202, 0.1);
        }
        
        .stats-section {
            background: #4c8bca;
            color: white;
            padding: 60px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            display: block;
            margin-bottom: 10px;
        }
        
        footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 50px 0 20px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #3a6f9a 0%, #2d5a7b 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(76, 139, 202, 0.3);
        }
        
        .text-primary {
            color: #4c8bca !important;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2c3e50;
        }
        
        .section-subtitle {
            font-size: 1.2rem;
            color: #6c757d;
            margin-bottom: 3rem;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .floating-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #4c8bca 0%, #3a6f9a 100%);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 25px rgba(76, 139, 202, 0.3);
            transition: all 0.3s ease;
            z-index: 1000;
            text-decoration: none;
        }
        
        .floating-button:hover {
            background: linear-gradient(135deg, #3a6f9a 0%, #2d5a7b 100%);
            transform: scale(1.1);
            color: white;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/Logo/logo.png" alt="EmpleoExpress" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Sobre nosotros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Servicios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contacto</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="php/login.php" class="btn btn-primary">Iniciar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4">Encuentra el trabajo de tus sueños</h1>
                    <p class="lead">EmpleoExpress te conecta con las mejores oportunidades laborales. Regístrate ahora y da el siguiente paso en tu carrera profesional.</p>
                    <a href="php/register_user.php" class="btn btn-light btn-lg">
                        <i class="fas fa-rocket me-2"></i>Regístrate Gratis
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="images/Cover/hero-img.png" alt="Hero" class="img-fluid" style="max-height: 400px;">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 stat-item">
                    <span class="stat-number">1000+</span>
                    <p class="mb-0">Empresas Registradas</p>
                </div>
                <div class="col-md-4 stat-item">
                    <span class="stat-number">5000+</span>
                    <p class="mb-0">Empleos Disponibles</p>
                </div>
                <div class="col-md-4 stat-item">
                    <span class="stat-number">10000+</span>
                    <p class="mb-0">Usuarios Activos</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Nuestros Servicios</h2>
                <p class="section-subtitle">Soluciones completas para tu búsqueda laboral</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-search fa-3x mb-4"></i>
                            <h3 class="h4 mb-3">Búsqueda Inteligente</h3>
                            <p class="text-muted">Encuentra el trabajo perfecto con nuestro sistema de búsqueda avanzada y filtros personalizados.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-user-tie fa-3x mb-4"></i>
                            <h3 class="h4 mb-3">Perfil Profesional</h3>
                            <p class="text-muted">Crea un perfil destacado que resalte tus habilidades y experiencia para atraer a los mejores empleadores.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-bell fa-3x mb-4"></i>
                            <h3 class="h4 mb-3">Alertas de Empleo</h3>
                            <p class="text-muted">Recibe notificaciones instantáneas de las ofertas laborales que más se ajusten a tu perfil profesional.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Sobre Nosotros</h2>
                    <p class="lead mb-4">En EmpleoExpress, nos dedicamos a conectar el talento con las mejores oportunidades laborales. Nuestra plataforma facilita el proceso de búsqueda de empleo y selección de personal.</p>
                    <div class="row g-3">
                        <div class="col-4 text-center">
                            <h3 class="h2 fw-bold text-primary">98%</h3>
                            <p class="mb-0">Satisfacción</p>
                        </div>
                        <div class="col-4 text-center">
                            <h3 class="h2 fw-bold text-primary">24/7</h3>
                            <p class="mb-0">Soporte</p>
                        </div>
                        <div class="col-4 text-center">
                            <h3 class="h2 fw-bold text-primary">100%</h3>
                            <p class="mb-0">Seguro</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="images/About/about-1.png" alt="About Us" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Testimonios</h2>
                <p class="section-subtitle">Lo que dicen nuestros usuarios</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="images/Team/team-1.jpg" alt="Juan Pérez" class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                                <div>
                                    <h5 class="mb-0">Juan Pérez</h5>
                                    <small class="text-muted">Desarrollador Web</small>
                                </div>
                            </div>
                            <p class="mb-0 fst-italic">"EmpleoExpress me ayudó a encontrar mi trabajo ideal en menos de una semana. ¡Increíble plataforma!"</p>
                            <div class="text-warning mt-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="images/Team/team-2.jpg" alt="María López" class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                                <div>
                                    <h5 class="mb-0">María López</h5>
                                    <small class="text-muted">Diseñadora UX</small>
                                </div>
                            </div>
                            <p class="mb-0 fst-italic">"La mejor plataforma para encontrar talento. El proceso de contratación fue muy sencillo y rápido."</p>
                            <div class="text-warning mt-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <img src="images/Team/team-3.jpg" alt="Carlos García" class="rounded-circle me-3" width="60" height="60" style="object-fit: cover;">
                                <div>
                                    <h5 class="mb-0">Carlos García</h5>
                                    <small class="text-muted">Marketing Digital</small>
                                </div>
                            </div>
                            <p class="mb-0 fst-italic">"Gracias a EmpleoExpress pude dar el siguiente paso en mi carrera. ¡Altamente recomendado!"</p>
                            <div class="text-warning mt-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center mb-5">
                        <h2 class="section-title">Contacto</h2>
                        <p class="section-subtitle">¿Tienes alguna pregunta? Estamos aquí para ayudarte</p>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-5">
                            <form action="submit_form.php" method="post">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nombre</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Mensaje</label>
                                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-primary btn-lg px-5">
                                            <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="images/Logo/logo.png" alt="EmpleoExpress" height="60" class="mb-3">
                    <p class="mb-3">Conectando talento con oportunidades. Tu próximo trabajo te está esperando.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <h5 class="mb-3">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#inicio" class="text-white text-decoration-none">Inicio</a></li>
                        <li class="mb-2"><a href="#about" class="text-white text-decoration-none">Sobre Nosotros</a></li>
                        <li class="mb-2"><a href="#services" class="text-white text-decoration-none">Servicios</a></li>
                        <li class="mb-2"><a href="#contact" class="text-white text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="mb-3">Contacto</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-phone me-2"></i> +57 123 456 7890</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@empleoexpress.com</li>
                        <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Cúcuta, Colombia</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 EmpleoExpress. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Button -->
    <a href="#inicio" class="floating-button">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe cards for animation
        document.querySelectorAll('.card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    </script>
</body>
</html>