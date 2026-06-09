# SUÇEK Design System — MASTER
> Style: **Editorial Luxury Minimal** | Stack: Laravel Blade + Tailwind v4 + Alpine.js + Tabler Icons

---

## Brand Identity
- **Marka:** SUÇEK — Mimarlık · İnşaat · Koleksiyon · Mağaza
- **Ton:** Prestijli, güvenilir, editorial, Türk lüks markası
- **Hedef kitle:** Üst-orta ve üst segment, 30-60 yaş, mülk sahibi / koleksiyoner

---

## Color Tokens (CSS Variables in app.css @theme)

| Token | Değer | Kullanım |
|-------|-------|----------|
| `--color-brand-ink` | `#0F0F0F` | Ana metin, başlıklar |
| `--color-brand-dark` | `#141414` | Butonlar, koyu arka planlar |
| `--color-brand-card` | `rgba(25,25,25,0.90)` | Karanlık kartlar, referanslar |
| `--color-brand-muted` | `#A8A8A8` | İkincil metin, etiketler |
| `--color-brand-red` | `#CC2200` | Promosyon banner, badge, alert |
| `--color-brand-gold` | `#B8962E` | Lüks vurgu, özel etiket |
| `--color-surface-page` | `#EBEBEB` | Body arka planı |
| `--color-surface-white` | `#FFFFFF` | Kart ve içerik yüzeyi |
| `--color-surface-off` | `#F5F5F5` | Form input arka planı |
| `--color-surface-subtle` | `#F0F0F0` | Hover state, ayırıcılar |
| `--color-text-primary` | `#0F0F0F` | Birincil metin |
| `--color-text-secondary` | `#5A5A5A` | İkincil metin |
| `--color-text-muted` | `#A8A8A8` | Placeholder, etiket |
| `--color-text-inverse` | `#FFFFFF` | Koyu bg üzerindeki metin |
| `--color-border-soft` | `rgba(0,0,0,0.07)` | Bölüm ayırıcı |
| `--color-border-mid` | `rgba(0,0,0,0.12)` | Kart border |
| `--color-border-strong` | `rgba(0,0,0,0.20)` | Input, buton border |
| `--color-border-inv` | `rgba(255,255,255,0.10)` | Koyu yüzey üzerinde border |

---

## Typography

### Font Stack
| Role | Font | Weight | Kullanım |
|------|------|--------|----------|
| Display | Cormorant Garamond | 300/400/600 | Hero başlıklar, logo, büyük başlıklar |
| Serif | Playfair Display | 400/700 (italic) | Alıntılar, testimonials, editorial metin |
| Sans | DM Sans | 300/400/500 | UI, body, nav, butonlar |

### Type Scale
| Class | Size | Kullanım |
|-------|------|----------|
| `text-[9px]` | 9px | Etiket, nav item, badge |
| `text-[11px]` | 11px | Küçük UI metni |
| `text-[13px]` | 13px | Body küçük |
| `text-sm` | 14px | Body |
| `text-base` | 16px | Body büyük |
| `text-[18px]` | 18px | Alt başlık |
| `text-2xl` | 24px | Kart başlığı |
| `text-[28px]` | 28px | Bölüm başlığı |
| `text-[34px]` | 34px | Logo |
| `text-4xl` | 36px | Sayfa hero başlığı |
| `text-5xl` | 48px | Ana hero |
| `text-7xl` | 72px | 404 sayısı |

---

## Spacing System (4pt grid)
`gap-1(4px)` · `gap-1.5(6px)` · `gap-2(8px)` · `gap-3(12px)` · `gap-4(16px)` · `gap-5(20px)` · `gap-6(24px)` · `gap-8(32px)` · `gap-10(40px)` · `gap-12(48px)`

Section padding: `py-8 px-[9px]` (32px top/bottom, 9px sides)

---

## Border Radius
| Token | Değer |
|-------|-------|
| `--radius-xs` | 4px |
| `--radius-sm` | 6px |
| `--radius-md` | 8px — Butonlar, nav items, küçük kartlar |
| `--radius-lg` | 12px — Kartlar, paneller |
| `--radius-xl` | 16px — Modal, hero cells |

