CREATE DATABASE IF NOT EXISTS personal_portfolio
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE personal_portfolio;

CREATE TABLE IF NOT EXISTS profile (
  id TINYINT UNSIGNED PRIMARY KEY,
  name_en VARCHAR(120) NOT NULL,
  headline_en VARCHAR(255) NOT NULL,
  headline_ar VARCHAR(255) NOT NULL,
  intro_en TEXT NOT NULL,
  intro_ar TEXT NOT NULL,
  about_en TEXT NOT NULL,
  about_ar TEXT NOT NULL,
  location_en VARCHAR(120) NOT NULL,
  location_ar VARCHAR(120) NOT NULL,
  focus_en VARCHAR(180) NOT NULL,
  focus_ar VARCHAR(180) NOT NULL,
  email VARCHAR(190) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS skills (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name_en VARCHAR(100) NOT NULL UNIQUE,
  name_ar VARCHAR(100) NOT NULL,
  category_en VARCHAR(100) NOT NULL,
  category_ar VARCHAR(100) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS projects (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title_en VARCHAR(160) NOT NULL UNIQUE,
  title_ar VARCHAR(160) NOT NULL,
  description_en TEXT NOT NULL,
  description_ar TEXT NOT NULL,
  stack VARCHAR(255) NOT NULL,
  status_en VARCHAR(80) NOT NULL,
  status_ar VARCHAR(80) NOT NULL,
  project_url VARCHAR(500) NULL,
  sort_order INT NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT IGNORE INTO profile (
  id, name_en, headline_en, headline_ar, intro_en, intro_ar,
  about_en, about_ar, location_en, location_ar, focus_en, focus_ar, email
) VALUES (
  1,
  'RC',
  'Building useful things, one project at a time.',
  'أبني أشياء مفيدة، مشروعًا بعد مشروع.',
  'I’m a software engineering student who enjoys turning ideas into practical projects and learning how things work.',
  'أنا طالب هندسة برمجيات، أستمتع بتحويل الأفكار إلى مشاريع عملية وبفهم طريقة عمل الأشياء.',
  'I study computer science and engineering at the University of Ha’il. I’m interested in software development, game development, and small electronics projects. I learn best by building and improving real things.',
  'أدرس علوم وهندسة الحاسب في جامعة حائل. أهتم بتطوير البرمجيات والألعاب والمشاريع الإلكترونية الصغيرة. أتعلم بشكل أفضل من خلال بناء أشياء حقيقية وتحسينها.',
  'Ha’il, Saudi Arabia',
  'حائل، المملكة العربية السعودية',
  'Software · Games · Embedded projects',
  'البرمجيات · الألعاب · الأنظمة المضمنة',
  NULL
);

INSERT IGNORE INTO skills (name_en, name_ar, category_en, category_ar, sort_order) VALUES
  ('C#', 'C#', 'Programming', 'برمجة', 1),
  ('PHP & MySQL', 'PHP و MySQL', 'Web development', 'تطوير الويب', 2),
  ('JavaScript', 'JavaScript', 'Web development', 'تطوير الويب', 3),
  ('Unity', 'Unity', 'Game development', 'تطوير الألعاب', 4),
  ('HTML & CSS', 'HTML و CSS', 'Web development', 'تطوير الويب', 5),
  ('ESP32', 'ESP32', 'Embedded systems', 'الأنظمة المضمنة', 6),
  ('Git & GitHub', 'Git و GitHub', 'Development tools', 'أدوات التطوير', 7),
  ('Problem solving', 'حل المشكلات', 'Core skill', 'مهارة أساسية', 8);

INSERT IGNORE INTO projects (
  title_en, title_ar, description_en, description_ar, stack,
  status_en, status_ar, project_url, sort_order
) VALUES
  (
    'ESP32 Smart Clock', 'ساعة ذكية باستخدام ESP32',
    'A small desk clock prototype using a display and microcontroller, built as a hands-on electronics project.',
    'نموذج أولي لساعة مكتبية صغيرة تستخدم شاشة ومتحكمًا دقيقًا، ضمن مشروع عملي للإلكترونيات.',
    'ESP32 · C++ · TFT display', 'Prototype', 'نموذج أولي', NULL, 1
  ),
  (
    '2D Platformer', 'لعبة منصات ثنائية الأبعاد',
    'A learning project exploring player movement, wall climbing, and simple game mechanics in Unity.',
    'مشروع تعلّم لاستكشاف حركة اللاعب وتسلق الجدران وآليات اللعب الأساسية في Unity.',
    'Unity · C#', 'In progress', 'قيد التطوير', NULL, 2
  ),
  (
    'Farm Irrigation Automation', 'أتمتة ري المزرعة',
    'An early design for automating irrigation at a family farm, with a focus on reliable control and simple maintenance.',
    'تصميم مبدئي لأتمتة الري في مزرعة العائلة، مع التركيز على موثوقية التحكم وسهولة الصيانة.',
    'ESP32 · Sensors · Automation', 'Planning', 'في مرحلة التخطيط', NULL, 3
  );
