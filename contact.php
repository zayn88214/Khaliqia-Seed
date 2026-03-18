<?php
require_once 'includes/site_header.php';
require_once 'includes/db.php';

$form_success = false;
$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $interest = trim($_POST['interest'] ?? $_GET['interest'] ?? '');
    $product_ref = trim($_POST['product'] ?? $_GET['product'] ?? '');

    if ($name && $phone) {
        try {
            $stmt = $pdo->prepare("INSERT INTO inquiries (name, phone, email, message, crop, source, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'contact_form', 'new', NOW(), NOW())");
            $stmt->execute([$name, $phone, $email ?: null, $message ?: null, $interest ?: $product_ref ?: null]);
            $form_success = true;
        } catch (PDOException $e) {
            $form_error = "Something went wrong. Please try again.";
        }
    } else {
        $form_error = "Name and phone number are required.";
    }
}

$whatsapp_number = preg_replace('/\D+/', '', get_content('contact', 'whatsapp', '+923001234567'));
$phone_raw = get_content('contact', 'phone', '+92 300 1234567');
$email_address = get_content('contact', 'email', 'info@khaliqia.com');
?>

<style>
    .contact-input {
        width: 100%;
        background: #fafaf9;
        border: 1.5px solid #e7e5e4;
        border-radius: 0.75rem;
        padding: 0.875rem 1rem 0.875rem 3rem;
        font-size: 0.95rem;
        color: #1c1917;
        transition: all 0.25s ease;
        outline: none;
        font-family: 'Inter', sans-serif;
    }
    .contact-input:focus {
        border-color: #16a34a;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(22, 163, 74, 0.08);
    }
    .contact-input::placeholder { color: #a8a29e; }
    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #a8a29e;
        transition: color 0.25s;
        pointer-events: none;
    }
    .input-group:focus-within .input-icon { color: #16a34a; }
    .textarea-icon { top: 1.1rem; transform: none; }
    .contact-card {
        background: white;
        border: 1px solid rgba(214, 211, 209, 0.5);
        border-radius: 1rem;
        padding: 1.75rem;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .contact-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #16a34a, #15803d);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.35s ease;
    }
    .contact-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(22, 163, 74, 0.12); }
    .contact-card:hover::before { transform: scaleX(1); }
    .card-icon-wrap {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        transition: all 0.35s ease;
    }
    .contact-card:hover .card-icon-wrap { transform: scale(1.1) rotate(-3deg); }
    .map-embed {
        border-radius: 1.25rem;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(214, 211, 209, 0.4);
    }
</style>

<!-- Hero Banner -->
<section class="relative overflow-hidden bg-gradient-to-br from-green-600 via-green-700 to-green-800">
    <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 1440 400" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="200" cy="100" r="300" fill="white" opacity="0.05"/>
            <circle cx="1200" cy="300" r="250" fill="white" opacity="0.05"/>
            <circle cx="700" cy="50" r="180" fill="white" opacity="0.03"/>
        </svg>
    </div>
    <div class="absolute inset-0" style="background-image: radial-gradient(circle at 1px 1px, rgba(255,255,255,0.06) 1px, transparent 0); background-size: 40px 40px;"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="max-w-2xl mx-auto text-center">
            <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-green-100 text-sm font-medium px-4 py-2 rounded-full mb-6 border border-white/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                We'd love to hear from you
            </span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-5 leading-tight"><?php echo h(get_content('contact', 'title', 'Get in Touch')); ?></h1>
            <p class="text-lg text-green-100/80 leading-relaxed max-w-lg mx-auto"><?php echo h(get_content('contact', 'subtitle', 'Have questions about our seeds? Our agricultural experts are ready to help you grow better.')); ?></p>
        </div>
    </div>
    <!-- Wave divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full"><path d="M0 60V20C240 0 480 40 720 30C960 20 1200 0 1440 20V60H0Z" fill="#fafaf9"/></svg>
    </div>
</section>

<!-- Contact Info Cards -->
<section class="relative -mt-6 z-10 pb-8">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 scroll-reveal">
            <!-- Phone -->
            <a href="tel:<?php echo preg_replace('/\D+/', '', $phone_raw); ?>" class="contact-card group cursor-pointer block">
                <div class="card-icon-wrap bg-green-50 mb-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1"><?php echo h(get_content('contact', 'phone_title', 'Call Us')); ?></p>
                <p class="text-stone-900 font-bold text-base"><?php echo h($phone_raw); ?></p>
                <p class="text-xs text-stone-400 mt-1">Mon–Sat, 9AM – 6PM</p>
            </a>

            <!-- WhatsApp -->
            <a href="https://wa.me/<?php echo $whatsapp_number; ?>" target="_blank" class="contact-card group cursor-pointer block">
                <div class="card-icon-wrap bg-emerald-50 mb-4">
                    <svg class="w-6 h-6 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                </div>
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1"><?php echo h(get_content('contact', 'whatsapp_title', 'WhatsApp')); ?></p>
                <p class="text-stone-900 font-bold text-base"><?php echo h(get_content('contact', 'whatsapp_cta', 'Chat with an expert')); ?></p>
                <p class="text-xs text-stone-400 mt-1">Quick instant replies</p>
            </a>

            <!-- Email -->
            <a href="mailto:<?php echo h($email_address); ?>" class="contact-card group cursor-pointer block">
                <div class="card-icon-wrap bg-amber-50 mb-4">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1">Email Us</p>
                <p class="text-stone-900 font-bold text-base"><?php echo h($email_address); ?></p>
                <p class="text-xs text-stone-400 mt-1">We'll reply within 24 hours</p>
            </a>

            <!-- Location -->
            <div class="contact-card">
                <div class="card-icon-wrap bg-purple-50 mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <p class="text-xs font-semibold text-stone-400 uppercase tracking-wider mb-1"><?php echo h(get_content('contact', 'office_title', 'Our Office')); ?></p>
                <p class="text-stone-900 font-bold text-base leading-snug"><?php echo h(get_content('contact', 'address', '123 Agriculture St, Green City')); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content: Form + Map -->
