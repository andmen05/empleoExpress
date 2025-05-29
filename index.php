<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EmpleoExpress</title>
    <link rel="stylesheet" href="css/presentacion.css">
    
    <script src="js/script.js">script,JSON</script>

    <script src="https://kit.fontawesome.com/41bcea2ae3.js" crossorigin="anonymous"></script>

    <style>
        /* Estilos para la sección "Sobre Nosotros" */
        #about {
            padding: 50px 0;
            background-color: #f9f9f9;
            text-align: center;
        }

        #about h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        #about p {
            font-size: 1.2em;
            color: #666;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Estilos para la sección "Servicios" */
        #services {
            padding: 50px 0;
            background-color: #fff;
            text-align: center;
        }

        #services h2 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        #services ul {
            list-style: none;
            padding: 0;
        }

        #services ul li {
            font-size: 1.2em;
            color: #666;
            margin: 10px 0;
        }
    </style>
</head>
<body>

    <!--Header - Menu-->

    <header>
        <div class="container__header">
            <div class="logo">
                <a href="#">
                    <img src="images/Logo/logo.png" alt="">
                </a>
            </div>

            <div class="menu">
                <nav>
                    <ul>
                        <li><a href="#">PACHO</a></li>
                        <li><a href="#about">Sobre nosotros</a></li>
                        <li><a href="#services">Servicios</a></li>
                        <li><a href="#contact">Contactos</a></li>
                    </ul>
                </nav>

                <div class="socialMedia">
                    <a href="#">
                        <img src="images/social media/facebook.png" alt="">
                    </a>
                    <a href="#">
                        <img src="images/social media/instagram.png" alt="">
                    </a>
                    <a href="#">
                        <img src="images/social media/twitter.png" alt="">
                    </a>
                    <a href="#">
                        <img src="images/social media/youtube.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main>

        <!--INICIO-->
        <!--Portada de inicio-->
        <div class="container__cover div__offset">
            <div class="cover">
                <section class="text__cover">
                    <h1>Bienvenido a EmpleoExpress</h1>
                    <p>Tu plataforma ideal para encontrar el trabajo de tus sueños de manera rápida y sencilla.</p>
                    <a href="php/login.php" class="btn__text-cover btn__text">Regístrate Ahora</a>
                </section>
                <section class="image__cover">
                    <img src="images/Cover/hero-img.png" alt="">
                </section>
            </div>
        </div>
       <!--Sobre nosotros - Nuestro equipo-->
     <section id="about">
        <h2>Sobre Nosotros</h2>
        <p>En EmpleoExpress, nos dedicamos a conectar a los mejores talentos con las mejores oportunidades laborales. Nuestra misión es facilitar el proceso de búsqueda de empleo para todos.</p>
    </section>

    <section id="services">
        <h2>Servicios</h2>
        <ul>
            <li>Búsqueda de empleo personalizada</li>
            <li>Asesoramiento profesional</li>
            <li>Alertas de empleo</li>
            <li>Recursos para mejorar tu CV</li>
        </ul>
    </section>

    <!-- Sección de Testimonios -->
<section id="testimonials">
    <h2>Testimonios</h2>
    <div class="testimonial active">
        <p>"EmpleoExpress me ayudó a encontrar el trabajo perfecto en solo una semana. ¡Increíble!" - Juan Pérez</p>
    </div>
    <div class="testimonial">
        <p>"Gracias a EmpleoExpress, ahora tengo el trabajo de mis sueños. ¡Muy recomendable!" - María López</p>
    </div>
    <div class="testimonial">
        <p>"La mejor plataforma para buscar empleo. Fácil de usar y muy efectiva." - Carlos García</p>
    </div>
</section>


        <!--Sobre nosotros - Nuestro equipo-->

        <div class="container__about div__offset">
            <div class="about">
                <div class="text__about">
                    <h1>Plataforma que conecta talento con oportunidades</h1>
<p>En EmpleoExpress, nuestro equipo de expertos trabaja incansablemente para ayudarte a encontrar las mejores oportunidades laborales. Nos enfocamos en brindarte las herramientas y el apoyo necesario para alcanzar tus metas profesionales y destacar en el mercado laboral.</p>
                </div>
                
                <div class="image__about">
                    <img src="images/About/about-1.png" alt="">
                    <img src="images/About/about-2.png" alt="">
                </div>
            </div>
            
        </div>

        <!--contactanos-->

        <section id="contact">
            <h2>Contacto</h2>
            <p>¿Tienes alguna pregunta? Contáctanos a través de nuestro formulario de contacto o síguenos en nuestras redes sociales.</p>
            <form action="submit_form.php" method="post">
                <label for="name">Nombre:</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" required>
                <label for="message">Mensaje:</label>
                <textarea id="message" name="message" rows="4" required></textarea>
                <input type="submit" value="Enviar">
            </form>
        </section>
    
        <footer>
            <p>&copy; 2023 EmpleoExpress. Todos los derechos reservados.</p>
        </footer>
    </body>
    </html>
