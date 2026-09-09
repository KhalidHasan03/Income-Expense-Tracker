<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Expense Tracker — Premium Finance</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>

        /* =========================================================
           GLOBAL
        ========================================================= */

        :root {
            --black: #111113;
            --dark: #18181b;
            --muted: #71717a;
            --border: #e7e7e5;
            --surface: #ffffff;
            --background: #f7f7f5;
            --green: #16a34a;
            --red: #ef4444;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: "Inter", sans-serif;
            background: var(--background);
            color: var(--black);
            -webkit-font-smoothing: antialiased;
        }

        a {
            text-decoration: none;
        }


        /* =========================================================
           BACKGROUND
        ========================================================= */

        .page-background {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: -1;
        }

        .background-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
        }

        .orb-one {
            width: 500px;
            height: 500px;
            background: rgba(212, 212, 216, .38);
            top: -220px;
            left: -180px;
        }

        .orb-two {
            width: 450px;
            height: 450px;
            background: rgba(255, 255, 255, .9);
            right: -180px;
            top: 25%;
        }

        .orb-three {
            width: 300px;
            height: 300px;
            background: rgba(228, 228, 231, .25);
            left: 35%;
            bottom: -180px;
        }


        /* =========================================================
           NAVBAR
        ========================================================= */

        .navbar-custom {
            padding-top: 30px;
        }

        .brand-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 43px;
            height: 43px;
            border-radius: 15px;
            background: var(--black);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 19px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .12);
        }

        .brand-logo::after {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #34d399;
            border: 2px solid var(--background);
            position: absolute;
            right: -2px;
            bottom: -2px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .brand-subtitle {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .18em;
            color: #a1a1aa;
            margin-top: 2px;
        }

        .nav-login {
            color: #71717a;
            font-size: 13px;
            font-weight: 500;
            padding: 10px 16px;
            transition: .25s ease;
        }

        .nav-login:hover {
            color: #111113;
        }

        .btn-dark-custom {
            background: #111113;
            color: #fff;
            border: 1px solid #111113;
            border-radius: 999px;
            padding: 11px 20px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(0, 0, 0, .1);
            transition: .25s ease;
        }

        .btn-dark-custom:hover {
            background: #27272a;
            border-color: #27272a;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 14px 30px rgba(0, 0, 0, .15);
        }


        /* =========================================================
           HERO
        ========================================================= */

        .hero-section {
            padding-top: 95px;
            padding-bottom: 100px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            background: rgba(255, 255, 255, .75);
            border: 1px solid #e4e4e2;
            border-radius: 999px;
            padding: 8px 13px;
            font-size: 11px;
            font-weight: 600;
            color: #52525b;
            box-shadow: 0 5px 20px rgba(0, 0, 0, .03);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #22c55e;
            position: relative;
        }

        .status-dot::before {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: rgba(34, 197, 94, .2);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(.7);
                opacity: .8;
            }

            70% {
                transform: scale(1.5);
                opacity: 0;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }


        .hero-title {
            margin-top: 25px;
            font-size: clamp(48px, 6vw, 76px);
            line-height: .94;
            letter-spacing: -.065em;
            font-weight: 800;
            color: #111113;
        }

        .hero-title span {
            color: #a1a1aa;
        }

        .hero-description {
            max-width: 560px;
            margin-top: 27px;
            color: #71717a;
            font-size: 16px;
            line-height: 1.8;
        }

        .hero-description strong {
            color: #3f3f46;
            font-weight: 600;
        }


        /* =========================================================
           CTA
        ========================================================= */

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 30px;
        }

        .btn-primary-premium {
            background: #111113;
            border: 1px solid #111113;
            color: #fff;
            border-radius: 999px;
            padding: 14px 23px;
            font-size: 13px;
            font-weight: 600;
            transition: .3s ease;
            box-shadow: 0 15px 30px rgba(0, 0, 0, .12);
        }

        .btn-primary-premium:hover {
            background: #27272a;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, .16);
        }

        .btn-secondary-premium {
            background: rgba(255, 255, 255, .8);
            border: 1px solid #dededb;
            color: #3f3f46;
            border-radius: 999px;
            padding: 14px 23px;
            font-size: 13px;
            font-weight: 600;
            transition: .3s ease;
        }

        .btn-secondary-premium:hover {
            background: #fff;
            color: #111113;
            border-color: #cfcfcb;
            transform: translateY(-2px);
        }

        .reassurance {
            margin-top: 15px;
            font-size: 11px;
            color: #a1a1aa;
        }

        .reassurance span {
            color: #16a34a;
            font-weight: 800;
            margin-right: 5px;
        }


        /* =========================================================
           STATS
        ========================================================= */

        .hero-stats {
            margin-top: 45px;
            max-width: 560px;
            border-top: 1px solid #dededb;
            border-bottom: 1px solid #dededb;
            padding: 19px 0;
        }

        .stat-item {
            padding: 0 18px;
        }

        .stat-item:first-child {
            padding-left: 0;
        }

        .stat-item + .stat-item {
            border-left: 1px solid #dededb;
        }

        .stat-title {
            font-size: 14px;
            font-weight: 700;
            color: #18181b;
        }

        .stat-text {
            margin-top: 4px;
            font-size: 10px;
            color: #a1a1aa;
        }


        /* =========================================================
           PRODUCT PREVIEW
        ========================================================= */

        .dashboard-wrapper {
            position: relative;
            padding: 20px;
        }

        .dashboard-glow {
            position: absolute;
            inset: 5%;
            background: rgba(161, 161, 170, .25);
            filter: blur(60px);
            border-radius: 50px;
        }

        .dashboard-window {
            position: relative;
            background: rgba(255, 255, 255, .8);
            border: 1px solid rgba(255, 255, 255, .9);
            border-radius: 30px;
            padding: 10px;
            box-shadow:
                0 45px 100px rgba(24, 24, 27, .13),
                0 10px 30px rgba(24, 24, 27, .06);
            backdrop-filter: blur(20px);
        }

        .window-header {
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 14px;
        }

        .window-dots {
            display: flex;
            gap: 5px;
        }

        .window-dot {
            width: 8px;
            height: 8px;
            background: #d4d4d8;
            border-radius: 50%;
        }

        .window-label {
            font-size: 8px;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: #a1a1aa;
            font-weight: 600;
        }

        .dashboard-content {
            background: #f4f4f2;
            border-radius: 22px;
            padding: 17px;
        }

        .dashboard-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .welcome-label {
            font-size: 9px;
            color: #a1a1aa;
        }

        .overview-title {
            margin-top: 3px;
            font-size: 12px;
            font-weight: 700;
        }

        .mini-logo {
            width: 34px;
            height: 34px;
            background: #fff;
            border: 1px solid #e4e4e2;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 11px;
        }


        /* Balance card */

        .balance-card {
            background: #111113;
            color: white;
            border-radius: 19px;
            padding: 19px;
            position: relative;
            overflow: hidden;
        }

        .balance-card::before,
        .balance-card::after {
            content: "";
            position: absolute;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 50%;
        }

        .balance-card::before {
            width: 180px;
            height: 180px;
            right: -85px;
            top: -90px;
        }

        .balance-card::after {
            width: 110px;
            height: 110px;
            right: -30px;
            top: -50px;
        }

        .balance-label {
            position: relative;
            z-index: 1;
            color: #a1a1aa;
            font-size: 9px;
        }

        .balance-value {
            position: relative;
            z-index: 1;
            margin-top: 6px;
            font-size: 29px;
            font-weight: 800;
            letter-spacing: -.05em;
        }

        .growth-badge {
            position: relative;
            z-index: 1;
            display: inline-flex;
            margin-top: 10px;
            background: rgba(255,255,255,.08);
            color: #6ee7b7;
            border-radius: 999px;
            padding: 5px 9px;
            font-size: 8px;
            font-weight: 600;
        }


        /* Income / expense */

        .finance-card {
            background: #fff;
            border: 1px solid #e5e5e3;
            border-radius: 17px;
            padding: 14px;
        }

        .finance-icon {
            width: 29px;
            height: 29px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
        }

        .income-icon {
            background: #ecfdf5;
            color: #16a34a;
        }

        .expense-icon {
            background: #fef2f2;
            color: #ef4444;
        }

        .finance-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .finance-label {
            font-size: 9px;
            color: #a1a1aa;
        }

        .finance-value {
            margin-top: 10px;
            font-size: 17px;
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .finance-description {
            margin-top: 3px;
            font-size: 8px;
            color: #a1a1aa;
        }


        /* Chart */

        .chart-card {
            margin-top: 10px;
            background: #fff;
            border: 1px solid #e5e5e3;
            border-radius: 17px;
            padding: 14px;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chart-small-label {
            font-size: 8px;
            color: #a1a1aa;
        }

        .chart-title {
            margin-top: 3px;
            font-size: 11px;
            font-weight: 700;
        }

        .chart-filter {
            background: #f4f4f2;
            color: #71717a;
            border-radius: 7px;
            padding: 5px 8px;
            font-size: 7px;
        }

        .chart-area {
            height: 90px;
            margin-top: 12px;
            position: relative;
        }

        .chart-grid {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .chart-grid div {
            border-top: 1px solid #f0f0ee;
        }

        .chart-svg {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
        }


        /* Transactions */

        .transactions-card {
            margin-top: 10px;
            background: #fff;
            border: 1px solid #e5e5e3;
            border-radius: 17px;
            padding: 14px;
        }

        .transactions-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transactions-title {
            font-size: 10px;
            font-weight: 700;
        }

        .view-all {
            font-size: 7px;
            color: #a1a1aa;
        }

        .transaction {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
        }

        .transaction-left {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .transaction-icon {
            width: 28px;
            height: 28px;
            background: #f4f4f2;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .transaction-name {
            font-size: 9px;
            font-weight: 600;
        }

        .transaction-date {
            margin-top: 2px;
            font-size: 7px;
            color: #a1a1aa;
        }

        .transaction-amount {
            font-size: 9px;
            font-weight: 700;
        }

        .amount-red {
            color: #ef4444;
        }

        .amount-green {
            color: #16a34a;
        }


        /* =========================================================
           FLOATING CARDS
        ========================================================= */

        .floating-card {
            position: absolute;
            background: rgba(255,255,255,.92);
            border: 1px solid rgba(228,228,226,.9);
            border-radius: 17px;
            padding: 14px;
            box-shadow: 0 20px 45px rgba(0,0,0,.12);
            backdrop-filter: blur(15px);
        }

        .spending-card {
            left: -5px;
            bottom: 70px;
            width: 175px;
        }

        .category-card {
            right: -15px;
            top: 105px;
            width: 165px;
            background: #111113;
            color: #fff;
            border-color: #111113;
        }

        .floating-label {
            font-size: 7px;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: #a1a1aa;
        }

        .floating-value {
            margin-top: 5px;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -.04em;
        }

        .floating-note {
            margin-top: 5px;
            font-size: 8px;
            color: #16a34a;
            font-weight: 600;
        }

        .category-card .floating-value {
            font-size: 13px;
        }

        .category-card .floating-note {
            color: #71717a;
        }


        /* =========================================================
           FEATURES
        ========================================================= */

        .features-section {
            padding-bottom: 90px;
        }

        .section-eyebrow {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .2em;
            color: #a1a1aa;
            font-weight: 700;
        }

        .section-title {
            margin-top: 8px;
            font-size: 28px;
            letter-spacing: -.045em;
            font-weight: 800;
        }

        .section-description {
            max-width: 400px;
            color: #71717a;
            font-size: 12px;
            line-height: 1.7;
        }

        .feature-card {
            height: 100%;
            background: rgba(255,255,255,.8);
            border: 1px solid #e5e5e3;
            border-radius: 25px;
            padding: 25px;
            position: relative;
            overflow: hidden;
            transition: .3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px rgba(0,0,0,.08);
            border-color: #d4d4d1;
        }

        .feature-number {
            width: 39px;
            height: 39px;
            border-radius: 12px;
            background: #111113;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
        }

        .feature-number.light {
            background: #f0f0ee;
            color: #3f3f46;
        }

        .feature-title {
            margin-top: 25px;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .feature-text {
            margin-top: 8px;
            max-width: 300px;
            color: #71717a;
            font-size: 11px;
            line-height: 1.8;
        }

        .feature-decoration {
            position: absolute;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            right: -55px;
            bottom: -55px;
            background: #f4f4f2;
            transition: .4s ease;
        }

        .feature-card:hover .feature-decoration {
            transform: scale(1.3);
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .footer {
            border-top: 1px solid #dededb;
            padding: 25px 0 35px;
            color: #a1a1aa;
            font-size: 10px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991px) {

            .hero-section {
                padding-top: 70px;
            }

            .dashboard-wrapper {
                margin-top: 25px;
            }

            .spending-card {
                left: 0;
            }

            .category-card {
                right: 0;
            }

        }


        @media (max-width: 575px) {

            .navbar-custom {
                padding-top: 20px;
            }

            .hero-section {
                padding-top: 55px;
                padding-bottom: 70px;
            }

            .hero-title {
                font-size: 48px;
            }

            .hero-description {
                font-size: 14px;
            }

            .hero-stats {
                margin-top: 35px;
            }

            .stat-item {
                padding: 0 10px;
            }

            .stat-title {
                font-size: 11px;
            }

            .stat-text {
                font-size: 8px;
            }

            .dashboard-wrapper {
                padding: 5px;
            }

            .floating-card {
                display: none;
            }

            .dashboard-content {
                padding: 12px;
            }

            .balance-value {
                font-size: 25px;
            }

            .section-title {
                font-size: 24px;
            }

        }

    </style>
</head>


<body>


    <!-- Background -->

    <div class="page-background">

        <div class="background-orb orb-one"></div>

        <div class="background-orb orb-two"></div>

        <div class="background-orb orb-three"></div>

    </div>


    <div class="container-xl px-4">


        <!-- =====================================================
             NAVBAR
        ====================================================== -->

        <nav class="navbar-custom d-flex justify-content-between align-items-center">


            <!-- Brand -->

            <div class="brand-wrapper">

                <div class="brand-logo">
                    ₮
                </div>

                <div>

                    <div class="brand-name">
                        Expense Tracker
                    </div>

                    <div class="brand-subtitle">
                        Personal Finance
                    </div>

                </div>

            </div>


            <!-- Navigation -->

            <div class="d-flex align-items-center gap-1">

                @auth

                    <a href="{{ route('dashboard') }}"
                       class="btn-dark-custom">

                        Dashboard
                        <span class="ms-1">→</span>

                    </a>

                @else

                    <a href="{{ route('login') }}"
                       class="nav-login d-none d-sm-block">

                        Log in

                    </a>

                    <a href="{{ route('register') }}"
                       class="btn-dark-custom">

                        Get Started
                        <span class="ms-1">→</span>

                    </a>

                @endauth

            </div>

        </nav>



        <!-- =====================================================
             HERO
        ====================================================== -->

        <section class="hero-section">

            <div class="row align-items-center g-5">


                <!-- LEFT -->

                <div class="col-lg-6">


                    <!-- Badge -->

                    <div class="hero-badge">

                        <span class="status-dot"></span>

                        Your finances, simplified

                    </div>



                    <!-- Heading -->

                    <h1 class="hero-title">

                        Track money.

                        <br>

                        <span>
                            Lose the clutter.
                        </span>

                    </h1>



                    <!-- Description -->

                    <p class="hero-description">

                        Minimal tracker for managing your

                        <strong>
                            income, expenses, custom categories
                        </strong>

                        and monthly summaries — all in one clean,
                        focused experience.

                    </p>



                    <!-- CTA -->

                    <div class="hero-actions">

                        <a href="{{ route('register') }}"
                           class="btn-primary-premium">

                            Create account

                            <span class="ms-1">
                                →
                            </span>

                        </a>


                        <a href="{{ route('login') }}"
                           class="btn-secondary-premium">

                            Sign in

                        </a>

                    </div>



                    <!-- Reassurance -->

                    <div class="reassurance">

                        <span>✓</span>

                        Simple setup · No clutter · Your categories

                    </div>



                    <!-- Stats -->

                    <div class="hero-stats">

                        <div class="row g-0">


                            <div class="col-4 stat-item">

                                <div class="stat-title">
                                    Balance
                                </div>

                                <div class="stat-text">
                                    Auto-calculated
                                </div>

                            </div>


                            <div class="col-4 stat-item">

                                <div class="stat-title">
                                    Categories
                                </div>

                                <div class="stat-text">
                                    You create
                                </div>

                            </div>


                            <div class="col-4 stat-item">

                                <div class="stat-title">
                                    Summary
                                </div>

                                <div class="stat-text">
                                    Monthly
                                </div>

                            </div>


                        </div>

                    </div>

                </div>



                <!-- =================================================
                     RIGHT — PRODUCT PREVIEW
                ================================================== -->

                <div class="col-lg-6">

                    <div class="dashboard-wrapper">


                        <!-- Glow -->

                        <div class="dashboard-glow"></div>


                        <!-- Main Window -->

                        <div class="dashboard-window">


                            <!-- Window Header -->

                            <div class="window-header">

                                <div class="window-dots">

                                    <span class="window-dot"></span>
                                    <span class="window-dot"></span>
                                    <span class="window-dot"></span>

                                </div>

                                <div class="window-label">
                                    Expense Tracker
                                </div>

                                <div style="width: 30px;"></div>

                            </div>



                            <!-- Dashboard -->

                            <div class="dashboard-content">


                                <!-- Header -->

                                <div class="dashboard-heading">

                                    <div>

                                        <div class="welcome-label">
                                            Good morning
                                        </div>

                                        <div class="overview-title">
                                            Your financial overview
                                        </div>

                                    </div>

                                    <div class="mini-logo">
                                        ₮
                                    </div>

                                </div>



                                <!-- Balance -->

                                <div class="balance-card">

                                    <div class="balance-label">
                                        Total balance
                                    </div>

                                    <div class="balance-value">
                                        $8,240.50
                                    </div>

                                    <div class="growth-badge">
                                        ↑ 12.8% this month
                                    </div>

                                </div>



                                <!-- Income / Expense -->

                                <div class="row g-2 mt-1">


                                    <!-- Income -->

                                    <div class="col-6">

                                        <div class="finance-card">

                                            <div class="finance-top">

                                                <div class="finance-icon income-icon">
                                                    ↑
                                                </div>

                                                <div class="finance-label">
                                                    Income
                                                </div>

                                            </div>

                                            <div class="finance-value">
                                                $12,450
                                            </div>

                                            <div class="finance-description">
                                                All sources
                                            </div>

                                        </div>

                                    </div>



                                    <!-- Expense -->

                                    <div class="col-6">

                                        <div class="finance-card">

                                            <div class="finance-top">

                                                <div class="finance-icon expense-icon">
                                                    ↓
                                                </div>

                                                <div class="finance-label">
                                                    Expense
                                                </div>

                                            </div>

                                            <div class="finance-value">
                                                $4,209
                                            </div>

                                            <div class="finance-description">
                                                By category
                                            </div>

                                        </div>

                                    </div>

                                </div>



                                <!-- Chart -->

                                <div class="chart-card">

                                    <div class="chart-header">

                                        <div>

                                            <div class="chart-small-label">
                                                Monthly overview
                                            </div>

                                            <div class="chart-title">
                                                Spending activity
                                            </div>

                                        </div>

                                        <div class="chart-filter">
                                            This month
                                        </div>

                                    </div>


                                    <div class="chart-area">


                                        <div class="chart-grid">

                                            <div></div>
                                            <div></div>
                                            <div></div>
                                            <div></div>

                                        </div>


                                        <svg
                                            viewBox="0 0 500 120"
                                            class="chart-svg"
                                            preserveAspectRatio="none">

                                            <defs>

                                                <linearGradient
                                                    id="areaGradient"
                                                    x1="0"
                                                    y1="0"
                                                    x2="0"
                                                    y2="1">

                                                    <stop
                                                        offset="0%"
                                                        stop-color="#18181b"
                                                        stop-opacity=".15" />

                                                    <stop
                                                        offset="100%"
                                                        stop-color="#18181b"
                                                        stop-opacity="0" />

                                                </linearGradient>

                                            </defs>


                                            <path
                                                d="M0 90 C45 82 60 100 95 67 C130 35 150 75 190 57 C225 39 250 68 285 45 C320 24 345 55 380 37 C420 16 450 42 500 15 L500 120 L0 120 Z"
                                                fill="url(#areaGradient)" />


                                            <path
                                                d="M0 90 C45 82 60 100 95 67 C130 35 150 75 190 57 C225 39 250 68 285 45 C320 24 345 55 380 37 C420 16 450 42 500 15"
                                                fill="none"
                                                stroke="#18181b"
                                                stroke-width="3"
                                                stroke-linecap="round" />

                                        </svg>

                                    </div>

                                </div>



                                <!-- Transactions -->

                                <div class="transactions-card">

                                    <div class="transactions-heading">

                                        <div class="transactions-title">
                                            Recent transactions
                                        </div>

                                        <div class="view-all">
                                            View all →
                                        </div>

                                    </div>



                                    <!-- Transaction -->

                                    <div class="transaction">

                                        <div class="transaction-left">

                                            <div class="transaction-icon">
                                                🛒
                                            </div>

                                            <div>

                                                <div class="transaction-name">
                                                    Groceries
                                                </div>

                                                <div class="transaction-date">
                                                    Today
                                                </div>

                                            </div>

                                        </div>

                                        <div class="transaction-amount amount-red">
                                            -$84.20
                                        </div>

                                    </div>



                                    <!-- Transaction -->

                                    <div class="transaction">

                                        <div class="transaction-left">

                                            <div class="transaction-icon">
                                                💼
                                            </div>

                                            <div>

                                                <div class="transaction-name">
                                                    Salary
                                                </div>

                                                <div class="transaction-date">
                                                    Yesterday
                                                </div>

                                            </div>

                                        </div>

                                        <div class="transaction-amount amount-green">
                                            +$2,400
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- Floating Spending -->

                        <div class="floating-card spending-card">

                            <div class="d-flex justify-content-between align-items-start">

                                <div>

                                    <div class="floating-label">
                                        Monthly spending
                                    </div>

                                    <div class="floating-value">
                                        $1,840
                                    </div>

                                </div>

                                <div style="font-size:14px;">
                                    ↗
                                </div>

                            </div>

                            <div class="floating-note">
                                8.4% less than last month
                            </div>

                        </div>



                        <!-- Floating Category -->

                        <div class="floating-card category-card">

                            <div class="floating-label">
                                Top category
                            </div>

                            <div class="floating-value">
                                Food & Dining
                            </div>

                            <div class="floating-note">
                                28% of expenses
                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </section>



        <!-- =====================================================
             FEATURES
        ====================================================== -->

        <section class="features-section">


            <div class="row align-items-end mb-4 g-4">


                <div class="col-lg-7">

                    <div class="section-eyebrow">
                        Everything you need
                    </div>

                    <h2 class="section-title">
                        Financial clarity,
                        <br class="d-none d-md-block">
                        without complexity.
                    </h2>

                </div>


                <div class="col-lg-5">

                    <p class="section-description mb-0">

                        Focus on understanding your money instead of
                        fighting with complicated financial tools.

                    </p>

                </div>


            </div>



            <div class="row g-3">


                <!-- Feature 1 -->

                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-number">
                            01
                        </div>

                        <div class="feature-title">
                            Track everything
                        </div>

                        <div class="feature-text">

                            Keep your income and expenses organized
                            in one simple, easy-to-understand place.

                        </div>

                        <div class="feature-decoration"></div>

                    </div>

                </div>



                <!-- Feature 2 -->

                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-number light">
                            02
                        </div>

                        <div class="feature-title">
                            Understand spending
                        </div>

                        <div class="feature-text">

                            See where your money goes with clear
                            categories, charts and monthly summaries.

                        </div>

                        <div class="feature-decoration"></div>

                    </div>

                </div>



                <!-- Feature 3 -->

                <div class="col-md-4">

                    <div class="feature-card">

                        <div class="feature-number light">
                            03
                        </div>

                        <div class="feature-title">
                            Stay in control
                        </div>

                        <div class="feature-text">

                            Create your own categories and build
                            better financial habits without unnecessary complexity.

                        </div>

                        <div class="feature-decoration"></div>

                    </div>

                </div>


            </div>

        </section>



        <!-- =====================================================
             FOOTER
        ====================================================== -->

        <footer class="footer">

            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2">

                <div>
                    © {{ date('Y') }} Expense Tracker
                </div>

                <div>
                    Simple. Private. Organized.
                </div>

            </div>

        </footer>


    </div>


    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>