<section class="py-16 md:py-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-5 gap-10 lg:gap-14 items-start">

            <!-- Form Column -->
            <div class="lg:col-span-3 scroll-reveal">
                <div class="bg-white rounded-2xl shadow-xl shadow-stone-200/50 border border-stone-100 p-8 md:p-10">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-stone-900"><?php echo h(get_content('contact', 'form_heading', 'Send us a message')); ?></h2>
                    </div>
                    <p class="text-stone-400 text-sm mb-8 ml-[52px]">Fill out the form and we'll be in touch shortly.</p>

                    <?php if ($form_success): ?>
                        <div class="text-center py-12">
                            <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <h3 class="text-2xl font-bold text-stone-900 mb-2">Message Sent!</h3>
                            <p class="text-stone-500 mb-8">Thank you for reaching out. Our team will get back to you within 24 hours.</p>
                            <a href="contact.php" class="btn-secondary px-8 py-3 inline-block font-semibold">Send Another Message</a>
                        </div>
                    <?php else: ?>
                        <?php if ($form_error): ?>
                            <div class="flex items-center gap-3 bg-red-50 border border-red-100 text-red-600 p-4 rounded-xl mb-6">
                                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm font-medium"><?php echo h($form_error); ?></span>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="space-y-5">
                            <div class="grid sm:grid-cols-2 gap-5">
                                <div class="input-group relative">
                                    <span class="input-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </span>
                                    <input type="text" name="name" required placeholder="Full Name *" class="contact-input">
                                </div>
                                <div class="input-group relative">
                                    <span class="input-icon">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </span>
                                    <input type="tel" name="phone" required placeholder="Phone Number *" class="contact-input">
                                </div>
                            </div>
                            <div class="input-group relative">
                                <span class="input-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </span>
                                <input type="email" name="email" placeholder="Email Address (optional)" class="contact-input">
                            </div>
                            <div class="input-group relative">
                                <span class="input-icon textarea-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                </span>
                                <textarea name="message" rows="5" placeholder="Tell us about your needs — crop type, quantity, region..." class="contact-input !pt-3" style="padding-left: 3rem;"></textarea>
                            </div>
                            <?php if (!empty($_GET['product']) || !empty($_GET['interest'])): ?>
                                <input type="hidden" name="interest" value="<?php echo h($_GET['product'] ?? $_GET['interest'] ?? ''); ?>">
                            <?php endif; ?>
                            <button type="submit" name="send_message" class="w-full btn-primary font-bold py-4 rounded-xl shadow-lg text-base flex items-center justify-center gap-2 group">
                                Send Message
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                            <p class="text-center text-xs text-stone-400">We respect your privacy. No spam, ever.</p>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div class="lg:col-span-2 space-y-6 scroll-reveal">
                <!-- Quick Actions -->
                <div class="bg-white rounded-2xl shadow-lg shadow-stone-200/40 border border-stone-100 p-7">
                    <h3 class="font-bold text-stone-900 text-lg mb-5">Prefer a quick chat?</h3>
                    <div class="space-y-3">
                        <a href="https://wa.me/<?php echo $whatsapp_number; ?>?text=Hi, I'd like to inquire about your seeds." target="_blank" class="btn-whatsapp w-full py-3.5 rounded-xl flex items-center justify-center gap-2.5 text-sm">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Chat on WhatsApp
                        </a>
                        <a href="tel:<?php echo preg_replace('/\D+/', '', $phone_raw); ?>" class="btn-secondary w-full py-3.5 rounded-xl flex items-center justify-center gap-2.5 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Call Now
                        </a>
                    </div>
                </div>

                <!-- Business Hours -->
                <div class="bg-white rounded-2xl shadow-lg shadow-stone-200/40 border border-stone-100 p-7">
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-stone-900 text-lg">Business Hours</h3>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-center justify-between py-2 border-b border-stone-100">
                            <span class="text-stone-500">Monday – Friday</span>
                            <span class="font-semibold text-stone-800"><?php echo h(get_content('contact', 'hours_weekday', '9:00 AM – 6:00 PM')); ?></span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-stone-100">
                            <span class="text-stone-500">Saturday</span>
                            <span class="font-semibold text-stone-800"><?php echo h(get_content('contact', 'hours_saturday', '9:00 AM – 2:00 PM')); ?></span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <span class="text-stone-500">Sunday</span>
                            <span class="font-semibold text-red-500"><?php echo h(get_content('contact', 'hours_sunday', 'Closed')); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Why Choose Us mini -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border border-green-100 p-7">
                    <h3 class="font-bold text-stone-900 text-lg mb-4">Why Farmers Trust Us</h3>
                    <div class="space-y-3.5">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 mt-0.5 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm text-stone-600"><span class="font-semibold text-stone-800">Lab-Tested Seeds</span> — Every batch verified for purity & germination</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 mt-0.5 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm text-stone-600"><span class="font-semibold text-stone-800">Expert Guidance</span> — Free crop advisory from agronomists</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 mt-0.5 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                                <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <p class="text-sm text-stone-600"><span class="font-semibold text-stone-800">Fast Delivery</span> — Nationwide doorstep shipping</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
require_once 'includes/site_footer.php';
?>