---

## Shadow Scale
| Token | Değer | Kullanım |
|-------|-------|----------|
| `--shadow-card` | `0 2px 6px rgba(0,0,0,0.45)` | Nav items, hero sub, kartlar |
| `--shadow-modal` | `0 24px 64px rgba(0,0,0,0.22)` | Modal, dropdown |
| `--shadow-hover` | `0 4px 16px rgba(0,0,0,0.35)` | Hover state |

---

## Components

### Navigation (`resources/views/components/nav.blade.php`)
- Logo solda (Cormorant Garamond, 34px, tracking-[6px])
- Nav items ortada-sağ: 9px uppercase, letter-spacing, dropdown hover
- Sağ: sepet ikonu + "Giriş Yap" outline btn + "Üye Ol" dark btn
- **Mobil:** hamburger menü (Alpine.js x-show)
- `position: sticky top-0 z-40`

### Footer (`resources/views/components/footer.blade.php`)
- Koyu (#141414) arka plan
- Logo + kısa açıklama, link grupları, sosyal ikonlar
- Alt bar: telif hakkı

### Banner (`resources/views/components/banner.blade.php`)
- Beyaz arka plan, kırmızı metin, marquee animasyonu
- `margin: 8px 9px 0`

### Hero Grid (`resources/views/components/hero-grid.blade.php`)
- 2×2 grid, her hücre = büyük görsel kart + alt nav bar
- Hover: overlay karartma + metin beyazlaşma (transition 400ms)

### Kart - Dark
- `card-dark` class, Playfair serif italic metin
- Referanslar, iletişim bilgisi için

---

## Page Layouts

### `layouts/app.blade.php`
- Google Fonts import (Cormorant Garamond, Playfair Display, DM Sans)
- Tabler Icons CDN
- `@vite` css + js
- Nav component
- `@yield('content')`
- Footer component
- Alpine.js ile sepet state yönetimi

---

## Interaction Patterns (Alpine.js)

| Component | Alpine Pattern |
|-----------|---------------|
| Dropdown nav | `x-data="{ open: false }" @mouseenter="open=true" @mouseleave="open=false"` |
| Mobile menü | `x-data="{ menuOpen: false }"` |
| Koleksiyon modal | `x-data="{ modal: null }"` |
| Mağaza slider | `x-data="{ current: 0 }"` |
| Sepet | `$store.cart` (Alpine.store) |
| Hesaplama formu | `x-data="hesapla()"` |
| Filtre | `x-data="{ aktif: 'tumu' }"` |

---

## Anti-Patterns (Kaçınılacaklar)
- Emoji icon kullanma (Tabler Icons kullan)
- `rgba()` renkleri doğrudan component içine yazma (token kullan)
- Hover-only etkileşim (mobil için `@click` de ekle)
- 44px altında touch target
- `width`/`height` animasyonu (`transform`/`opacity` kullan)
- Placeholder-only form label
- Fixed px container width (`max-w-[1280px]` kullan)

---

## Page Inventory

| Sayfa | Route | View | Controller Method |
|-------|-------|------|-------------------|
| Ana Sayfa | `/` | `welcome` | `HomeController@index` |
| Mimarlık | `/mimarlik` | `mimarlik.index` | `MimarlikController@index` |
| İnşaat | `/insaat` | `insaat.index` | `InsaatController@index` |
| Koleksiyon | `/koleksiyon` | `koleksiyon.index` | `KoleksiyonController@index` |
| Mağaza | `/magaza` | `magaza.index` | `MagazaController@index` |
| Ürün | `/magaza/{slug}` | `magaza.urun` | `MagazaController@urun` |
| Hesaplama | `/insaat/hesaplama` | `insaat.hesaplama` | `InsaatController@hesaplama` |
| Belgeler | `/mimarlik/belgeler` | `mimarlik.belgeler` | `MimarlikController@belgeler` |
| 404 | — | `errors.404` | — |
