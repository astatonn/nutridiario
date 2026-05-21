<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>{{ config('app.name') }} - Acompanhamento Nutricional Inteligente</title>
	<meta name="description" content="Sistema inteligente de acompanhamento nutricional. Transforme sua alimentação com tecnologia de ponta.">
	<meta name="keywords" content="nutrição, dieta, acompanhamento nutricional, saúde, alimentação saudável">
	
	<link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

	<style>		
		/* Animações */
		.slide-in-bottom{-webkit-animation:slide-in-bottom .5s cubic-bezier(.25,.46,.45,.94) both;animation:slide-in-bottom .5s cubic-bezier(.25,.46,.45,.94) both}
		.slide-in-bottom-h1{-webkit-animation:slide-in-bottom .5s cubic-bezier(.25,.46,.45,.94) .5s both;animation:slide-in-bottom .5s cubic-bezier(.25,.46,.45,.94) .5s both}
		.slide-in-bottom-subtitle{-webkit-animation:slide-in-bottom .5s cubic-bezier(.25,.46,.45,.94) .75s both;animation:slide-in-bottom .5s cubic-bezier(.25,.46,.45,.94) .75s both}
		.fade-in{-webkit-animation:fade-in 1.2s cubic-bezier(.39,.575,.565,1.000) 1s both;animation:fade-in 1.2s cubic-bezier(.39,.575,.565,1.000) 1s both}
		.bounce-top-icons{-webkit-animation:bounce-top .9s 1s both;animation:bounce-top .9s 1s both}
		
		@-webkit-keyframes slide-in-bottom{0%{-webkit-transform:translateY(1000px);transform:translateY(1000px);opacity:0}100%{-webkit-transform:translateY(0);transform:translateY(0);opacity:1}}@keyframes slide-in-bottom{0%{-webkit-transform:translateY(1000px);transform:translateY(1000px);opacity:0}100%{-webkit-transform:translateY(0);transform:translateY(0);opacity:1}}
		@-webkit-keyframes bounce-top{0%{-webkit-transform:translateY(-45px);transform:translateY(-45px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in;opacity:1}24%{opacity:1}40%{-webkit-transform:translateY(-24px);transform:translateY(-24px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}65%{-webkit-transform:translateY(-12px);transform:translateY(-12px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}82%{-webkit-transform:translateY(-6px);transform:translateY(-6px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}93%{-webkit-transform:translateY(-4px);transform:translateY(-4px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}25%,55%,75%,87%{-webkit-transform:translateY(0);transform:translateY(0);-webkit-animation-timing-function:ease-out;animation-timing-function:ease-out}100%{-webkit-transform:translateY(0);transform:translateY(0);-webkit-animation-timing-function:ease-out;animation-timing-function:ease-out;opacity:1}}@keyframes bounce-top{0%{-webkit-transform:translateY(-45px);transform:translateY(-45px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in;opacity:1}24%{opacity:1}40%{-webkit-transform:translateY(-24px);transform:translateY(-24px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}65%{-webkit-transform:translateY(-12px);transform:translateY(-12px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}82%{-webkit-transform:translateY(-6px);transform:translateY(-6px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}93%{-webkit-transform:translateY(-4px);transform:translateY(-4px);-webkit-animation-timing-function:ease-in;animation-timing-function:ease-in}25%,55%,75%,87%{-webkit-transform:translateY(0);transform:translateY(0);-webkit-animation-timing-function:ease-out;animation-timing-function:ease-out}100%{-webkit-transform:translateY(0);transform:translateY(0);-webkit-animation-timing-function:ease-out;animation-timing-function:ease-out;opacity:1}}
		@-webkit-keyframes fade-in{0%{opacity:0}100%{opacity:1}}@keyframes fade-in{0%{opacity:0}100%{opacity:1}}

		/* Cores personalizadas - Verde do formulário */
		.text-nutrigreen { color: #4CAF50; }
		.bg-nutrigreen { background-color: #4CAF50; }
		.border-nutrigreen { border-color: #4CAF50; }
		.hover\:bg-nutrigreen-dark:hover { background-color: #2E7D32; }
		.hover\:bg-nutrigreen-light:hover { background-color: #66BB6A; }
		
		/* Efeito glow */
		.glow-green {
			text-shadow: 0 0 10px #4CAF50, 0 0 20px #4CAF50, 0 0 30px #4CAF50;
		}
		
		.btn-glow {
			box-shadow: 0 0 20px rgba(76, 175, 80, 0.5);
			transition: all 0.3s ease;
		}
		
		.btn-glow:hover {
			box-shadow: 0 0 30px rgba(76, 175, 80, 0.8), 0 0 40px rgba(76, 175, 80, 0.6);
			transform: translateY(-2px);
		}

		/* Grid pattern background */
		.grid-bg {
			background-image: 
				linear-gradient(rgba(76, 175, 80, 0.03) 1px, transparent 1px),
				linear-gradient(90deg, rgba(76, 175, 80, 0.03) 1px, transparent 1px);
			background-size: 50px 50px;
		}

		/* Logo styles */
		.nav-logo {
			height: 250px;
			width: auto;
			transition: opacity 0.3s ease;
		}

		.nav-logo:hover {
			opacity: 0.8;
		}

		/* Footer styles */
		.footer-social {
			background: #0f0f10;
			padding: 60px 20px 30px;
			margin-top: 80px;
			border-top: 1px solid rgba(76, 175, 80, 0.1);
		}

		.footer-content {
			max-width: 1200px;
			margin: 0 auto;
			text-align: center;
		}

		.footer-logo {
			margin-bottom: 30px;
		}

		.footer-logo-img {
			width: 180px;
			height: auto;
			opacity: 0.9;
			margin: 0 auto;
		}

		.social-links {
			display: flex;
			justify-content: center;
			gap: 20px;
			margin-bottom: 40px;
			flex-wrap: wrap;
		}

		.social-link {
			width: 50px;
			height: 50px;
			border-radius: 50%;
			background: rgba(76, 175, 80, 0.1);
			display: flex;
			align-items: center;
			justify-content: center;
			color: #4CAF50;
			transition: all 0.3s ease;
			text-decoration: none;
		}

		.social-link:hover {
			background: #4CAF50;
			color: #1d1d1f;
			transform: translateY(-5px);
			box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
		}

		.social-link svg {
			width: 24px;
			height: 24px;
		}

		.footer-copyright {
			color: rgba(255, 255, 255, 0.6);
			font-size: 14px;
			line-height: 1.6;
			padding-top: 30px;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
		}

		.footer-copyright p {
			margin: 5px 0;
		}

		.footer-tagline {
			color: rgba(76, 175, 80, 0.7);
			font-weight: 500;
			font-size: 15px;
		}

		@media (max-width: 768px) {
			.nav-logo {
				height: 150px;
			}

			.footer-social {
				padding: 40px 20px 20px;
				margin-top: 60px;
			}

			.footer-logo-img {
				width: 150px;
			}

			.social-links {
				gap: 15px;
			}

			.social-link {
				width: 45px;
				height: 45px;
			}

			.footer-copyright {
				font-size: 13px;
			}
		}
	</style>
</head>

<body class="leading-normal tracking-normal text-white grid-bg" style="font-family: 'Inter', sans-serif; background-color: #1d1d1f;">

<div class="min-h-screen pb-14">
	<!-- Nav -->
	<div class="w-full container mx-auto p-6">
		<div class="w-full flex items-center justify-between">
			<a class="flex items-center no-underline hover:no-underline" href="/"> 
				<img src="{{ asset('images/logo_with_text.png') }}" alt="{{ config('app.name') }}" class="nav-logo">
			</a>
			
			<div class="flex items-center space-x-4">
				<a href="#features" class="hidden md:inline-block text-gray-300 hover:text-nutrigreen transition-colors no-underline px-4">
					Recursos
				</a>
				<a href="{{ route('planoalimentar') }}" class="inline-block bg-nutrigreen hover:bg-nutrigreen-dark text-white font-bold py-2 px-6 rounded-full transition-all btn-glow">
					Começar Agora
				</a>
			</div>
		</div>
	</div>

	<!-- Main -->
	<div class="container pt-16 md:pt-32 px-6 mx-auto flex flex-wrap flex-col md:flex-row items-center">
		
		<!-- Left Col -->
		<div class="flex flex-col w-full xl:w-2/5 justify-center lg:items-start overflow-y-hidden">
			<h1 class="my-4 text-4xl md:text-6xl font-extrabold leading-tight text-center md:text-left slide-in-bottom-h1">
				<span class="text-white">Transforme sua</span><br>
				<span class="text-nutrigreen">Nutrição</span>
			</h1>
			<p class="leading-normal text-xl md:text-2xl mb-8 text-gray-300 text-center md:text-left slide-in-bottom-subtitle">
				Acompanhamento nutricional inteligente e personalizado. 
				Alcance seus objetivos com tecnologia de ponta.
			</p>

			<div class="flex flex-col w-full justify-center md:justify-start pb-24 lg:pb-0 fade-in" id="cta">
				<a href="{{ route('planoalimentar') }}" class="bg-nutrigreen hover:bg-nutrigreen-dark text-white font-bold text-lg py-4 px-8 rounded-full transition-all btn-glow mb-4 w-full md:w-auto text-center no-underline">
					🥗 Quero fazer meu acompanhamento
				</a>
				<p class="text-gray-400 text-sm text-center md:text-left mt-2">
					✓ Plano personalizado &nbsp; ✓ Acompanhamento 24/7 &nbsp; ✓ Resultados garantidos
				</p>
			</div>
		</div>
		
		<!-- Right Col -->
		<div class="w-full xl:w-3/5 py-6 overflow-y-hidden" id="features">
			<div class="w-5/6 mx-auto lg:mr-0 slide-in-bottom">
				<!-- Feature Cards -->
				<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
					<!-- Card 1 -->
					<div class="bg-gray-800 bg-opacity-50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700 hover:border-nutrigreen transition-all">
						<div class="text-nutrigreen text-3xl mb-4">📊</div>
						<h3 class="text-xl font-bold mb-2 text-white">Análise Completa</h3>
						<p class="text-gray-400">Monitore macros, calorias e nutrientes em tempo real com precisão científica.</p>
					</div>
					
					<!-- Card 2 -->
					<div class="bg-gray-800 bg-opacity-50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700 hover:border-nutrigreen transition-all">
						<div class="text-nutrigreen text-3xl mb-4">🎯</div>
						<h3 class="text-xl font-bold mb-2 text-white">Metas Personalizadas</h3>
						<p class="text-gray-400">Planos adaptados aos seus objetivos, seja ganho de massa ou emagrecimento.</p>
					</div>
					
					<!-- Card 3 -->
					<div class="bg-gray-800 bg-opacity-50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700 hover:border-nutrigreen transition-all">
						<div class="text-nutrigreen text-3xl mb-4">🤖</div>
						<h3 class="text-xl font-bold mb-2 text-white">IA Avançada</h3>
						<p class="text-gray-400">Inteligência artificial que aprende com seus hábitos e sugere melhorias.</p>
					</div>
					
					<!-- Card 4 -->
					<div class="bg-gray-800 bg-opacity-50 backdrop-blur-sm rounded-2xl p-6 border border-gray-700 hover:border-nutrigreen transition-all">
						<div class="text-nutrigreen text-3xl mb-4">📱</div>
						<h3 class="text-xl font-bold mb-2 text-white">App Completo</h3>
						<p class="text-gray-400">Acesse de qualquer lugar, sincronize dados e acompanhe seu progresso.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Footer -->
<footer class="footer-social">
	<div class="footer-content">
		<div class="footer-logo">
			<img src="{{ asset('images/logo_with_text.png') }}" alt="{{ config('app.name') }}" class="footer-logo-img">
		</div>
		
		<div class="social-links">
			<a href="https://instagram.com/nutridiario.app" target="_blank" class="social-link" title="Instagram">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
					<path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
				</svg>
			</a>
			<a href="https://youtube.com/@nutridiario" target="_blank" class="social-link" title="YouTube">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
					<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
				</svg>
			</a>
		</div>

		<div class="footer-copyright">
			<p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.</p>
			<p class="footer-tagline">Transformando vidas através da nutrição inteligente</p>
		</div>
	</div>
</footer>

</body>
</html>
