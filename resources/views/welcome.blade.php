<x-app-layout>
    <style>
        .tibetan-font {
            font-family: 'MonlamTBslim', sans-serif;
            line-height: 2;
        }
        p{
            line-height: 2;
        }
        /* Animation keyframes */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        @keyframes scaleIn {
            from { transform: scale(0.9); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .hero-section {
            background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
            padding: 7rem 2rem;
            position: relative;
            overflow: hidden;
            color: white;
            animation: fadeIn 1s ease-out;
        }
        @media (max-width: 768px) {
            .hero-section {
                padding: 4rem 1rem;
            }
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQ0MCIgaGVpZ2h0PSI3NjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IHgxPSIwJSIgeTE9IjEwMCUiIHgyPSIxMDAlIiB5Mj0iMCUiIGlkPSJhIj48c3RvcCBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjAuMDUiIG9mZnNldD0iMCUiLz48c3RvcCBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjAuMSIgb2Zmc2V0PSIxMDAlIi8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PHBhdGggZD0iTTAgNzYwSDE0NDBWMEgwVjc2MFoiIGZpbGw9InVybCgjYSkiLz48cGF0aCBkPSJNMjEyLjQgNDE2LjJDMzA2LjcgMzQ3LjkgNDQ0IDM4OCA0ODYuOCAzMTcuMkM1MjkuNiAyNDYuNCA0MTAuNSAxMDUuOCA2NjcgNzBIMCBWNzYwSDI2NC44QzQxMS43IDcxOCAxNzkuNyA2MjAuNyAyMTIuNCA0MTYuMloiIGZpbGw9InVybCgjYSkiLz48L3N2Zz4=');
            background-size: cover;
            opacity: 0.1;
            z-index: 0;
        }
        .hero-content {
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease-out 0.2s both;
        }
        .feature-card {
            transition: all 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(229, 231, 235, 0.5);
            background: white;
            padding: 2.5rem 2rem;
            margin: 6px;
            opacity: 0;
            animation: scaleIn 0.5s ease-out forwards;
        }
        @media (max-width: 768px) {
            .feature-card {
                padding: 1.5rem 1rem;
            }
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
            border-color: rgba(79, 70, 229, 0.3);
        }
        .feature-icon {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            border-radius: 12px;
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.25);
            animation: pulse 2s infinite;
        }
        .btn-primary {
            background: linear-gradient(135deg, #7067ff 0%, #6568fc 100%);
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            overflow: hidden;
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .btn-primary:hover::after {
            opacity: 1;
            animation: shine 1.5s ease-out;
        }
        @keyframes shine {
            from { transform: translateX(-100%) rotate(45deg); }
            to { transform: translateX(100%) rotate(45deg); }
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #4338ca 0%, #4f46e5 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.35);
        }
        .btn-secondary {
            background: rgba(255, 255, 255, 0.9);
            color: #4f46e5;
            padding: 1rem 2.5rem;
            border-radius: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(229, 231, 235, 0.5);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 2rem;
        }
        .section-heading {
            position: relative;
            display: inline-block;
            z-index: 1;
        }
        .section-heading::after {
            content: '';
            position: absolute;
            bottom: 0.25rem;
            left: 0;
            width: 100%;
            height: 0.75rem;
            background-color: rgba(99, 102, 241, 0.15);
            z-index: -1;
        }
        .text-center{
padding: 1rem;
padding-bottom: 1rem;
        }
    </style>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <div class="hero-content flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="md:w-1/2 space-y-8">
                    <h1 class="text-6xl font-bold mb-4 tracking-tight text-white">
                        <span>Monlam Melong</span>
                        <span class="block mt-2 tibetan-font text-white">བརྡ་མཛོད་ཆེན་མོ་ཡར་རྒྱས་གཏོང་བྱེད།</span>
                    </h1>
                    <p class="text-xl text-white/90 leading-relaxed max-w-xl" style="line-height: 2;">འདི་ནི་བོད་ཀྱི་བརྡ་མཛོད་ཆེན་མོ་ལེགས་བཅོས་བྱེད་པར་ཚད་ལྡན་གྱི་རྒྱུ་ཆ་བཟོ་སྐྲུན་དང་དོ་དམ་བྱེད་པའི་མཁོ་ཆས་ཤིག</p>
                    <div class="flex flex-wrap gap-4 pt-6">
                        <a href="{{ route('login') }}" class="btn-primary">
                            <span>འགོ་འཛུགས། (Get Started)</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#features" class="btn-secondary">
                            <span>ལྟ་ཞིབ། (Learn More)</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 flex justify-center">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-white/20 to-indigo-300/30 rounded-full opacity-70 blur-2xl animate-pulse"></div>
                        <img src="{{ asset('monlam-logo.png') }}" alt="Monlam Melong Logo" class="relative z-10 max-w-sm md:max-w-md transform hover:scale-105 transition duration-700 ease-in-out" style="width: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-gray-50">
        <script>
            // Animation script for feature cards
            document.addEventListener('DOMContentLoaded', function() {
                const featureCards = document.querySelectorAll('.feature-card');

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach((entry, index) => {
                        if (entry.isIntersecting) {
                            // Add delay based on index for staggered animation
                            setTimeout(() => {
                                entry.target.style.animation = `scaleIn 0.5s ease-out forwards`;
                            }, index * 150);
                        }
                    });
                }, { threshold: 0.2 });

                featureCards.forEach(card => {
                    observer.observe(card);
                });
            });
        </script>
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold mb-6 section-heading tibetan-font">ཁྱད་ཆོས་གཙོ་བོ།</h2>
                <h3 class="text-2xl mb-6">Platform Features</h3>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"style="line-height: 2">ང་ཚོས་མིས་བཟོས་རིག་ནུས་ཀྱི་རྣམ་དཔྱོད་དང་ལག་ལེན་དངོས་བཅས་མི་ལས་ལྷག་པ་ཞིག་ཡོང་ཆེད་ཞིབ་འཇུག་རྒྱ་ཆེན་དང་ལག་ལེན་ཉམས་མྱོང་བརྒྱུད་ནས་འབད་པ་བྱ་རྒྱུ་ཡིན། ང་ཚོའི་རིག་གཞུང་སྲུང་སྐྱོབས་དང་དར་སྤེལ་སླད་མིའི་ནུས་པ་འདེངས་མེད་པ་དེ་གློག་འཕྲུལ་གྱི་ནུས་པ་ཟུང་འབྲེལ་གྱིས་མི་གཅིག་གི་ནུས་པ་མ་མཐར་སྡབས་༢༠ སྤར་ཐུབ་པ་བྱ་དགོས།</p>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed"style="line-height: 2;">We will strive through extensive research and practical experience to develop artificial intelligence analysis and applications that surpass human capabilities. To preserve and promote our culture, we must combine unlimited human potential with computer power to ultimately increase an individual's capacity 20-fold.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">ཡིག་ཆ་ཚད་ལྡན།</h3>
                    <h4 class="text-xl font-medium text-gray-700 mb-3">High-Quality Training Data</h4>
                    <p class="text-gray-600 mb-6">ང་ཚོའི་མཉེན་ཆས་མ་ལག་གིས་སྐད་ཡིག་དཔེ་མཚོན་སྦྱོང་བརྡར་བྱེད་པར་དམིགས་ཏེ་ཚད་ལྡན་རྣམ་གཞག་ཅན་གྱི་དྲི་ལན་ཟུང་ལྡན་གྱིས་ཕྱོགས་ཡོངས་ནས་བསྡུ་རུབ་བྱེད་ཀྱིན་ཡོད་པ་དང་། དེས་སྦྱོང་བརྡར་གྲུབ་འབྲས་ཆེས་ལེགས་ཤོས་དེ་ཡོང་བར་ཡིད་ཆེས་བྱེད་ཀྱིན་ཡོད།</p>
                    <p class="text-gray-600 mb-6">Our platform collects comprehensive question-answer pairs specifically curated for language model training with standardized formatting to ensure optimal learning outcomes.</p>


                    <a href="{{ route('login') }}" class="text-indigo-600 font-semibold inline-flex items-center hover:text-indigo-800">
                        <span>མཐོང་བ།</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">ཞིབ་བཤེར་ལམ་ལུགས།</h3>
                    <h4 class="text-xl font-medium text-gray-700 mb-3">Advanced Quality Assurance</h4>
                    <p class="text-gray-600 mb-6">ང་ཚོའི་མཁས་པའི་བསྐྱར་ཞིབ་མ་ལག་གིས་མཐུན་སྦྱོར་ཚང་མ་ཆེས་མཐོ་བའི་ཚད་གཞི་ཁག་ལ་འཚམ་ཞིང་ངེས་བརྟན་བཟོ་ཆེད་ནན་ཏན་གྱིས་ར་སྤྲོད་ལག་ལེན་བྱུང་བ་ཁག་ལག་བསྟར་བྱེད་ཀྱིན་ཡོད་པ་དང་། དེའི་འབྲས་བུར་ངོ་མཚར་བའི་ཁྱད་འཕགས་སྦྱོང་བརྡར་གཞི་གྲངས་ཁག་སྐྲུན་ཐུབ་ཀྱི་ཡོད།</p>
                    <p class="text-gray-600 mb-6">Our expert review system implements rigorous validation protocols to ensure all contributions meet the highest standards, resulting in premium training datasets with exceptional accuracy.</p>

                    <a href="{{ route('login') }}" class="text-indigo-600 font-semibold inline-flex items-center hover:text-indigo-800">
                        <span>ཤེས་རྟོགས།</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-semibold mb-3">བོད་ཡིག་གཙོ་བཟུང་།</h3>
                    <h4 class="text-xl font-medium text-gray-700 mb-3">Complete Tibetan Language Integration</h4>
                    <p class="text-gray-600 mb-6">ང་ཚོའི་མ་ལག་ནང་ཆ་ཚང་བའི་བོད་ཡིག་མངོན་སྟོན་བྱེད་ནུས་དང་། དམིགས་བསལ་སྐད་བརྡའི་ལག་ཆ་ཁག རིག་གཞུང་དང་འཚམ་པའི་ནང་དོན་གསར་བསྐྲུན་གྱི་ནུས་པ་བཅས་ལྡན་པས་ཡང་དག་པའི་བོད་ཀྱི་སྐད་ཡིག་གོང་འཕེལ་ལ་རྒྱབ་སྐྱོར་བྱེད་ཀྱི་ཡོད།</p>
                    <p class="text-gray-600 mb-6">Our platform features comprehensive Tibetan text rendering, specialized linguistic tools, and culturally appropriate content creation capabilities to support authentic Tibetan language development.</p>

                    <a href="{{ route('login') }}" class="text-indigo-600 font-semibold inline-flex items-center hover:text-indigo-800">
                        <span>འཚོལ་ཞིབ།</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
            </div>


        </div>
    </div>

    <!-- Statistics Section -->
    <div class="py-20 bg-indigo-900 relative overflow-hidden">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQ0MCIgaGVpZ2h0PSI3NjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PGxpbmVhckdyYWRpZW50IHgxPSIwJSIgeTE9IjEwMCUiIHgyPSIxMDAlIiB5Mj0iMCUiIGlkPSJhIj48c3RvcCBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjAuMDUiIG9mZnNldD0iMCUiLz48c3RvcCBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjAuMSIgb2Zmc2V0PSIxMDAlIi8+PC9saW5lYXJHcmFkaWVudD48L2RlZnM+PHBhdGggZD0iTTAgNzYwSDE0NDBWMEgwVjc2MFoiIGZpbGw9InVybCgjYSkiLz48cGF0aCBkPSJNMjEyLjQgNDE2LjJDMzA2LjcgMzQ3LjkgNDQ0IDM4OCA0ODYuOCAzMTcuMkM1MjkuNiAyNDYuNCA0MTAuNSAxMDUuOCA2NjcgNzBIMCBWNzYwSDI2NC44QzQxMS43IDcxOCAxNzkuNyA2MjAuNyAyMTIuNCA0MTYuMloiIGZpbGw9InVybCgjYSkiLz48L3N2Zz4=')]" opacity="0.1"></div>
        <div class="container relative z-10">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-indigo-600 mb-4 tibetan-font">ང་ཚོའི་ནུས་པ།</h2>
                <h3 class="text-2xl text-indigo-200">Our Impact</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 text-center">
                <!-- Stat 1 -->
                <div class="p-6 rounded-xl bg-indigo-800/50 backdrop-blur-sm border border-indigo-700/50">
                    <div class="text-5xl font-bold text-white mb-2">900,000+</div>
                    <div class="text-xl text-indigo-200 tibetan-font">དྲི་བ་དང་ལན།</div>
                    <p class="text-indigo-200">Questions & Answers</p>
                </div>

                <!-- Stat 2 -->
                <div class="p-6 rounded-xl bg-indigo-800/50 backdrop-blur-sm border border-indigo-700/50">
                    <div class="text-5xl font-bold text-white mb-2">50+</div>
                    <div class="text-xl text-indigo-200 tibetan-font">སྡེ་ཚན་དང་བརྗོད་གཞི།</div>
                    <p class="text-indigo-200">Categories & Topics</p>
                </div>

                <!-- Stat 3 -->
                <div class="p-6 rounded-xl bg-indigo-800/50 backdrop-blur-sm border border-indigo-700/50">
                    <div class="text-5xl font-bold text-white mb-2">25+</div>
                    <div class="text-xl text-indigo-200 tibetan-font">ཆེད་མཁས་པ་དང་ལས་ཞུགས་པ།</div>
                    <p class="text-indigo-200">Expert Contributors</p>
                </div>

                <!-- Stat 4 -->
                <div class="p-6 rounded-xl bg-indigo-800/50 backdrop-blur-sm border border-indigo-700/50">
                    <div class="text-5xl font-bold text-white mb-2">98%</div>
                    <div class="text-xl text-indigo-200 tibetan-font">གཏན་འཁེལ་ཚད་གཞི།</div>
                    <p class="text-indigo-200">Accuracy Rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action Section -->
    <div class="py-24 bg-gradient-to-br from-white to-indigo-50">
        <div class="container">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-bold mb-6 tibetan-font">ང་ཚོའི་མཉམ་ལས་ནང་ཞུགས་རོགས།།</h2>
                <h3 class="text-3xl mb-6">Join Our Community</h3>
                <p class="text-xl text-gray-600 mb-12">སྦྱོང་བརྡར་རྒྱུ་ཆ་བཟོ་སྐྲུན་དང་ཚད་ལྡན་གྱི་མིས་བཟོས་རིག་ནུས་ལ་སྦྱོང་བརྡར་གྱིས་དུས་དང་མཐུན་པར་སྐད་ཡིག་ཡར་རྒྱས་གཏོང་།</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="{{ route('login') }}" class="btn-primary">
                        <span>ཐོ་ཞུགས། (Login)</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-secondary">
                            <span>དེབ་སྐྱེལ། (Register)</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>








        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </x-app-layout>


    <footer class="bg-gray-800 text-white py-8 w-full">
        <div class="w-full px-4">
            <div class="flex flex-col md:flex-row justify-between items-center max-w-7xl mx-auto">
                <div>
                    <h3 class="text-xl font-bold tibetan-font">སྨོན་ལམ་མེ་ལོང་།</h3>
                    <p class="mt-2">&copy; {{ date('Y') }} Monlam Melong. All rights reserved.</p>
                </div>

            </div>
        </div>
    </footer>
