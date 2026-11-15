<?php
declare(strict_types=1);
session_start();
require __DIR__ . '/db.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$notice = '';
$noticeType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = (string) ($_POST['csrf_token'] ?? '');
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));

    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $notice = 'Please refresh the page and try again.';
        $noticeType = 'error';
    } elseif ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
        $notice = 'Please enter your name, a valid email, and a message.';
        $noticeType = 'error';
    } else {
        $saveMessage = $pdo->prepare('INSERT INTO messages (name, email, message) VALUES (?, ?, ?)');
        $saveMessage->execute([$name, $email, $message]);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $notice = 'Thanks! Your message was sent.';
        $noticeType = 'success';
    }
}

$profile = $pdo->query('SELECT * FROM profile WHERE id = 1')->fetch();
$skills = $pdo->query('SELECT name_en, name_ar, category_en, category_ar FROM skills ORDER BY sort_order, id')->fetchAll();
$projects = $pdo->query('SELECT title_en, title_ar, description_en, description_ar, stack, status_en, status_ar, project_url FROM projects ORDER BY sort_order, id')->fetchAll();

function e(?string $value): string {
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

if (!$profile) {
    http_response_code(500);
    exit('No profile data found. Import database.sql first.');
}
?>
<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Personal portfolio of <?= e($profile['name_en']) ?>">
    <title><?= e($profile['name_en']) ?> — Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Tajawal:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/app.js" defer></script>
</head>
<body>
<header class="site-header">
    <nav class="nav wrap" aria-label="Main navigation">
        <a class="brand" href="#home"><span class="brand-mark">R</span><span><?= e($profile['name_en']) ?></span></a>
        <button class="menu-toggle" id="menu-toggle" aria-label="Open navigation" aria-expanded="false">☰</button>
        <div class="nav-links" id="nav-links">
            <a href="#about"><span class="en">About</span><span class="ar">نبذة</span></a>
            <a href="#skills"><span class="en">Skills</span><span class="ar">المهارات</span></a>
            <a href="#projects"><span class="en">Projects</span><span class="ar">المشاريع</span></a>
            <a href="#contact"><span class="en">Contact</span><span class="ar">تواصل</span></a>
        </div>
        <div class="nav-actions">
            <button class="icon-button" id="language-toggle" type="button" aria-label="Switch language">عربي</button>
            <button class="icon-button theme-button" id="theme-toggle" type="button" aria-label="Toggle dark mode">◐</button>
        </div>
    </nav>
</header>

<main>
    <section class="hero wrap" id="home">
        <div class="hero-copy">
            <h1><span class="en"><?= e($profile['headline_en']) ?></span><span class="ar"><?= e($profile['headline_ar']) ?></span></h1>
            <p class="hero-text"><span class="en"><?= e($profile['intro_en']) ?></span><span class="ar"><?= e($profile['intro_ar']) ?></span></p>
            <div class="hero-actions">
                <a class="button primary" href="#projects"><span class="en">Explore my work</span><span class="ar">استكشف أعمالي</span><span aria-hidden="true">↘</span></a>
                <a class="text-link" href="#contact"><span class="en">Get in touch</span><span class="ar">تواصل معي</span></a>
            </div>
        </div>
        <div class="hero-card" aria-label="Introduction card">
            <div class="avatar">RC<span class="avatar-dot"></span></div>
            <p class="card-label"><span class="en">CURRENTLY LEARNING</span><span class="ar">أتعلم حاليًا</span></p>
            <p class="learning"><span class="en">Software engineering<br>Unity · C# · Web development</span><span class="ar">هندسة البرمجيات<br>Unity · C# · تطوير الويب</span></p>
            <div class="availability"><span></span><span class="en">Open to learning opportunities</span><span class="ar">شغوف بالتطوير والتحسين</span></div>
        </div>
        <a class="scroll-hint" href="#about"><span class="en">SCROLL TO EXPLORE</span><span class="ar">مرر للاستكشاف</span><span>↓</span></a>
    </section>

    <section class="section wrap" id="about">
        <div class="section-heading"><p class="eyebrow">01 / <span class="en">ABOUT</span><span class="ar">نبذة</span></p><h2><span class="en">A little about me</span><span class="ar">نبذة عني</span></h2></div>
        <div class="about-grid">
            <p class="about-lead"><span class="en"><?= e($profile['about_en']) ?></span><span class="ar"><?= e($profile['about_ar']) ?></span></p>
            <div class="about-details">
                <div><span class="detail-label"><span class="en">BASED IN</span><span class="ar">الموقع</span></span><strong><span class="en"><?= e($profile['location_en']) ?></span><span class="ar"><?= e($profile['location_ar']) ?></span></strong></div>
                <div><span class="detail-label"><span class="en">FOCUS</span><span class="ar">الإهتمامات</span></span><strong><span class="en"><?= e($profile['focus_en']) ?></span><span class="ar"><?= e($profile['focus_ar']) ?></span></strong></div>
                <div><span class="detail-label"><span class="en">EMAIL</span><span class="ar">البريد</span></span><strong><?php if (!empty($profile['email'])): ?><?= e($profile['email']) ?><?php else: ?><span class="en">Add your email in MySQL</span><span class="ar">أضف بريدك في قاعدة البيانات</span><?php endif; ?></strong></div>
            </div>
        </div>
    </section>

    <section class="section tinted" id="skills">
        <div class="wrap">
            <div class="section-heading"><p class="eyebrow">02 / <span class="en">SKILLS</span><span class="ar">المهارات</span></p><h2><span class="en">Tools I work with</span><span class="ar">أدوات أستخدمها</span></h2></div>
            <div class="skills-grid">
                <?php foreach ($skills as $skill): ?>
                    <article class="skill-card"><span class="skill-symbol">✳</span><div><h3><span class="en"><?= e($skill['name_en']) ?></span><span class="ar"><?= e($skill['name_ar']) ?></span></h3><p><span class="en"><?= e($skill['category_en']) ?></span><span class="ar"><?= e($skill['category_ar']) ?></span></p></div></article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section wrap" id="projects">
        <div class="section-heading"><p class="eyebrow">03 / <span class="en">SELECTED WORK</span><span class="ar">مشاريع قيد الإنشاء.</span></p><h2><span class="en">Things I’m building</span><span class="ar">مشاريع اشتغل عليها.</span></h2></div>
        <div class="projects-grid">
            <?php foreach ($projects as $index => $project): ?>
                <article class="project-card">
                    <div class="project-top"><span class="project-number">0<?= $index + 1 ?></span><span class="project-status"><span class="en"><?= e($project['status_en']) ?></span><span class="ar"><?= e($project['status_ar']) ?></span></span></div>
                    <h3><span class="en"><?= e($project['title_en']) ?></span><span class="ar"><?= e($project['title_ar']) ?></span></h3>
                    <p class="project-description"><span class="en"><?= e($project['description_en']) ?></span><span class="ar"><?= e($project['description_ar']) ?></span></p>
                    <p class="tech-list"><?= e($project['stack']) ?></p>
                    <?php if (!empty($project['project_url'])): ?><a class="project-link" href="<?= e($project['project_url']) ?>" target="_blank" rel="noopener noreferrer"><span class="en">View project ↗</span><span class="ar">عرض المشروع ↗</span></a><?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section contact-section" id="contact">
        <div class="wrap contact-grid">
            <div><p class="eyebrow">04 / <span class="en">CONTACT</span><span class="ar">تواصل</span></p><h2><span class="en">Have a good idea?<br>Let’s talk.</span><span class="ar">عندك فكرة ابداعية ؟<br>تواصل معي</span></h2><p class="contact-intro"><span class="en">Send a message and I’ll get back to you.</span><span class="ar">ارسل رسالة وبرد في اقرب وقت.</span></p></div>
            <form class="contact-form" method="post" action="#contact">
                <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                <label><span class="en">Name</span><span class="ar">الاسم</span><input name="name" type="text" maxlength="120" required autocomplete="name" placeholder="Your name / اسمك"></label>
                <label><span class="en">Email</span><span class="ar">البريد الإلكتروني</span><input name="email" type="email" maxlength="190" required autocomplete="email" placeholder="you@example.com"></label>
                <label><span class="en">Message</span><span class="ar">الرسالة</span><textarea name="message" rows="4" maxlength="5000" required placeholder="Write your message / اكتب رسالتك"></textarea></label>
                <?php if ($notice !== ''): ?><p class="form-notice <?= e($noticeType) ?>" role="status"><?php if ($noticeType === 'success'): ?><span class="en">Thanks! Your message was sent.</span><span class="ar">شكرًا! تم ارسال رسالتك.</span><?php else: ?><span class="en">Please check the fields and try again. If this page stayed open for a long time, refresh it first.</span><span class="ar">حدثت مشكلة , تأكد من صيغة الأيميل او الأسم.</span><?php endif; ?></p><?php endif; ?>
                <button class="button primary" type="submit"><span class="en">Send message</span><span class="ar">إرسال الرسالة</span><span aria-hidden="true">↗</span></button>
            </form>
        </div>
    </section>
</main>

<footer class="footer wrap"><a class="brand" href="#home"><span class="brand-mark">R</span><span><?= e($profile['name_en']) ?></span></a><p> <span class="en"></span><span class="ar"></span></p><a class="back-top" href="#home"><span class="en">Back to top ↑</span><span class="ar">للأعلى ↑</span></a></footer>
</body>
</html>
