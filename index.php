<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmpleoExpress</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/presentacion.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Montserrat:wght@600&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color:#4c8bca;
            color: #333;
            overflow-x: hidden;
        }
        .navbar {
            background-color: rgba(255, 255, 255, 0.9);
            transition: background-color 0.3s;
        }
        .navbar.scrolled {
            background-color: rgb(255, 255, 255);
        }
        .hero {
            position: relative;
            height: 100vh;
            background: url('images/Cover/hero-bg.jpg') no-repeat center center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 0 20px;
        }
        .hero h1 {
            font-size: 3.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }
        .hero p {
            font-size: 1.25rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }
        .services {
            background: #ffffff;
            padding: 50px 0;
        }
        .card {
            border-radius: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.2);
        }
        .testimonials {
            background: #f0f4f8;
            padding: 50px 0;
        }
        .testimonial-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 20px;
            transition: transform 0.3s;
        }
        .testimonial-card:hover {
            transform: scale(1.05);
        }
        footer {
            background-color: #343a40;
            color: white;
        }
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: rgb(76, 139, 202);
            color: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }
        .floating-button:hover {
            background-color: #4c8bca;
        }
        .btn-primary {
            background-color: #4c8bca;
            border-color: #4c8bca;
        }
        .btn-primary:hover {
            background-color: #3a6f9a;
            border-color: #3a6f9a;
        }
        .text-primary {
            color: #4c8bca !important;
        }
    </style>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/Logo/logo.png" alt="EmpleoExpress" height="60">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inicio</a>
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
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/Logo/logo.png" alt="EmpleoExpress" height="60">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Inicio</a>
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
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Encuentra el trabajo de tus sueños</h1>
            <p class="lead mb-4">EmpleoExpress te conecta con las mejores oportunidades laborales. Regístrate ahora y da el siguiente paso en tu carrera profesional.</p>
            <a href="php/register_user.php" class="btn btn-light btn-lg">Regístrate Gratis</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Nuestros Servicios</h2>
                <p class="lead">Soluciones completas para tu búsqueda laboral</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-search fa-3x text-primary mb-3"></i>
                            <h3 class="h4 mb-3">Búsqueda Inteligente</h3>
                            <p>Encuentra el trabajo perfecto con nuestro sistema de búsqueda avanzada.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                            <h3 class="h4 mb-3">Perfil Profesional</h3>
                            <p>Crea un perfil destacado para atraer a los mejores empleadores.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                            <h3 class="h4 mb-3">Alertas de Empleo</h3>
                            <p>Recibe notificaciones de las ofertas que más te interesan.</p>
                        </div>
                    </div>
    <!-- Hero Section -->
    <section class="hero bg-light py-5 mt-5">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Encuentra el trabajo de tus sueños</h1>
                    <p class="lead mb-4">EmpleoExpress te conecta con las mejores oportunidades laborales. Regístrate ahora y da el siguiente paso en tu carrera profesional.</p>
                    <a href="php/register_user.php" class="btn btn-primary btn-lg">Regístrate Gratis</a>
                </div>
                <div class="col-lg-6">
                    <img src="images/Cover/hero-img.png" alt="Hero" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">Sobre Nosotros</h2>
                    <p class="lead mb-4">En EmpleoExpress, nos dedicamos a conectar el talento con las mejores oportunidades laborales. Nuestra plataforma facilita el proceso de búsqueda de empleo y selección de personal.</p>
                    <div class="d-flex gap-3">
                        <div class="text-center">
                            <h3 class="h2 fw-bold text-primary">1000+</h3>
                            <p>Empresas</p>
                        </div>
                        <div class="text-center">
                            <h3 class="h2 fw-bold text-primary">5000+</h3>
                            <p>Empleos</p>
                        </div>
                        <div class="text-center">
                            <h3 class="h2 fw-bold text-primary">10000+</h3>
                            <p>Usuarios</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="images/About/about-1.png" alt="About Us" class="img-fluid rounded shadow">
                </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Nuestros Servicios</h2>
                <p class="lead">Soluciones completas para tu búsqueda laboral</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-search fa-3x text-primary mb-3"></i>
                            <h3 class="h4 mb-3">Búsqueda Inteligente</h3>
                            <p>Encuentra el trabajo perfecto con nuestro sistema de búsqueda avanzada.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                            <h3 class="h4 mb-3">Perfil Profesional</h3>
                            <p>Crea un perfil destacado para atraer a los mejores empleadores.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-bell fa-3x text-primary mb-3"></i>
                            <h3 class="h4 mb-3">Alertas de Empleo</h3>
                            <p>Recibe notificaciones de las ofertas que más te interesan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">Sobre Nosotros</h2>
                    <p class="lead mb-4">En EmpleoExpress, nos dedicamos a conectar el talento con las mejores oportunidades laborales. Nuestra plataforma facilita el proceso de búsqueda de empleo y selección de personal.</p>
                    <div class="d-flex gap-3">
                        <div class="text-center">
                            <h3 class="h2 fw-bold text-primary">1000+</h3>
                            <p>Empresas</p>
                        </div>
                        <div class="text-center">
                            <h3 class="h2 fw-bold text-primary">5000+</h3>
                            <p>Empleos</p>
                        </div>
                        <div class="text-center">
                            <h3 class="h2 fw-bold text-primary">10000+</h3>
                            <p>Usuarios</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="images/About/about-1.png" alt="About Us" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5 testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Testimonios</h2>
                <p class="lead">Lo que dicen nuestros usuarios</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="testimonial-card p-4 rounded shadow">
                        <p>"EmpleoExpress me ayudó a encontrar mi trabajo ideal en menos de una semana."</p>
                        <footer>— Juan Pérez, Desarrollador Web</footer>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card p-4 rounded shadow">
                        <p>"La mejor plataforma para encontrar talento."</p>
                        <footer>— María López, Diseñadora UX</footer>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card p-4 rounded shadow">
                        <p>"Gracias a EmpleoExpress pude dar el siguiente paso en mi carrera."</p>
                        <footer>— Carlos García, Marketing Digital</footer>
                    </div>
                </div>
            </div>
        </div>
    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Testimonios</h2>
                <p class="lead">Lo que dicen nuestros usuarios</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="images/Team/team-1.jpg" alt="User" class="rounded-circle" width="60">
                                <div class="ms-3">
                                    <h5 class="mb-0">Juan Pérez</h5>
                                    <small class="text-muted">Desarrollador Web</small>
                                </div>
                            </div>
                            <p class="mb-0">"EmpleoExpress me ayudó a encontrar mi trabajo ideal en menos de una semana. ¡Increíble plataforma!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="images/Team/team-2.jpg" alt="User" class="rounded-circle" width="60">
                                <div class="ms-3">
                                    <h5 class="mb-0">María López</h5>
                                    <small class="text-muted">Diseñadora UX</small>
                                </div>
                            </div>
                            <p class="mb-0">"La mejor plataforma para encontrar talento. El proceso de contratación fue muy sencillo y rápido."</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <img src="images/Team/team-3.jpg" alt="User" class="rounded-circle" width="60">
                                <div class="ms-3">
                                    <h5 class="mb-0">Carlos García</h5>
                                    <small class="text-muted">Marketing Digital</small>
                                </div>
                            </div>
                            <p class="mb-0">"Gracias a EmpleoExpress pude dar el siguiente paso en mi carrera. ¡Altamente recomendado!"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Contacto</h2>
                <p class="lead">¿Tienes alguna pregunta? Estamos aquí para ayudarte</p>
            </div>
            <div class="card border-0 shadow">
                <div class="card-body p-4">
                    <form action="submit_form.php" method="post">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Mensaje</label>
                            <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Enviar Mensaje</button>
                    </form>
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
                        <h2 class="display-5 fw-bold">Contacto</h2>
                        <p class="lead">¿Tienes alguna pregunta? Estamos aquí para ayudarte</p>
                    </div>
                    <div class="card border-0 shadow">
                        <div class="card-body p-4">
                            <form action="submit_form.php" method="post">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Mensaje</label>
                                    <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Enviar Mensaje</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="images/Logo/logo.png" alt="EmpleoExpress" height="60" class="mb-3">
                    <p class="mb-0">Conectando talento con oportunidades.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="mb-3">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-decoration-none">Inicio</a></li>
                        <li><a href="#about" class="text-decoration-none">Sobre Nosotros</a></li>
                        <li><a href="#services" class="text-decoration-none">Servicios</a></li>
                        <li><a href="#contact" class="text-decoration-none">Contacto</a></li>
                    </ul>
    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <img src="images/Logo/logo.png" alt="EmpleoExpress" height="60" class="mb-3">
                    <p class="mb-0">Conectando talento con oportunidades.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="mb-3">Enlaces Rápidos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Inicio</a></li>
                        <li><a href="#about" class="text-white text-decoration-none">Sobre Nosotros</a></li>
                        <li><a href="#services" class="text-white text-decoration-none">Servicios</a></li>
                        <li><a href="#contact" class="text-white text-decoration-none">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="mb-3">Síguenos</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-decoration-none"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-decoration-none"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-decoration-none"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-decoration-none"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                <div class="col-lg-4">
                    <h5 class="mb-3">Síguenos</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-lg"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 EmpleoExpress. Todos los derechos reservados.</p>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2023 EmpleoExpress. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Cambiar el color de la navbar al hacer scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>

    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="js/script.js"></script>
</body>
</html>