<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Deep space base ── */
        .galaxy-bg {
            background:
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(30, 10, 60, 0.9) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 20%, rgba(10, 30, 70, 0.8) 0%, transparent 55%),
                radial-gradient(ellipse 100% 100% at 50% 50%, #060714 0%, #03040d 100%);
        }

        /* ── Star layers at different depths ── */
        .stars-small {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1px 1px at 10% 15%, rgba(255, 255, 255, 0.9) 0%, transparent 100%),
                radial-gradient(1px 1px at 25% 42%, rgba(255, 255, 255, 0.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 38% 8%, rgba(255, 255, 255, 0.85) 0%, transparent 100%),
                radial-gradient(1px 1px at 52% 71%, rgba(255, 255, 255, 0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 67% 33%, rgba(255, 255, 255, 0.9) 0%, transparent 100%),
                radial-gradient(1px 1px at 80% 58%, rgba(255, 255, 255, 0.75) 0%, transparent 100%),
                radial-gradient(1px 1px at 91% 12%, rgba(255, 255, 255, 0.8) 0%, transparent 100%),
                radial-gradient(1px 1px at 5% 85%, rgba(255, 255, 255, 0.65) 0%, transparent 100%),
                radial-gradient(1px 1px at 44% 90%, rgba(255, 255, 255, 0.7) 0%, transparent 100%),
                radial-gradient(1px 1px at 73% 78%, rgba(255, 255, 255, 0.85) 0%, transparent 100%),
                radial-gradient(1px 1px at 17% 63%, rgba(255, 255, 255, 0.6) 0%, transparent 100%),
                radial-gradient(1px 1px at 59% 22%, rgba(255, 255, 255, 0.9) 0%, transparent 100%),
                radial-gradient(1px 1px at 33% 55%, rgba(255, 255, 255, 0.5) 0%, transparent 100%),
                radial-gradient(1px 1px at 88% 40%, rgba(255, 255, 255, 0.8) 0%, transparent 100%),
                radial-gradient(1px 1px at 47% 48%, rgba(255, 255, 255, 0.7) 0%, transparent 100%);
            background-size: 600px 600px;
            animation: drift-slow 90s linear infinite;
            pointer-events: none;
        }

        .stars-medium {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(1.5px 1.5px at 8% 30%, rgba(200, 220, 255, 0.95) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 22% 68%, rgba(220, 200, 255, 0.9) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 41% 19%, rgba(255, 255, 255, 1.0) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 63% 52%, rgba(200, 230, 255, 0.85) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 76% 14%, rgba(255, 240, 220, 0.9) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 85% 75%, rgba(220, 200, 255, 0.95) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 30% 88%, rgba(255, 255, 255, 0.8) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 55% 37%, rgba(200, 220, 255, 0.9) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 94% 46%, rgba(255, 240, 200, 0.85) 0%, transparent 100%),
                radial-gradient(1.5px 1.5px at 14% 95%, rgba(220, 255, 220, 0.8) 0%, transparent 100%);
            background-size: 800px 800px;
            animation: drift-slow 120s linear infinite;
            pointer-events: none;
        }

        .stars-large {
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(2px 2px at 15% 25%, rgba(255, 255, 255, 1.0) 0%, rgba(255, 255, 255, 0.1) 3px, transparent 5px),
                radial-gradient(2px 2px at 48% 60%, rgba(180, 200, 255, 1.0) 0%, rgba(180, 200, 255, 0.1) 3px, transparent 5px),
                radial-gradient(2px 2px at 72% 88%, rgba(255, 220, 180, 1.0) 0%, rgba(255, 220, 180, 0.1) 3px, transparent 5px),
                radial-gradient(2px 2px at 90% 30%, rgba(255, 255, 255, 1.0) 0%, rgba(255, 255, 255, 0.1) 3px, transparent 5px),
                radial-gradient(2px 2px at 32% 75%, rgba(200, 180, 255, 1.0) 0%, rgba(200, 180, 255, 0.1) 3px, transparent 5px),
                radial-gradient(2px 2px at 60% 10%, rgba(255, 255, 220, 1.0) 0%, rgba(255, 255, 220, 0.1) 3px, transparent 5px);
            background-size: 1000px 1000px;
            animation: drift-slow 150s linear infinite, twinkle-bright 4s ease-in-out infinite alternate;
            pointer-events: none;
        }

        /* ── Nebula clouds ── */
        .nebula-1 {
            position: absolute;
            width: 70vw;
            height: 50vh;
            top: -10%;
            left: -10%;
            background: radial-gradient(ellipse at center,
                    rgba(80, 30, 140, 0.25) 0%,
                    rgba(40, 10, 90, 0.12) 40%,
                    transparent 70%);
            filter: blur(40px);
            animation: nebula-drift 30s ease-in-out infinite alternate;
            pointer-events: none;
        }

        .nebula-2 {
            position: absolute;
            width: 60vw;
            height: 60vh;
            bottom: -15%;
            right: -10%;
            background: radial-gradient(ellipse at center,
                    rgba(20, 60, 160, 0.22) 0%,
                    rgba(10, 30, 100, 0.10) 45%,
                    transparent 70%);
            filter: blur(50px);
            animation: nebula-drift 40s ease-in-out infinite alternate-reverse;
            pointer-events: none;
        }

        .nebula-3 {
            position: absolute;
            width: 40vw;
            height: 40vh;
            top: 30%;
            left: 30%;
            background: radial-gradient(ellipse at center,
                    rgba(100, 20, 80, 0.15) 0%,
                    rgba(60, 10, 50, 0.07) 50%,
                    transparent 70%);
            filter: blur(60px);
            animation: nebula-drift 50s ease-in-out infinite alternate;
            pointer-events: none;
        }

        /* ── Shooting stars ── */
        .shooting-stars {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .shooting-star {
            position: absolute;
            width: 120px;
            height: 1px;
            background: linear-gradient(90deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.9) 50%, rgba(255, 255, 255, 0) 100%);
            border-radius: 50%;
            transform: rotate(-35deg);
            opacity: 0;
        }

        .shooting-star:nth-child(1) {
            top: 12%;
            left: 60%;
            animation: shoot 8s 1.5s infinite;
        }

        .shooting-star:nth-child(2) {
            top: 28%;
            left: 80%;
            animation: shoot 8s 4.5s infinite;
        }

        .shooting-star:nth-child(3) {
            top: 8%;
            left: 30%;
            animation: shoot 8s 7.0s infinite;
        }

        .shooting-star:nth-child(4) {
            top: 45%;
            left: 70%;
            animation: shoot 8s 2.2s infinite;
        }

        .shooting-star:nth-child(5) {
            top: 18%;
            left: 50%;
            animation: shoot 8s 9.5s infinite;
        }

        /* ── Milky way band ── */
        .milky-way {
            position: absolute;
            inset: 0;
            background: linear-gradient(115deg,
                    transparent 0%,
                    transparent 20%,
                    rgba(180, 160, 255, 0.04) 30%,
                    rgba(200, 180, 255, 0.07) 38%,
                    rgba(180, 160, 255, 0.05) 46%,
                    transparent 56%,
                    transparent 100%);
            pointer-events: none;
        }

        /* ── Animations ── */
        @keyframes drift-slow {
            from {
                transform: translateY(0);
            }

            to {
                transform: translateY(-600px);
            }
        }

        @keyframes twinkle-bright {
            0% {
                opacity: 0.7;
            }

            50% {
                opacity: 1.0;
            }

            100% {
                opacity: 0.8;
            }
        }

        @keyframes nebula-drift {
            0% {
                transform: translate(0, 0) scale(1);
            }

            100% {
                transform: translate(2%, 3%) scale(1.05);
            }
        }

        @keyframes shoot {
            0% {
                transform: translateX(0) translateY(0) rotate(-35deg);
                opacity: 0;
            }

            5% {
                opacity: 1;
            }

            30% {
                transform: translateX(-400px) translateY(200px) rotate(-35deg);
                opacity: 0;
            }

            100% {
                transform: translateX(-400px) translateY(200px) rotate(-35deg);
                opacity: 0;
            }
        }

        /* ── Respect reduced motion ── */
        @media (prefers-reduced-motion: reduce) {

            .stars-small,
            .stars-medium,
            .stars-large {
                animation: none;
            }

            .nebula-1,
            .nebula-2,
            .nebula-3 {
                animation: none;
            }

            .shooting-star {
                animation: none;
            }
        }
    </style>
</head>

<body class="galaxy-bg font-sans antialiased">
    <div class="min-h-screen flex flex-col justify-center items-center relative overflow-hidden">

        {{-- Nebula clouds (behind stars) --}}
        <div class="nebula-1"></div>
        <div class="nebula-2"></div>
        <div class="nebula-3"></div>

        {{-- Milky Way band --}}
        <div class="milky-way"></div>

        {{-- Star layers (small → large / faint → bright) --}}
        <div class="stars-small"></div>
        <div class="stars-medium"></div>
        <div class="stars-large"></div>

        {{-- Shooting stars --}}
        <div class="shooting-stars">
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
            <div class="shooting-star"></div>
        </div>

        {{-- Card --}}
        <div
            class="w-full sm:max-w-md px-8 py-10
                    bg-slate-900/40 backdrop-blur-2xl
                    border border-white/10
                    shadow-[0_8px_48px_rgba(80,40,160,0.35),0_0_0_1px_rgba(255,255,255,0.05)]
                    rounded-3xl z-10">
            {{ $slot }}
        </div>

    </div>
</body>

</html>
