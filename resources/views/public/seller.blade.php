<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $seoTitle = trim((string) ($seller->seo_title ?? '')) ?: (__('ui.common.products').' '.$seller->store_name);
        $seoDescription = trim((string) ($seller->seo_description ?? '')) ?: ('Best products from '.$seller->store_name.'.');
        $seoImage = trim((string) ($seller->seo_logo_url ?? ''));
        $canonicalUrl = route('public.seller.page', ['seller_slug' => $seller->slug]);
        $faviconVersion = (string) ($seller->updated_at?->timestamp ?? time());
    @endphp
    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if($seoImage !== '')
    <meta property="og:image" content="{{ $seoImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $seoImage }}">
    @else
    <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if($seoImage !== '')
    <link rel="icon" type="image/png" href="{{ $seoImage }}?v={{ urlencode($faviconVersion) }}">
    <link rel="shortcut icon" href="{{ $seoImage }}?v={{ urlencode($faviconVersion) }}">
    <link rel="apple-touch-icon" href="{{ $seoImage }}?v={{ urlencode($faviconVersion) }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700;800&family=Inter:wght@500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f7f9fd;
            --surface: #ffffff;
            --surface-soft: #f3f7fc;
            --text: #11253d;
            --muted: #49627f;
            --primary: #21759b;
            --primary-strong: #005c7e;
            --secondary: #1e3a5f;
            --outline: #d4dfec;
            --radius-xl: 20px;
            --radius-lg: 14px;
            --radius-md: 10px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Hanken Grotesk", "Segoe UI", sans-serif;
            color: var(--text);
            background:
                radial-gradient(1200px 420px at 16% -12%, rgba(33, 117, 155, 0.08) 0%, transparent 60%),
                radial-gradient(900px 400px at 90% -10%, rgba(74, 120, 166, 0.11) 0%, transparent 65%),
                var(--bg);
        }

        .topbar-wrap {
            padding: 0 18px 0;
        }

        .topbar {
            max-width: 1300px;
            margin: 0 auto;
            background: var(--surface);
            border-radius: 0 0 22px 22px;
            border: 1px solid var(--outline);
            border-top: 0;
            box-shadow: 0 12px 24px rgba(17, 40, 68, 0.08);
            padding: 18px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0;
        }

        .brand-label {
            font-size: 34px;
            font-weight: 800;
            letter-spacing: .2px;
            color: var(--secondary);
            line-height: 1;
        }

        .wrap {
            max-width: 1300px;
            margin: 0 auto;
            padding: 26px;
            position: relative;
        }

        .section-title {
            margin: 0 0 16px;
            font-size: 46px;
            line-height: 1.06;
            color: #b35e26;
        }

        .section-subtitle {
            margin: 0 0 20px;
            font-size: 18px;
            color: var(--muted);
        }

        .catalog-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
            padding: 14px;
            background: var(--surface);
            border: 1px solid var(--outline);
            border-radius: var(--radius-lg);
        }

        .catalog-toolbar form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            width: 100%;
        }

        .catalog-field {
            height: 42px;
            border-radius: var(--radius-md);
            border: 1px solid #c9d7e6;
            background: #fff;
            color: var(--text);
            padding: 0 12px;
            min-width: 180px;
            font: inherit;
        }

        .catalog-search {
            flex: 1;
            min-width: 220px;
        }

        .catalog-btn {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #fff;
            height: 42px;
            border-radius: var(--radius-md);
            padding: 0 14px;
            font-family: Inter, "Segoe UI", sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .catalog-btn:hover {
            background: var(--primary-strong);
            border-color: var(--primary-strong);
        }

        .catalog-btn.catalog-btn-ghost {
            background: #fff;
            border-color: #c4d1e0;
            color: var(--secondary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .catalog-btn.catalog-btn-ghost:hover {
            background: #f6faff;
            border-color: #8ca5bf;
        }

        .catalog-meta {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .catalog-footer-row {
            margin-top: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .request-cta-panel {
            margin-top: 22px;
            padding: 18px;
            border: 1px solid #cddced;
            border-radius: var(--radius-lg);
            background:
                linear-gradient(180deg, #ffffff 0%, #f5faff 100%);
            box-shadow: 0 12px 26px rgba(20, 52, 84, 0.08);
        }

        .request-cta-head {
            margin-bottom: 14px;
        }

        .request-cta-kicker {
            margin: 0 0 6px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #2b638f;
        }

        .request-cta-title {
            margin: 0;
            font-size: 26px;
            line-height: 1.2;
            color: var(--secondary);
        }

        .request-cta-description {
            margin: 8px 0 0;
            font-size: 14px;
            line-height: 1.45;
            color: var(--muted);
            max-width: 760px;
        }

        .request-cta-form {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            align-items: end;
        }

        .request-cta-field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .request-cta-label {
            font-size: 13px;
            font-weight: 700;
            color: #1f4468;
        }

        .request-cta-input {
            height: 46px;
            border-radius: var(--radius-md);
            border: 1px solid #c7d7e8;
            background: #fff;
            color: var(--text);
            padding: 0 12px;
            font: inherit;
        }

        .request-cta-input:focus {
            outline: none;
            border-color: #78a5cd;
            box-shadow: 0 0 0 3px rgba(33, 117, 155, 0.15);
        }

        .request-cta-submit {
            height: 46px;
            white-space: nowrap;
            padding: 0 16px;
        }

        .request-cta-error,
        .request-cta-success {
            margin: 0 0 12px;
            padding: 10px 12px;
            border-radius: var(--radius-md);
            font-size: 13px;
            line-height: 1.4;
        }

        .request-cta-error {
            border: 1px solid #e5bcbc;
            background: #fff1f1;
            color: #9d2323;
        }

        .request-cta-success {
            border: 1px solid #b9dbcf;
            background: #ecfff5;
            color: #1f6a46;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(245px, 1fr));
            gap: 16px;
        }

        .card {
            display: flex;
            flex-direction: column;
            border: 1px solid var(--outline);
            border-radius: var(--radius-lg);
            background: var(--surface);
            overflow: hidden;
            cursor: pointer;
            text-align: left;
            padding: 0;
            transition: transform .14s ease, box-shadow .14s ease, border-color .14s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px rgba(17, 40, 68, 0.12);
            border-color: #afc7de;
        }

        .card.selected {
            border-color: var(--primary);
            box-shadow: 0 10px 18px rgba(33, 117, 155, 0.24);
        }

        .thumb-wrap {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #fff;
            border-bottom: 1px solid #e7edf5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .thumb-fallback {
            color: #99abc1;
            font-weight: 700;
            font-size: 14px;
        }

        .card-body {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-height: 120px;
        }

        .product-name {
            margin: 0;
            font-size: 18px;
            line-height: 1.22;
            color: var(--text);
        }

        .meta {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.3;
        }

        .empty {
            border: 1px dashed #bfcddb;
            border-radius: var(--radius-lg);
            background: var(--surface);
            padding: 26px;
            text-align: center;
            color: var(--muted);
        }

        .catalog-pagination {
            margin-top: 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            flex-wrap: wrap;
        }

        .catalog-pagination a,
        .catalog-pagination span {
            min-width: 36px;
            height: 36px;
            padding: 0 10px;
            border-radius: var(--radius-md);
            border: 1px solid #c9d7e6;
            background: #fff;
            color: var(--secondary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-family: Inter, "Segoe UI", sans-serif;
            font-size: 12px;
            font-weight: 600;
        }

        .catalog-pagination .active {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 32, 54, 0.55);
            backdrop-filter: blur(3px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            z-index: 1500;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: opacity .24s ease, visibility .24s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        .modal {
            width: min(1024px, calc(100vw - 34px));
            max-height: calc(100vh - 24px);
            overflow: auto;
            background: linear-gradient(180deg, #ffffff, #f7fbff);
            border-radius: var(--radius-xl);
            border: 1px solid #d2dfee;
            box-shadow: 0 22px 60px rgba(17, 40, 68, 0.26);
            padding: 16px;
            transform: translateY(14px) scale(.98);
            opacity: 0;
            transition: transform .24s ease, opacity .24s ease;
        }

        .modal-overlay.active .modal {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .modal-title {
            margin: 0;
            font-size: 42px;
            color: var(--secondary);
            line-height: 1.05;
        }

        .modal-title-row {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .dummy-mode-badge {
            display: inline-flex;
            align-items: center;
            height: 24px;
            padding: 0 10px;
            border-radius: 999px;
            border: 1px solid #b7cce1;
            background: #edf6ff;
            color: #1f4e78;
            font-family: Inter, "Segoe UI", sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .03em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .modal-close {
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #edf4ff;
            color: var(--secondary);
            font-size: 22px;
            cursor: pointer;
        }

        .modal-info-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 12px;
            margin-bottom: 8px;
            align-items: center;
            padding: 10px 12px;
            border: 1px solid #d7e3ef;
            border-radius: 10px;
            background: #f7fbff;
        }

        .selected-product {
            padding: 2px 0;
            font-size: 14px;
            margin: 0;
            color: var(--text);
        }

        .quota-box {
            border: none;
            background: transparent;
            color: #295884;
            padding: 2px 0;
            font-size: 13px;
            margin: 0;
            text-align: right;
            white-space: nowrap;
            justify-self: end;
            width: auto;
            max-width: none;
        }
        .tryon-action-row {
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .marketplace-links {
            display: none;
            align-items: center;
            gap: 8px;
            flex: 0 0 auto;
        }
        .marketplace-links.has-link {
            display: inline-flex;
        }
        .marketplace-link-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 44px;
            padding: 0 14px;
            border-radius: 14px;
            border: 1px solid #b8cde2;
            background: #eef6ff;
            color: #1f4f78;
            text-decoration: none;
            font-family: Inter, "Segoe UI", sans-serif;
            font-size: 13px;
            font-weight: 700;
            white-space: nowrap;
        }
        .marketplace-link-btn:hover {
            background: #e3f1ff;
            border-color: #8cb1d3;
        }
        .marketplace-link-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .field {
            border-radius: var(--radius-md);
            background: var(--surface);
            border: 1px solid #d7e3ef;
            padding: 10px;
        }

        .label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #2f4d6c;
        }

        .label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .label-row .label {
            margin-bottom: 0;
        }

        .dummy-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #2f4d6c;
            user-select: none;
            cursor: pointer;
        }

        .dummy-toggle input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
            pointer-events: none;
        }

        .dummy-toggle-switch {
            position: relative;
            width: 40px;
            height: 22px;
            border-radius: 999px;
            background: #c9d7e6;
            border: 1px solid #b9cadb;
            transition: background .2s ease, border-color .2s ease;
            flex: 0 0 auto;
        }

        .dummy-toggle-switch::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(43, 23, 9, 0.24);
            transition: transform .2s ease;
        }

        .dummy-toggle input:checked + .dummy-toggle-switch {
            background: #8eb9da;
            border-color: #5f98c4;
        }

        .dummy-toggle input:checked + .dummy-toggle-switch::after {
            transform: translateX(18px);
        }

        .dummy-toggle-text {
            font-weight: 700;
            line-height: 1;
        }

        .model-footer-row {
            margin-top: 4px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: nowrap;
        }

        .model-footer-row .label {
            margin: 0;
            margin-bottom: 0;
            font-size: 14px;
            white-space: nowrap;
        }

        .model-footer-row .dummy-toggle {
            font-size: 11px;
            gap: 6px;
            white-space: nowrap;
            flex: 0 0 auto;
        }

        .model-footer-row .dummy-toggle-text {
            font-size: 11px;
            font-weight: 600;
        }

        .input-file {
            display: none;
        }

        .upload-trigger {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(180deg, #2a8ab4, #21759b);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .01em;
            cursor: pointer;
        }

        .status-note {
            margin-top: 10px;
            min-height: 20px;
            font-size: 13px;
            line-height: 1.4;
            color: var(--muted);
        }

        .status-error {
            color: #ba1a1a;
        }

        .status-success {
            color: #0e7a61;
        }

        .feedback-wrap {
            margin-top: 10px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #d6e3ef;
            background: #f8fcff;
            display: none;
        }

        .feedback-wrap-under-product {
            margin-top: 0;
            flex: 0 0 auto;
            position: relative;
            z-index: 1;
        }

        .feedback-title {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #2d4f6b;
        }

        .feedback-hint {
            margin: 4px 0 8px;
            font-size: 12px;
            color: #597792;
        }

        .feedback-stars {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
        }

        .feedback-star {
            width: 30px;
            height: 30px;
            border: 1px solid #c8daea;
            border-radius: 8px;
            background: #fff;
            color: #9cb5c9;
            font-size: 18px;
            line-height: 1;
            cursor: pointer;
            transition: .15s ease;
        }

        .feedback-star.is-active {
            border-color: #f0ba57;
            background: #fff6de;
            color: #f0ba57;
        }

        .feedback-comment {
            width: 100%;
            min-height: 72px;
            border-radius: 10px;
            border: 1px solid #c8daea;
            background: #fff;
            padding: 8px 10px;
            font: inherit;
            font-size: 13px;
            resize: vertical;
        }

        .feedback-actions {
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
        }

        .feedback-status {
            min-height: 18px;
            font-size: 12px;
            color: #597792;
            flex: 1 1 auto;
        }

        .feedback-submit-btn {
            border: none;
            border-radius: 8px;
            background: #2a8ab4;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            padding: 8px 12px;
            cursor: pointer;
            white-space: nowrap;
        }

        .feedback-submit-btn:disabled {
            cursor: not-allowed;
            opacity: .65;
        }

        .modal-preview-grid {
            display: none;
        }

        .tryon-main-grid {
            display: grid;
            grid-template-columns: 450px minmax(0, 1fr);
            gap: 16px;
            margin-bottom: 10px;
            width: 100%;
            align-items: start;
            height: auto;
        }

        .tryon-left-rail { min-width: 0; }

        .tryon-left-rail > .field {
            display: flex;
            flex-direction: column;
            gap: 14px;
            overflow: visible;
        }

        .tryon-main-grid > .field {
            min-width: 0;
        }

        .tryon-generated-field {
            width: 100%;
        }

        .tryon-panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .ready-badge {
            display: inline-flex;
            align-items: center;
            height: 20px;
            padding: 0 8px;
            border-radius: 999px;
            border: 1px solid #c9dff1;
            background: #eef8ff;
            color: #3374a1;
            font-family: Inter, "Segoe UI", sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: capitalize;
        }

        .preview-box {
            position: relative;
            width: 100%;
            height: auto;
            border-radius: var(--radius-md);
            border: none;
            background: #fff;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: none;
        }

        .preview-box-product {
            width: 100%;
            aspect-ratio: 1 / 1;
            height: auto;
            max-height: none;
        }

        .preview-box-product img,
        .selected-product-thumb.preview-box-product img {
            object-fit: cover;
            object-position: center center;
        }

        .preview-box-result {
            width: 100%;
            height: clamp(460px, 64vh, 700px);
            aspect-ratio: auto;
        }

        @media (max-width: 1300px) {
            .modal {
                width: min(1080px, calc(100vw - 26px));
            }

            .tryon-main-grid {
                grid-template-columns: 348px minmax(0, 1fr);
            }

            .preview-box-product {
                aspect-ratio: 1 / 1;
                height: auto;
                max-height: none;
            }

            .preview-box-result {
                height: clamp(420px, 58vh, 620px);
            }
        }

        .compare-frame {
            position: relative;
            width: 100%;
            height: 100%;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f6fb;
            border: 1px solid #d7e3ef;
        }

        .compare-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
            display: block;
            user-select: none;
            -webkit-user-drag: none;
        }

        .compare-before {
            position: absolute;
            inset: 0;
            background: #f7fbff;
            display: none;
        }

        .compare-after-wrap {
            position: absolute;
            inset: 0;
            background: #fff;
            display: none;
            clip-path: inset(0 0 0 50%);
        }

        .compare-after-wrap img { width: 100%; }

        .compare-divider {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 2px;
            margin-left: -1px;
            background: #ffffff;
            box-shadow: 0 0 0 1px rgba(33, 117, 155, 0.18);
            pointer-events: none;
            display: none;
        }

        .compare-divider::after {
            content: '<>';
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 30px;
            height: 30px;
            border-radius: 999px;
            background: #ffffff;
            border: 1px solid #c9dff1;
            color: #2f628c;
            font-size: 11px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            letter-spacing: -0.02em;
        }

        .compare-slider {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            appearance: none;
            background: transparent;
            cursor: ew-resize;
            z-index: 4;
            display: none;
        }

        .compare-slider::-webkit-slider-thumb {
            appearance: none;
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .compare-slider::-moz-range-thumb {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            background: transparent;
            border: none;
            box-shadow: none;
        }

        .compare-labels {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            display: flex;
            justify-content: space-between;
            gap: 8px;
            z-index: 5;
            pointer-events: none;
            display: none;
        }

        .compare-chip {
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 0 10px;
            border-radius: 999px;
            border: 1px solid #c9dff1;
            background: rgba(255, 255, 255, 0.92);
            color: #29577d;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .02em;
            text-transform: uppercase;
        }

        .compare-model-stage {
            position: absolute;
            inset: 0;
            z-index: 3;
            display: grid;
            place-items: center;
            padding: 0;
            background: #f7fbff;
        }

        .compare-model-media {
            width: 100%;
            height: 100%;
            border-radius: 0;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .compare-model-media.is-adjust-ready {
            cursor: grab;
            touch-action: none;
            user-select: none;
        }

        .compare-model-media.is-adjust-ready.is-dragging {
            cursor: grabbing;
        }

        .compare-model-media img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: none;
        }

        #customerPreview {
            object-fit: cover;
            object-position: center center;
        }

        .compare-model-placeholder {
            text-align: center;
            color: #55708c;
        }

        .compare-model-placeholder p {
            margin: 0 0 8px;
        }

        .compare-frame.is-comparison .compare-before,
        .compare-frame.is-comparison .compare-after-wrap,
        .compare-frame.is-comparison .compare-divider,
        .compare-frame.is-comparison .compare-slider {
            display: block;
        }

        .compare-frame.is-comparison .compare-model-stage {
            display: none;
        }

        .compare-frame.is-comparison .compare-labels {
            display: flex;
        }

        .tryon-header-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .compare-frame.is-history-full .compare-before,
        .compare-frame.is-history-full .compare-divider,
        .compare-frame.is-history-full .compare-slider,
        .compare-frame.is-history-full .compare-labels,
        .compare-frame.is-history-full .compare-model-stage {
            display: none !important;
        }

        .compare-frame.is-history-full .compare-after-wrap {
            display: block !important;
            inset: 0;
            clip-path: inset(0 0 0 0);
            border-left: none;
            width: 100%;
        }

        .selected-product-thumb {
            width: 100%;
            border-radius: 10px;
            background: #f7fbff;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .selected-product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .selected-product-thumb-fallback {
            font-family: Inter, "Segoe UI", sans-serif;
            font-size: 10px;
            font-weight: 600;
            color: #55708c;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .preview-placeholder {
            color: #55708c;
            text-align: center;
            padding: 14px;
            font-size: 14px;
            line-height: 1.35;
        }

        .compare-frame > .preview-placeholder {
            position: absolute;
            inset: 0;
            z-index: 6;
            display: none;
            align-items: center;
            justify-content: center;
            background: rgba(247, 251, 255, 0.82);
            padding: 16px;
            pointer-events: none;
        }

        .preview-remove {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            border: none;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.9);
            color: #2f628c;
            font-size: 20px;
            cursor: pointer;
            display: none;
        }

        .model-adjust-panel {
            margin-top: 10px;
            padding: 10px;
            border: 1px solid #d7e3ef;
            border-radius: 10px;
            background: #f8fbff;
        }

        .model-adjust-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 8px;
        }

        .model-adjust-title {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
            color: #2f4d6c;
        }

        .model-adjust-reset {
            border: 1px solid #bfd2e4;
            background: #ffffff;
            color: #2f628c;
            height: 28px;
            border-radius: 8px;
            padding: 0 10px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
        }

        .model-adjust-controls {
            display: grid;
            gap: 10px;
        }

        .model-adjust-row {
            display: grid;
            gap: 6px;
        }

        .model-adjust-row-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 12px;
            color: #335978;
            font-weight: 600;
        }

        .model-adjust-row input[type="range"] {
            width: 100%;
        }

        .loading-dots {
            display: inline-flex;
            align-items: flex-end;
            gap: 8px;
            height: 26px;
        }

        .loading-dots span {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #21759b;
            animation: dot-bounce 0.9s ease-in-out infinite;
        }

        .loading-dots span:nth-child(2) {
            animation-delay: 0.15s;
        }

        .loading-dots span:nth-child(3) {
            animation-delay: 0.3s;
        }

        @keyframes dot-bounce {
            0%,
            80%,
            100% {
                transform: translateY(0);
                opacity: 0.5;
            }

            40% {
                transform: translateY(-10px);
                opacity: 1;
            }
        }

        .generate-btn {
            width: 100%;
            height: 44px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(180deg, #2a8ab4, #21759b);
            color: #fff;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            flex: 1 1 auto;
        }

        .generate-btn:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .floating-history-btn {
            position: fixed;
            right: max(18px, calc((100vw - 1300px) / 2 + 26px));
            bottom: 86px;
            width: 56px;
            height: 56px;
            border: none;
            border-radius: 999px;
            background: linear-gradient(180deg, #2a8ab4, #21759b);
            color: #fff;
            box-shadow: 0 14px 28px rgba(17, 58, 88, 0.28);
            cursor: pointer;
            z-index: 1400;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 900;
        }

        .floating-history-panel {
            position: fixed;
            right: max(18px, calc((100vw - 1300px) / 2 + 26px));
            bottom: 152px;
            width: min(360px, calc(100vw - 36px));
            max-height: 62vh;
            overflow: auto;
            background: #ffffff;
            border: 1px solid #d7e3ef;
            border-radius: 12px;
            box-shadow: 0 18px 36px rgba(15, 44, 73, 0.24);
            z-index: 1400;
            padding: 12px;
            display: none;
        }

        .floating-history-panel.active {
            display: block;
        }

        .floating-history-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 10px;
        }

        .floating-history-title {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #143050;
        }

        .floating-history-close {
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #edf4ff;
            color: #2b5378;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
        }

        .history-preview-overlay {
            position: fixed;
            inset: 0;
            background: rgba(8, 18, 28, 0.84);
            backdrop-filter: blur(3px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1500;
            padding: 24px;
        }

        .history-preview-overlay.active {
            display: flex;
        }

        .history-preview-modal {
            position: relative;
            width: auto;
            max-width: min(94vw, 1100px);
            max-height: 92vh;
            background: transparent;
            border: none;
            box-shadow: none;
            display: grid;
            place-items: center;
        }

        .history-preview-close {
            position: fixed;
            top: 24px;
            right: 24px;
            width: 34px;
            height: 34px;
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 999px;
            background: rgba(16, 36, 54, 0.55);
            color: #f5fbff;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            z-index: 1502;
        }

        .history-preview-body {
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 0;
            background: transparent;
        }

        .history-preview-image {
            max-width: min(94vw, 1100px);
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            background: transparent;
            border: none;
            box-shadow: 0 20px 42px rgba(0, 0, 0, 0.4);
        }

        .history-preview-buy {
            position: fixed;
            left: 50%;
            bottom: 26px;
            transform: translateX(-50%);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 44px;
            padding: 0 18px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.42);
            background: rgba(20, 56, 84, 0.82);
            color: #ffffff;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.3);
            z-index: 1502;
        }

        .history-preview-buy svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .history-wrap {
            margin: 4px 0 10px;
            border: 1px solid #d7e3ef;
            border-radius: var(--radius-md);
            background: var(--surface);
            padding: 8px;
        }

        .tryon-generated-field .history-wrap {
            margin: 8px 0 0;
        }

        .tryon-generated-field .history-list {
            grid-template-columns: repeat(auto-fill, minmax(72px, 1fr));
            gap: 6px;
        }

        .tryon-generated-field .history-item {
            min-height: 96px;
        }

        .history-title {
            margin: 0 0 8px;
            font-size: 13px;
            font-weight: 800;
            color: #2f4d6c;
        }

        .history-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(84px, 1fr));
            gap: 8px;
        }

        .history-item {
            border: 1px solid #d7e3ef;
            border-radius: 10px;
            background: #fff;
            overflow: hidden;
            padding: 0;
            cursor: pointer;
            min-height: 112px;
        }

        .history-item img {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            display: block;
        }

        .history-item-time {
            display: block;
            font-size: 10px;
            color: #49627f;
            padding: 5px 6px 6px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .history-empty {
            margin: 0;
            font-size: 13px;
            color: #49627f;
            padding: 2px 0 0;
        }

        @media (max-width: 900px) {
            .topbar {
                flex-direction: column;
                align-items: stretch;
            }

            .brand {
                gap: 0;
            }

            .brand-label {
                font-size: 26px;
            }

            .section-title {
                font-size: 36px;
            }

            .modal-preview-grid {
                grid-template-columns: 1fr;
            }

            .modal-info-grid {
                grid-template-columns: 1fr;
            }

            .tryon-main-grid {
                grid-template-columns: 1fr;
                height: auto;
            }

            .preview-box-product {
                aspect-ratio: 1 / 1;
                height: auto;
                max-height: none;
            }

            .preview-box-result {
                height: 420px;
            }

            .catalog-footer-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .catalog-pagination {
                width: 100%;
                justify-content: flex-start;
            }

            .request-cta-title {
                font-size: 22px;
            }

            .request-cta-form {
                grid-template-columns: 1fr;
            }

            .request-cta-submit {
                width: 100%;
            }

            .floating-history-btn {
                right: 16px;
                bottom: 96px;
            }

            .floating-history-panel {
                right: 16px;
                bottom: 164px;
            }

            .model-adjust-controls {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="topbar-wrap">
        <header class="topbar">
            <div class="brand">
                <span class="brand-label">{{ __('ui.common.products') }}</span>
            </div>
        </header>
    </div>

    <main class="wrap">
        <div class="catalog-toolbar">
            <form method="GET" action="{{ route('public.seller.page', ['seller_slug' => $seller->slug]) }}">
                <input
                    class="catalog-field catalog-search"
                    type="search"
                    name="q"
                    value="{{ $activeFilters['q'] }}"
                    placeholder="{{ __('ui.store.search_placeholder') }}">
                <select class="catalog-field" name="category">
                    <option value="">{{ __('ui.store.all_categories') }}</option>
                    @foreach ($categories as $categoryOption)
                        <option value="{{ $categoryOption }}" {{ $activeFilters['category'] === $categoryOption ? 'selected' : '' }}>
                            {{ $categoryOption }}
                        </option>
                    @endforeach
                </select>
                <select class="catalog-field" name="sort">
                    <option value="latest" {{ $activeFilters['sort'] === 'latest' ? 'selected' : '' }}>{{ __('ui.store.sort_latest') }}</option>
                    <option value="name_asc" {{ $activeFilters['sort'] === 'name_asc' ? 'selected' : '' }}>{{ __('ui.store.sort_name_asc') }}</option>
                    <option value="name_desc" {{ $activeFilters['sort'] === 'name_desc' ? 'selected' : '' }}>{{ __('ui.store.sort_name_desc') }}</option>
                </select>
                <button class="catalog-btn" type="submit">{{ __('ui.store.apply') }}</button>
                <a class="catalog-btn catalog-btn-ghost" href="{{ route('public.seller.page', ['seller_slug' => $seller->slug]) }}">{{ __('ui.store.reset') }}</a>
            </form>
        </div>

@if($products->isEmpty())
        <div class="empty">{{ __('ui.products_page.no_products') }}</div>
        @else
        <section class="grid" id="productGrid">
            @foreach ($products as $product)
            @php
            $image = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
            $isSelected = !empty($selectedProduct) && $selectedProduct->id === $product->id;
            @endphp
            <button
                type="button"
                class="card {{ $isSelected ? 'selected' : '' }}"
                data-product-id="{{ $product->id }}"
                data-product-name="{{ $product->name }}"
                data-product-slug="{{ $product->slug }}"
                data-product-sku="{{ $product->sku }}"
                data-product-category="{{ $product->category }}"
                data-product-image="{{ $image?->image_url ?? '' }}"
                data-product-link-url="{{ $product->product_link_url ?? '' }}"
                onclick="openTryOnModal(this)">
                <div class="thumb-wrap">
                    @if($image)
                    <img src="{{ $image->image_url }}" alt="{{ $product->name }}" class="thumb">
                    @else
                    <div class="thumb-fallback">No Image</div>
                    @endif
                </div>
                <div class="card-body">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="meta">Category: {{ $product->category ?: '-' }}</p>
                </div>
            </button>
            @endforeach
        </section>
        <div class="catalog-footer-row">
            <p class="catalog-meta">
                {{ __('ui.store.showing_summary', ['count' => $products->count(), 'total' => $products->total()]) }}
            </p>
            @if ($products->lastPage() > 1)
            @php
                $currentPage = $products->currentPage();
                $lastPage = $products->lastPage();
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp
            <nav class="catalog-pagination" aria-label="Pagination produk">
                @if ($products->onFirstPage())
                    <span>&laquo;</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" rel="prev">&laquo;</a>
                @endif

                @for ($page = $startPage; $page <= $endPage; $page++)
                    @if ($page === $currentPage)
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $products->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" rel="next">&raquo;</a>
                @else
                    <span>&raquo;</span>
                @endif
            </nav>
            @endif
        </div>
        @endif

        <section id="product-request-form" class="request-cta-panel">
            <div class="request-cta-head">
                <p class="request-cta-kicker">{{ __('ui.store.product_request_kicker') }}</p>
                <h2 class="request-cta-title">{{ __('ui.store.product_request_title') }}</h2>
                <p class="request-cta-description">{{ __('ui.store.product_request_description') }}</p>
            </div>

            @if(session('store_product_request_success'))
                <div class="request-cta-success" role="status">
                    {{ session('store_product_request_success') }}
                </div>
            @endif

            <form class="request-cta-form" method="POST" action="{{ route('public.seller.product-requests.store', ['seller_slug' => $seller->slug]) }}">
                @csrf
                <div class="request-cta-field">
                    <label class="request-cta-label" for="shopeeProductUrl">{{ __('ui.store.product_request_field_label') }}</label>
                    <input
                        id="shopeeProductUrl"
                        class="request-cta-input"
                        type="url"
                        name="shopee_product_url"
                        value="{{ old('shopee_product_url') }}"
                        placeholder="{{ __('ui.store.product_request_placeholder') }}"
                        maxlength="2048"
                        autocomplete="url"
                        required>
                    @error('shopee_product_url')
                        <div class="request-cta-error" role="alert">{{ $message }}</div>
                    @enderror
                </div>
                <button class="catalog-btn request-cta-submit" type="submit">{{ __('ui.store.product_request_submit') }}</button>
            </form>
        </section>

        @if(!empty($selectedProduct))
            @php
                $selectedProductImage = $selectedProduct->images->firstWhere('is_primary', true) ?? $selectedProduct->images->first();
            @endphp
            <button
                id="autoOpenTryOnTrigger"
                type="button"
                style="display:none"
                aria-hidden="true"
                data-product-id="{{ $selectedProduct->id }}"
                data-product-name="{{ $selectedProduct->name }}"
                data-product-slug="{{ $selectedProduct->slug }}"
                data-product-sku="{{ $selectedProduct->sku }}"
                data-product-category="{{ $selectedProduct->category }}"
                data-product-image="{{ $selectedProductImage?->image_url ?? '' }}"
                data-product-link-url="{{ $selectedProduct->product_link_url ?? '' }}">
            </button>
        @endif
        <button id="floatingHistoryBtn" type="button" class="floating-history-btn" aria-label="{{ __('ui.store.history_button_aria') }}">
            &#128340;
        </button>
        <div id="floatingHistoryPanel" class="floating-history-panel" aria-hidden="true">
            <div class="floating-history-header">
                <p class="floating-history-title">{{ __('ui.store.history_title') }}</p>
                <button id="floatingHistoryClose" type="button" class="floating-history-close" aria-label="{{ __('ui.common.close') }}">&times;</button>
            </div>
            <div id="floatingHistoryList" class="history-list"></div>
            <p id="floatingHistoryEmpty" class="history-empty">{{ __('ui.store.no_history') }}</p>
        </div>
        <div id="historyPreviewOverlay" class="history-preview-overlay" aria-hidden="true">
            <div class="history-preview-modal" role="dialog" aria-modal="true" aria-labelledby="historyPreviewTitle">
                <p id="historyPreviewTitle" style="position:absolute;left:-9999px;">Hasil Generated</p>
                <button id="historyPreviewClose" type="button" class="history-preview-close" aria-label="{{ __('ui.common.close') }}">&times;</button>
                <a id="historyPreviewBuy" class="history-preview-buy" href="#" target="_blank" rel="noopener noreferrer" style="display:none;">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="9" cy="20" r="1.5"></circle>
                        <circle cx="18" cy="20" r="1.5"></circle>
                        <path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 8H7"></path>
                    </svg>
                    <span>Beli Produk</span>
                </a>
                <div class="history-preview-body">
                    <img id="historyPreviewImage" class="history-preview-image" alt="History generated result">
                </div>
            </div>
        </div>
    </main>

    <div class="modal-overlay" id="tryOnModal" aria-hidden="true">
        <div class="modal" role="dialog" aria-modal="true" aria-labelledby="tryOnModalTitle">
            <div class="modal-header">
                <div class="modal-title-row">
                    <h2 class="modal-title" id="tryOnModalTitle">{{ __('ui.store.tryon_tool_title') }}</h2>
                    @if (($tryOnDummy['enabled'] ?? false) === true)
                        <span class="dummy-mode-badge">{{ __('ui.store.dummy_mode') }}</span>
                    @endif
                </div>
                <button type="button" class="modal-close" onclick="closeTryOnModal()" aria-label="{{ __('ui.common.close') }}">&times;</button>
            </div>

            <div class="modal-info-grid">
                <div id="selectedProductInfo" class="selected-product">
                    {{ __('ui.common.products') }}: <strong id="selectedProductName">-</strong>
                </div>
                <div id="quotaBox" class="quota-box">
                    {{ __('ui.store.quota_today_remaining') }}: <strong id="remainingQuotaText">-</strong>
                </div>
            </div>
            <input class="input-file" id="customerPhoto" type="file" accept="image/*">

            <div class="tryon-main-grid">
                <div class="tryon-left-rail">
                    <div class="field">
                        <div class="tryon-panel-head">
                            <label class="label">{{ __('ui.store.product_photo') }}</label>
                        </div>
                        <div class="selected-product-thumb preview-box preview-box-product" aria-label="{{ __('ui.store.selected_product_alt') }}">
                            <img id="selectedProductPreview" alt="{{ __('ui.store.selected_product_alt') }}" style="display:none;">
                            <span id="selectedProductPreviewFallback" class="selected-product-thumb-fallback">{{ __('ui.store.no_image') }}</span>
                        </div>
                        <div id="feedbackWrap" class="feedback-wrap feedback-wrap-under-product">
                            <p class="feedback-title">{{ __('ui.store.feedback_title') }}</p>
                            <p class="feedback-hint">{{ __('ui.store.feedback_hint') }}</p>
                            <div id="feedbackStars" class="feedback-stars" role="radiogroup" aria-label="{{ __('ui.store.feedback_stars_aria') }}">
                                <button type="button" class="feedback-star" data-rating="1" aria-label="1 star">★</button>
                                <button type="button" class="feedback-star" data-rating="2" aria-label="2 stars">★</button>
                                <button type="button" class="feedback-star" data-rating="3" aria-label="3 stars">★</button>
                                <button type="button" class="feedback-star" data-rating="4" aria-label="4 stars">★</button>
                                <button type="button" class="feedback-star" data-rating="5" aria-label="5 stars">★</button>
                            </div>
                            <textarea id="feedbackComment" class="feedback-comment" maxlength="1000" placeholder="{{ __('ui.store.feedback_comment_placeholder') }}"></textarea>
                            <div class="feedback-actions">
                                <div id="feedbackStatus" class="feedback-status" role="status" aria-live="polite"></div>
                                <button id="feedbackSubmitBtn" class="feedback-submit-btn" type="button">{{ __('ui.store.feedback_submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="field tryon-generated-field">
                    <div class="tryon-panel-head">
                        <label class="label">{{ __('ui.store.tryon_generated') }}</label>
                        <div class="tryon-header-toggle">
                            <label class="dummy-toggle" id="dummyModelToggleWrap" style="display:none;">
                                <input type="checkbox" id="useDummyModelToggle">
                                <span class="dummy-toggle-switch" aria-hidden="true"></span>
                                <span class="dummy-toggle-text">{{ __('ui.store.use_dummy') }}</span>
                            </label>
                        </div>
                    </div>
                    <div class="preview-box preview-box-result">
                        <div class="compare-frame" id="compareFrame">
                            <div class="compare-labels">
                                <span class="compare-chip">Before</span>
                                <span class="compare-chip">After</span>
                            </div>
                            <div class="compare-model-stage" id="compareModelStage">
                                <div id="modelAdjustSurface" class="compare-model-media">
                                    <img id="customerPreview" alt="Customer preview">
                                    <button id="removePhotoBtn" class="preview-remove" type="button" aria-label="{{ __('ui.store.remove_photo_aria') }}">&times;</button>
                                    <div id="customerPlaceholder" class="compare-model-placeholder">
                                        <p>{{ __('ui.store.upload_photo') }}</p>
                                        <button type="button" class="upload-trigger" onclick="document.getElementById('customerPhoto').click()">{{ __('ui.store.choose_file') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="compare-before">
                                <img id="compareBeforePreview" alt="Model preview">
                            </div>
                            <div class="compare-after-wrap" id="compareAfterWrap">
                                <img id="resultPreview" alt="Try-on result">
                            </div>
                            <div id="resultPlaceholder" class="preview-placeholder"></div>
                            <div class="compare-divider" id="compareDivider"></div>
                            <input id="compareSlider" class="compare-slider" type="range" min="0" max="100" value="50" aria-label="Compare before and after">
                        </div>
                    </div>
                    <div id="modelAdjustPanel" class="model-adjust-panel" style="display:none;">
                        <div class="model-adjust-header">
                            <p class="model-adjust-title">{{ __('ui.store.model_adjust_title') }}</p>
                            <button id="modelAdjustResetBtn" class="model-adjust-reset" type="button">{{ __('ui.store.model_adjust_reset') }}</button>
                        </div>
                        <div class="model-adjust-controls">
                            <div class="model-adjust-row">
                                <div class="model-adjust-row-head">
                                    <span>{{ __('ui.store.model_adjust_zoom') }}</span>
                                    <strong id="modelAdjustZoomValue">100%</strong>
                                </div>
                                <input id="modelAdjustZoom" type="range" min="100" max="250" step="1" value="100" aria-label="{{ __('ui.store.model_adjust_zoom') }}">
                            </div>
                            <div class="model-adjust-row">
                                <div class="model-adjust-row-head">
                                    <span>{{ __('ui.store.model_adjust_horizontal') }}</span>
                                    <strong id="modelAdjustOffsetXValue">0%</strong>
                                </div>
                                <input id="modelAdjustOffsetX" type="range" min="-100" max="100" step="1" value="0" aria-label="{{ __('ui.store.model_adjust_horizontal') }}">
                            </div>
                            <div class="model-adjust-row">
                                <div class="model-adjust-row-head">
                                    <span>{{ __('ui.store.model_adjust_vertical') }}</span>
                                    <strong id="modelAdjustOffsetYValue">0%</strong>
                                </div>
                                <input id="modelAdjustOffsetY" type="range" min="-100" max="100" step="1" value="0" aria-label="{{ __('ui.store.model_adjust_vertical') }}">
                            </div>
                        </div>
                    </div>
                    <div id="statusNote" class="status-note" role="status" aria-live="polite"></div>
                </div>
            </div>

            <div class="tryon-action-row">
                <div id="marketplaceLinks" class="marketplace-links"></div>
                <button id="generateBtn" class="generate-btn" type="button" onclick="submitTryOn()">{{ __('ui.store.tryon_action') }}</button>
            </div>
        </div>
    </div>

    <script>
        const I18N = {
            selectProductFirst: @json(__('ui.store.select_product_first')),
            generateDone: @json(__('ui.store.generate_done')),
            uploadModelFirst: @json(__('ui.store.upload_model_first')),
            dailyLimitReached: @json(__('ui.store.daily_limit_reached')),
            failedCreateSession: @json(__('ui.store.failed_create_session')),
            failedCheckStatus: @json(__('ui.store.failed_check_status')),
            failedPolling: @json(__('ui.store.failed_polling')),
            historyResultShown: @json(__('ui.store.history_result_shown')),
            historyItemAria: @json(__('ui.store.history_button_aria')),
            historyImageAlt: @json(__('ui.store.tryon_generated')),
            dummyModelUrlMissing: @json(__('ui.store.dummy_model_url_missing')),
            dummyResultUrlMissing: @json(__('ui.store.dummy_result_url_missing')),
            processingRetryLater: @json(__('ui.store.processing_retry_later')),
            feedbackSubmit: @json(__('ui.store.feedback_submit')),
            feedbackUpdate: @json(__('ui.store.feedback_update')),
            feedbackSaved: @json(__('ui.store.feedback_saved')),
            feedbackAlreadySent: @json(__('ui.store.feedback_already_sent')),
            feedbackRatingRequired: @json(__('ui.store.feedback_rating_required')),
            feedbackSubmitFailed: @json(__('ui.store.feedback_submit_failed')),
            modelAdjustInvalidFile: @json(__('ui.store.model_adjust_invalid_file')),
            modelAdjustFileTooLarge: @json(__('ui.store.model_adjust_file_too_large')),
        };

        let selectedProductId = @json(optional($selectedProduct)->id);
        let pollTimer = null;
        const TRYON_DEVICE_KEY = 'tryon_device_id_v1';
        const TRYON_DUMMY = @json($tryOnDummy ?? ['enabled' => false, 'model_image_url' => '', 'result_url' => '']);
        const TRYON_HISTORY_URL = @json(route('public.tryon.sessions.history', ['seller_slug' => $seller->slug]));
        const TRYON_PUBLIC_BASE_URL = @json(url('/'.$seller->slug.'/try-on'));
        let remainingDailyQuota = null;
        let useDummyModelForRealGenerate = false;
        let selectedProductLinkUrl = '';
        let activeFeedbackSessionId = null;
        let selectedFeedbackRating = 0;
        let isSubmittingFeedback = false;
        const MODEL_ADJUST_ZOOM_MIN = 1;
        const MODEL_ADJUST_ZOOM_MAX = 2.5;
        const modelAdjustState = {
            originalFile: null,
            image: null,
            sourceDataUrl: '',
            width: 0,
            height: 0,
            zoom: MODEL_ADJUST_ZOOM_MIN,
            offsetX: 0,
            offsetY: 0,
            previewDataUrl: '',
        };
        const modelAdjustInteraction = {
            mouseActive: false,
            mouseMoved: false,
            mouseLastX: 0,
            mouseLastY: 0,
            touchMode: 'none',
            touchLastX: 0,
            touchLastY: 0,
            pinchDistance: 0,
            pinchCenterX: 0,
            pinchCenterY: 0,
            pinchStartZoom: MODEL_ADJUST_ZOOM_MIN,
            pinchAnchorWorldX: 0,
            pinchAnchorWorldY: 0,
            renderQueued: false,
            lastTouchAt: 0,
        };

        function resolveTryOnDeviceId() {
            try {
                const existing = window.localStorage.getItem(TRYON_DEVICE_KEY);
                if (existing && typeof existing === 'string') {
                    return existing;
                }

                const generated = (window.crypto && window.crypto.randomUUID)
                    ? window.crypto.randomUUID()
                    : `dev-${Date.now()}-${Math.random().toString(36).slice(2, 12)}`;

                window.localStorage.setItem(TRYON_DEVICE_KEY, generated);
                return generated;
            } catch (error) {
                return `dev-fallback-${Date.now()}`;
            }
        }

        function setComparisonPosition(value) {
            const slider = document.getElementById('compareSlider');
            const afterWrap = document.getElementById('compareAfterWrap');
            const divider = document.getElementById('compareDivider');

            const normalized = Math.max(0, Math.min(100, Number(value) || 50));
            if (slider) slider.value = String(normalized);
            if (afterWrap) afterWrap.style.clipPath = `inset(0 0 0 ${normalized}%)`;
            if (divider) divider.style.left = `${normalized}%`;
        }

        function updateCompareMode(hasResult) {
            const frame = document.getElementById('compareFrame');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            if (!frame || !resultPlaceholder) {
                return;
            }

            if (hasResult) {
                frame.classList.remove('is-history-full');
                frame.classList.add('is-comparison');
                resultPlaceholder.style.display = 'none';
                return;
            }

            frame.classList.remove('is-comparison');
            frame.classList.remove('is-history-full');
            resultPlaceholder.style.display = 'none';
        }

        function setHistoryPreviewMode(active) {
            const frame = document.getElementById('compareFrame');
            if (!frame) {
                return;
            }
            if (active) {
                frame.classList.remove('is-comparison');
                frame.classList.add('is-history-full');
                return;
            }
            frame.classList.remove('is-history-full');
        }

        function syncComparisonBeforeImage() {
            const customerPreview = document.getElementById('customerPreview');
            const compareBeforePreview = document.getElementById('compareBeforePreview');
            if (!customerPreview || !compareBeforePreview) {
                return;
            }

            const modelSrc = customerPreview.getAttribute('src') || '';
            if (modelSrc) {
                compareBeforePreview.src = modelSrc;
                compareBeforePreview.style.display = 'block';
                compareBeforePreview.style.transformOrigin = customerPreview.style.transformOrigin || 'center center';
                compareBeforePreview.style.transform = customerPreview.style.transform || '';
                return;
            }

            compareBeforePreview.removeAttribute('src');
            compareBeforePreview.style.display = 'none';
            compareBeforePreview.style.transform = '';
        }

        function resetModelAdjustState() {
            modelAdjustState.originalFile = null;
            modelAdjustState.image = null;
            modelAdjustState.sourceDataUrl = '';
            modelAdjustState.width = 0;
            modelAdjustState.height = 0;
            modelAdjustState.zoom = MODEL_ADJUST_ZOOM_MIN;
            modelAdjustState.offsetX = 0;
            modelAdjustState.offsetY = 0;
            modelAdjustState.previewDataUrl = '';
            modelAdjustInteraction.mouseActive = false;
            modelAdjustInteraction.mouseMoved = false;
            modelAdjustInteraction.mouseLastX = 0;
            modelAdjustInteraction.mouseLastY = 0;
            modelAdjustInteraction.touchMode = 'none';
            modelAdjustInteraction.touchLastX = 0;
            modelAdjustInteraction.touchLastY = 0;
            modelAdjustInteraction.pinchDistance = 0;
            modelAdjustInteraction.pinchCenterX = 0;
            modelAdjustInteraction.pinchCenterY = 0;
            modelAdjustInteraction.pinchStartZoom = MODEL_ADJUST_ZOOM_MIN;
            modelAdjustInteraction.pinchAnchorWorldX = 0;
            modelAdjustInteraction.pinchAnchorWorldY = 0;
            modelAdjustInteraction.renderQueued = false;
            setModelAdjustDraggingState(false);
            const preview = document.getElementById('customerPreview');
            const compareBeforePreview = document.getElementById('compareBeforePreview');
            if (preview) {
                preview.style.transform = '';
                preview.style.transformOrigin = '';
            }
            if (compareBeforePreview) {
                compareBeforePreview.style.transform = '';
                compareBeforePreview.style.transformOrigin = '';
            }
            applyModelAdjustStateToControls();
            toggleModelAdjustPanel(false);
            syncModelAdjustSurfaceState();
        }

        function toggleModelAdjustPanel(visible) {
            const panel = document.getElementById('modelAdjustPanel');
            if (!panel) {
                return;
            }

            // Gesture interaction is now the primary control, so keep the slider panel hidden.
            panel.style.display = 'none';
        }

        function clampModelAdjustNumber(value, min, max) {
            const numeric = Number(value);
            if (Number.isNaN(numeric)) {
                return min;
            }

            return Math.max(min, Math.min(max, numeric));
        }

        function isModelAdjustInteractive() {
            if (!modelAdjustState.image || !modelAdjustState.originalFile) {
                return false;
            }

            if (TRYON_DUMMY.enabled) {
                return false;
            }

            if (Boolean(TRYON_DUMMY.model_image_url) && useDummyModelForRealGenerate) {
                return false;
            }

            return true;
        }

        function setModelAdjustDraggingState(isDragging) {
            const surface = document.getElementById('modelAdjustSurface');
            if (!surface) {
                return;
            }

            if (isDragging) {
                surface.classList.add('is-dragging');
                return;
            }

            surface.classList.remove('is-dragging');
        }

        function syncModelAdjustSurfaceState() {
            const surface = document.getElementById('modelAdjustSurface');
            if (!surface) {
                return;
            }

            if (isModelAdjustInteractive()) {
                surface.classList.add('is-adjust-ready');
                return;
            }

            surface.classList.remove('is-adjust-ready', 'is-dragging');
        }

        function getModelAdjustStageRect() {
            const surface = document.getElementById('modelAdjustSurface');
            if (!surface) {
                return null;
            }

            const rect = surface.getBoundingClientRect();
            if (rect.width <= 0 || rect.height <= 0) {
                return null;
            }

            return rect;
        }

        function getModelAdjustTranslatePx(
            stageRect,
            zoom = modelAdjustState.zoom,
            offsetX = modelAdjustState.offsetX,
            offsetY = modelAdjustState.offsetY
        ) {
            const safeZoom = clampModelAdjustNumber(zoom, MODEL_ADJUST_ZOOM_MIN, MODEL_ADJUST_ZOOM_MAX);
            const safeOffsetX = clampModelAdjustNumber(offsetX, -100, 100);
            const safeOffsetY = clampModelAdjustNumber(offsetY, -100, 100);
            const maxTranslateX = Math.max((stageRect.width * (safeZoom - 1)) / 2, 0);
            const maxTranslateY = Math.max((stageRect.height * (safeZoom - 1)) / 2, 0);

            return {
                zoom: safeZoom,
                translateX: maxTranslateX > 0 ? maxTranslateX * (safeOffsetX / 100) : 0,
                translateY: maxTranslateY > 0 ? maxTranslateY * (safeOffsetY / 100) : 0,
                maxTranslateX,
                maxTranslateY,
            };
        }

        function setModelAdjustFromTranslatePx(stageRect, zoom, translateX, translateY) {
            const safeZoom = clampModelAdjustNumber(zoom, MODEL_ADJUST_ZOOM_MIN, MODEL_ADJUST_ZOOM_MAX);
            const maxTranslateX = Math.max((stageRect.width * (safeZoom - 1)) / 2, 0);
            const maxTranslateY = Math.max((stageRect.height * (safeZoom - 1)) / 2, 0);

            modelAdjustState.zoom = safeZoom;
            modelAdjustState.offsetX = maxTranslateX > 0
                ? clampModelAdjustNumber((translateX / maxTranslateX) * 100, -100, 100)
                : 0;
            modelAdjustState.offsetY = maxTranslateY > 0
                ? clampModelAdjustNumber((translateY / maxTranslateY) * 100, -100, 100)
                : 0;
        }

        function queueModelAdjustPreviewRender() {
            if (modelAdjustInteraction.renderQueued) {
                return;
            }

            modelAdjustInteraction.renderQueued = true;
            window.requestAnimationFrame(() => {
                modelAdjustInteraction.renderQueued = false;
                renderAdjustedModelPreview();
            });
        }

        function applyModelAdjustTransforms() {
            const preview = document.getElementById('customerPreview');
            const compareBeforePreview = document.getElementById('compareBeforePreview');
            if (!preview && !compareBeforePreview) {
                return;
            }

            const stageRect = getModelAdjustStageRect();
            if (!stageRect) {
                return;
            }

            const { zoom, translateX, translateY } = getModelAdjustTranslatePx(stageRect);
            const transform = `translate(${translateX}px, ${translateY}px) scale(${zoom})`;

            [preview, compareBeforePreview].forEach((element) => {
                if (!element) {
                    return;
                }

                element.style.transformOrigin = 'center center';
                element.style.transform = transform;
            });
        }

        function adjustOffsetsFromPanDelta(deltaX, deltaY) {
            if (!isModelAdjustInteractive()) {
                return;
            }

            const stageRect = getModelAdjustStageRect();
            if (!stageRect) {
                return;
            }

            const current = getModelAdjustTranslatePx(stageRect);
            const targetTranslateX = current.translateX + deltaX;
            const targetTranslateY = current.translateY + deltaY;

            setModelAdjustFromTranslatePx(stageRect, current.zoom, targetTranslateX, targetTranslateY);
            applyModelAdjustStateToControls();
            queueModelAdjustPreviewRender();
        }

        function zoomModelAtClientPoint(targetZoom, clientX, clientY) {
            if (!isModelAdjustInteractive()) {
                return;
            }

            const stageRect = getModelAdjustStageRect();
            if (!stageRect) {
                return;
            }

            const oldState = getModelAdjustTranslatePx(stageRect);
            const newZoom = clampModelAdjustNumber(targetZoom, MODEL_ADJUST_ZOOM_MIN, MODEL_ADJUST_ZOOM_MAX);
            if (Math.abs(newZoom - oldState.zoom) < 0.0001) {
                return;
            }

            const pointX = clientX - stageRect.left - (stageRect.width / 2);
            const pointY = clientY - stageRect.top - (stageRect.height / 2);
            const ratio = newZoom / oldState.zoom;
            const newTranslateX = pointX - ((pointX - oldState.translateX) * ratio);
            const newTranslateY = pointY - ((pointY - oldState.translateY) * ratio);

            setModelAdjustFromTranslatePx(stageRect, newZoom, newTranslateX, newTranslateY);
            applyModelAdjustStateToControls();
            queueModelAdjustPreviewRender();
        }

        function beginPinchInteraction(touches) {
            const stageRect = getModelAdjustStageRect();
            if (!stageRect || touches.length < 2) {
                return;
            }

            const first = touches[0];
            const second = touches[1];
            const centerX = (first.clientX + second.clientX) / 2;
            const centerY = (first.clientY + second.clientY) / 2;
            const distance = Math.hypot(second.clientX - first.clientX, second.clientY - first.clientY);
            const pointX = centerX - stageRect.left - (stageRect.width / 2);
            const pointY = centerY - stageRect.top - (stageRect.height / 2);
            const current = getModelAdjustTranslatePx(stageRect);

            modelAdjustInteraction.touchMode = 'pinch';
            modelAdjustInteraction.pinchDistance = distance;
            modelAdjustInteraction.pinchCenterX = centerX;
            modelAdjustInteraction.pinchCenterY = centerY;
            modelAdjustInteraction.pinchStartZoom = current.zoom;
            modelAdjustInteraction.pinchAnchorWorldX = (pointX - current.translateX) / current.zoom;
            modelAdjustInteraction.pinchAnchorWorldY = (pointY - current.translateY) / current.zoom;
        }

        function applyPinchInteraction(touches) {
            const stageRect = getModelAdjustStageRect();
            if (!stageRect || touches.length < 2 || modelAdjustInteraction.pinchDistance <= 0) {
                return;
            }

            const first = touches[0];
            const second = touches[1];
            const centerX = (first.clientX + second.clientX) / 2;
            const centerY = (first.clientY + second.clientY) / 2;
            const distance = Math.hypot(second.clientX - first.clientX, second.clientY - first.clientY);
            const pointX = centerX - stageRect.left - (stageRect.width / 2);
            const pointY = centerY - stageRect.top - (stageRect.height / 2);
            const zoomRatio = distance / modelAdjustInteraction.pinchDistance;
            const targetZoom = modelAdjustInteraction.pinchStartZoom * zoomRatio;
            const safeZoom = clampModelAdjustNumber(targetZoom, MODEL_ADJUST_ZOOM_MIN, MODEL_ADJUST_ZOOM_MAX);
            const translateX = pointX - (safeZoom * modelAdjustInteraction.pinchAnchorWorldX);
            const translateY = pointY - (safeZoom * modelAdjustInteraction.pinchAnchorWorldY);

            setModelAdjustFromTranslatePx(stageRect, safeZoom, translateX, translateY);
            modelAdjustInteraction.pinchCenterX = centerX;
            modelAdjustInteraction.pinchCenterY = centerY;
            applyModelAdjustStateToControls();
            queueModelAdjustPreviewRender();
        }

        function applyModelAdjustStateToControls() {
            const zoomInput = document.getElementById('modelAdjustZoom');
            const offsetXInput = document.getElementById('modelAdjustOffsetX');
            const offsetYInput = document.getElementById('modelAdjustOffsetY');
            const zoomValue = document.getElementById('modelAdjustZoomValue');
            const offsetXValue = document.getElementById('modelAdjustOffsetXValue');
            const offsetYValue = document.getElementById('modelAdjustOffsetYValue');

            const zoomPercent = Math.round(modelAdjustState.zoom * 100);
            if (zoomInput) {
                zoomInput.value = String(zoomPercent);
            }
            if (offsetXInput) {
                offsetXInput.value = String(Math.round(modelAdjustState.offsetX));
            }
            if (offsetYInput) {
                offsetYInput.value = String(Math.round(modelAdjustState.offsetY));
            }

            if (zoomValue) {
                zoomValue.textContent = `${zoomPercent}%`;
            }
            if (offsetXValue) {
                offsetXValue.textContent = `${Math.round(modelAdjustState.offsetX)}%`;
            }
            if (offsetYValue) {
                offsetYValue.textContent = `${Math.round(modelAdjustState.offsetY)}%`;
            }
        }

        function drawAdjustedModelCanvas() {
            if (!modelAdjustState.image || modelAdjustState.width <= 0 || modelAdjustState.height <= 0) {
                return null;
            }

            const canvas = document.createElement('canvas');
            canvas.width = modelAdjustState.width;
            canvas.height = modelAdjustState.height;

            const ctx = canvas.getContext('2d');
            if (!ctx) {
                return null;
            }

            const zoom = Math.max(MODEL_ADJUST_ZOOM_MIN, Math.min(MODEL_ADJUST_ZOOM_MAX, modelAdjustState.zoom));
            const offsetX = Math.max(-100, Math.min(100, modelAdjustState.offsetX));
            const offsetY = Math.max(-100, Math.min(100, modelAdjustState.offsetY));

            const drawWidth = modelAdjustState.width * zoom;
            const drawHeight = modelAdjustState.height * zoom;
            const maxTranslateX = Math.max((drawWidth - modelAdjustState.width) / 2, 0);
            const maxTranslateY = Math.max((drawHeight - modelAdjustState.height) / 2, 0);

            const drawX = ((modelAdjustState.width - drawWidth) / 2) + (maxTranslateX * (offsetX / 100));
            const drawY = ((modelAdjustState.height - drawHeight) / 2) + (maxTranslateY * (offsetY / 100));

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = 'high';
            ctx.drawImage(modelAdjustState.image, drawX, drawY, drawWidth, drawHeight);

            return canvas;
        }

        function renderAdjustedModelPreview() {
            const preview = document.getElementById('customerPreview');
            const placeholder = document.getElementById('customerPlaceholder');
            const removeBtn = document.getElementById('removePhotoBtn');

            if (!preview || !placeholder || !removeBtn) {
                return;
            }

            if (!modelAdjustState.image || modelAdjustState.sourceDataUrl === '') {
                return;
            }

            if (preview.getAttribute('src') !== modelAdjustState.sourceDataUrl) {
                preview.src = modelAdjustState.sourceDataUrl;
            }
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            removeBtn.style.display = 'inline-block';
            syncComparisonBeforeImage();
            applyModelAdjustTransforms();
            syncModelAdjustSurfaceState();
        }

        function updateModelAdjustStateFromControls() {
            if (!modelAdjustState.image) {
                return;
            }

            const zoomInput = document.getElementById('modelAdjustZoom');
            const offsetXInput = document.getElementById('modelAdjustOffsetX');
            const offsetYInput = document.getElementById('modelAdjustOffsetY');

            const zoomValue = Number(zoomInput ? zoomInput.value : 100);
            const offsetXValue = Number(offsetXInput ? offsetXInput.value : 0);
            const offsetYValue = Number(offsetYInput ? offsetYInput.value : 0);

            modelAdjustState.zoom = clampModelAdjustNumber(zoomValue / 100, MODEL_ADJUST_ZOOM_MIN, MODEL_ADJUST_ZOOM_MAX);
            modelAdjustState.offsetX = clampModelAdjustNumber(offsetXValue, -100, 100);
            modelAdjustState.offsetY = clampModelAdjustNumber(offsetYValue, -100, 100);

            applyModelAdjustStateToControls();
            queueModelAdjustPreviewRender();
        }

        async function loadCustomerPhotoForAdjust(file) {
            if (!file) {
                resetModelAdjustState();
                return;
            }

            try {
                const sourceDataUrl = await new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onload = () => {
                        if (typeof reader.result !== 'string' || reader.result === '') {
                            reject(new Error(I18N.modelAdjustInvalidFile));
                            return;
                        }

                        resolve(reader.result);
                    };
                    reader.onerror = () => reject(new Error(I18N.modelAdjustInvalidFile));
                    reader.readAsDataURL(file);
                });

                const image = new Image();
                await new Promise((resolve, reject) => {
                    image.onload = () => resolve();
                    image.onerror = () => reject(new Error(I18N.modelAdjustInvalidFile));
                    image.src = sourceDataUrl;
                });

                modelAdjustState.originalFile = file;
                modelAdjustState.image = image;
                modelAdjustState.sourceDataUrl = sourceDataUrl;
                modelAdjustState.width = Number(image.naturalWidth) || 0;
                modelAdjustState.height = Number(image.naturalHeight) || 0;
                modelAdjustState.zoom = MODEL_ADJUST_ZOOM_MIN;
                modelAdjustState.offsetX = 0;
                modelAdjustState.offsetY = 0;
                modelAdjustState.previewDataUrl = '';

                applyModelAdjustStateToControls();
                toggleModelAdjustPanel(true);
                renderAdjustedModelPreview();
                syncModelAdjustSurfaceState();
            } catch (error) {
                resetModelAdjustState();
                setStatus(error.message || I18N.modelAdjustInvalidFile, 'error');
            }
        }

        function buildAdjustedModelFileName(originalName, mimeType) {
            const safeName = (typeof originalName === 'string' && originalName.trim() !== '') ? originalName.trim() : 'model-photo';
            const base = safeName.replace(/\.[^./\\]+$/, '');
            const extensionMap = {
                'image/jpeg': 'jpg',
                'image/png': 'png',
                'image/webp': 'webp',
            };
            const extension = extensionMap[mimeType] || 'jpg';

            return `${base}-adjusted.${extension}`;
        }

        async function buildAdjustedModelUploadFile() {
            if (!modelAdjustState.originalFile || !modelAdjustState.image) {
                return null;
            }

            const canvas = drawAdjustedModelCanvas();
            if (!canvas) {
                return modelAdjustState.originalFile;
            }

            const candidateType = (modelAdjustState.originalFile.type || '').toLowerCase();
            const mimeType = candidateType === 'image/webp' ? 'image/webp' : 'image/jpeg';
            const maxBytes = 10 * 1024 * 1024;

            let quality = mimeType === 'image/webp' ? 0.95 : 0.92;
            let blob = await new Promise((resolve, reject) => {
                canvas.toBlob((generatedBlob) => {
                    if (!generatedBlob) {
                        reject(new Error(I18N.modelAdjustInvalidFile));
                        return;
                    }
                    resolve(generatedBlob);
                }, mimeType, quality);
            });

            while (blob.size > maxBytes && quality > 0.62) {
                quality = Number((quality - 0.08).toFixed(2));
                blob = await new Promise((resolve, reject) => {
                    canvas.toBlob((generatedBlob) => {
                        if (!generatedBlob) {
                            reject(new Error(I18N.modelAdjustInvalidFile));
                            return;
                        }
                        resolve(generatedBlob);
                    }, mimeType, quality);
                });
            }

            if (blob.size > maxBytes) {
                throw new Error(I18N.modelAdjustFileTooLarge);
            }

            return new File(
                [blob],
                buildAdjustedModelFileName(modelAdjustState.originalFile.name, mimeType),
                {
                    type: mimeType,
                    lastModified: Date.now(),
                }
            );
        }

        function initModelAdjustGestures() {
            const surface = document.getElementById('modelAdjustSurface');
            if (!surface) {
                return;
            }

            const shouldIgnoreTarget = (target) => {
                if (!(target instanceof Element)) {
                    return false;
                }

                return Boolean(target.closest('#removePhotoBtn') || target.closest('.upload-trigger'));
            };

            const canInteractFromEvent = (target) => {
                if (!isModelAdjustInteractive()) {
                    return false;
                }

                if (shouldIgnoreTarget(target)) {
                    return false;
                }

                return true;
            };

            surface.addEventListener('mousedown', function(event) {
                if (event.button !== 0 || !canInteractFromEvent(event.target)) {
                    return;
                }

                event.preventDefault();
                modelAdjustInteraction.mouseActive = true;
                modelAdjustInteraction.mouseMoved = false;
                modelAdjustInteraction.mouseLastX = event.clientX;
                modelAdjustInteraction.mouseLastY = event.clientY;
                setModelAdjustDraggingState(true);
            });

            window.addEventListener('mousemove', function(event) {
                if (!modelAdjustInteraction.mouseActive) {
                    return;
                }

                const deltaX = event.clientX - modelAdjustInteraction.mouseLastX;
                const deltaY = event.clientY - modelAdjustInteraction.mouseLastY;
                modelAdjustInteraction.mouseLastX = event.clientX;
                modelAdjustInteraction.mouseLastY = event.clientY;

                if (Math.abs(deltaX) > 0 || Math.abs(deltaY) > 0) {
                    modelAdjustInteraction.mouseMoved = true;
                    adjustOffsetsFromPanDelta(deltaX, deltaY);
                }
            });

            window.addEventListener('mouseup', function() {
                if (!modelAdjustInteraction.mouseActive) {
                    return;
                }

                modelAdjustInteraction.mouseActive = false;
                setModelAdjustDraggingState(false);
            });

            surface.addEventListener('wheel', function(event) {
                if (!canInteractFromEvent(event.target)) {
                    return;
                }

                event.preventDefault();
                const zoomFactor = event.deltaY < 0 ? 1.08 : 0.92;
                zoomModelAtClientPoint(modelAdjustState.zoom * zoomFactor, event.clientX, event.clientY);
            }, { passive: false });

            surface.addEventListener('click', function(event) {
                if (!canInteractFromEvent(event.target)) {
                    return;
                }

                if (Date.now() - modelAdjustInteraction.lastTouchAt < 350) {
                    return;
                }

                if (modelAdjustInteraction.mouseMoved) {
                    modelAdjustInteraction.mouseMoved = false;
                    return;
                }

                zoomModelAtClientPoint(modelAdjustState.zoom + 0.15, event.clientX, event.clientY);
            });

            surface.addEventListener('contextmenu', function(event) {
                if (!canInteractFromEvent(event.target)) {
                    return;
                }

                event.preventDefault();
                zoomModelAtClientPoint(modelAdjustState.zoom - 0.15, event.clientX, event.clientY);
            });

            surface.addEventListener('touchstart', function(event) {
                if (!canInteractFromEvent(event.target)) {
                    return;
                }

                modelAdjustInteraction.lastTouchAt = Date.now();
                if (event.touches.length === 1) {
                    const touch = event.touches[0];
                    modelAdjustInteraction.touchMode = 'pan';
                    modelAdjustInteraction.touchLastX = touch.clientX;
                    modelAdjustInteraction.touchLastY = touch.clientY;
                    setModelAdjustDraggingState(true);
                    return;
                }

                if (event.touches.length >= 2) {
                    beginPinchInteraction(event.touches);
                    setModelAdjustDraggingState(true);
                    event.preventDefault();
                }
            }, { passive: false });

            surface.addEventListener('touchmove', function(event) {
                if (!isModelAdjustInteractive()) {
                    return;
                }

                if (event.touches.length === 1 && modelAdjustInteraction.touchMode === 'pan') {
                    const touch = event.touches[0];
                    const deltaX = touch.clientX - modelAdjustInteraction.touchLastX;
                    const deltaY = touch.clientY - modelAdjustInteraction.touchLastY;
                    modelAdjustInteraction.touchLastX = touch.clientX;
                    modelAdjustInteraction.touchLastY = touch.clientY;
                    adjustOffsetsFromPanDelta(deltaX, deltaY);
                    event.preventDefault();
                    return;
                }

                if (event.touches.length >= 2) {
                    if (modelAdjustInteraction.touchMode !== 'pinch') {
                        beginPinchInteraction(event.touches);
                    } else {
                        applyPinchInteraction(event.touches);
                    }
                    event.preventDefault();
                }
            }, { passive: false });

            surface.addEventListener('touchend', function(event) {
                modelAdjustInteraction.lastTouchAt = Date.now();
                if (event.touches.length === 0) {
                    modelAdjustInteraction.touchMode = 'none';
                    modelAdjustInteraction.pinchDistance = 0;
                    modelAdjustInteraction.pinchStartZoom = modelAdjustState.zoom;
                    modelAdjustInteraction.pinchAnchorWorldX = 0;
                    modelAdjustInteraction.pinchAnchorWorldY = 0;
                    setModelAdjustDraggingState(false);
                    return;
                }

                if (event.touches.length === 1) {
                    modelAdjustInteraction.touchMode = 'pan';
                    modelAdjustInteraction.pinchDistance = 0;
                    modelAdjustInteraction.pinchCenterX = 0;
                    modelAdjustInteraction.pinchCenterY = 0;
                    modelAdjustInteraction.pinchStartZoom = modelAdjustState.zoom;
                    modelAdjustInteraction.pinchAnchorWorldX = 0;
                    modelAdjustInteraction.pinchAnchorWorldY = 0;
                    modelAdjustInteraction.touchLastX = event.touches[0].clientX;
                    modelAdjustInteraction.touchLastY = event.touches[0].clientY;
                    setModelAdjustDraggingState(true);
                }
            });

            surface.addEventListener('touchcancel', function() {
                modelAdjustInteraction.touchMode = 'none';
                modelAdjustInteraction.pinchDistance = 0;
                modelAdjustInteraction.pinchCenterX = 0;
                modelAdjustInteraction.pinchCenterY = 0;
                modelAdjustInteraction.pinchStartZoom = modelAdjustState.zoom;
                modelAdjustInteraction.pinchAnchorWorldX = 0;
                modelAdjustInteraction.pinchAnchorWorldY = 0;
                setModelAdjustDraggingState(false);
            });
        }

        function selectProduct(el) {
            const cards = document.querySelectorAll('#productGrid .card');
            cards.forEach((card) => card.classList.remove('selected'));
            el.classList.add('selected');

            const productName = el.getAttribute('data-product-name') || '-';
            const productImage = el.getAttribute('data-product-image') || '';
            selectedProductId = Number(el.getAttribute('data-product-id')) || null;
            const productSlug = el.getAttribute('data-product-slug');
            const productSku = el.getAttribute('data-product-sku');
            const productLinkUrl = el.getAttribute('data-product-link-url') || '';
            selectedProductLinkUrl = productLinkUrl;
            document.getElementById('selectedProductName').textContent = productName;
            renderMarketplaceLinks(productLinkUrl);
            const selectedProductPreview = document.getElementById('selectedProductPreview');
            const selectedProductPreviewFallback = document.getElementById('selectedProductPreviewFallback');
            if (selectedProductPreview && selectedProductPreviewFallback) {
                if (productImage) {
                    selectedProductPreview.src = productImage;
                    selectedProductPreview.style.display = 'block';
                    selectedProductPreviewFallback.style.display = 'none';
                } else {
                    selectedProductPreview.removeAttribute('src');
                    selectedProductPreview.style.display = 'none';
                    selectedProductPreviewFallback.style.display = 'inline';
                }
            }

            const productRef = productSku || productSlug;
            if (productRef) {
                const newUrl = `/${@json($seller->slug)}/${productRef}`;
                window.history.replaceState({}, '', newUrl);
            }
        }

        function renderMarketplaceLinks(productLinkUrl) {
            const wrap = document.getElementById('marketplaceLinks');
            if (!wrap) return;
            wrap.innerHTML = '';
            wrap.classList.remove('has-link');
            const link = (productLinkUrl || '').trim();
            if (!link) return;

            const a = document.createElement('a');
            a.className = 'marketplace-link-btn';
            a.href = link;
            a.target = '_blank';
            a.rel = 'noopener noreferrer';
            a.innerHTML = `
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <circle cx="9" cy="20" r="1.5"></circle>
                    <circle cx="18" cy="20" r="1.5"></circle>
                    <path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.9a2 2 0 0 0 2-1.6L21 8H7"></path>
                </svg>
                <span>Beli Produk</span>
            `;
            wrap.appendChild(a);
            wrap.classList.add('has-link');
        }

        function resetTryOnModalState() {
            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }

            const customerPhotoInput = document.getElementById('customerPhoto');
            const customerPreview = document.getElementById('customerPreview');
            const customerPlaceholder = document.getElementById('customerPlaceholder');
            const removePhotoBtn = document.getElementById('removePhotoBtn');
            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            const toggle = document.getElementById('useDummyModelToggle');

            useDummyModelForRealGenerate = false;
            if (toggle && !TRYON_DUMMY.enabled) {
                toggle.checked = false;
            }

            if (customerPhotoInput) {
                customerPhotoInput.value = '';
            }
            resetModelAdjustState();

            if (customerPreview) {
                customerPreview.removeAttribute('src');
                customerPreview.style.display = 'none';
            }

            if (customerPlaceholder) {
                customerPlaceholder.style.display = 'block';
            }

            if (removePhotoBtn) {
                removePhotoBtn.style.display = 'none';
            }

            if (resultPreview) {
                resultPreview.removeAttribute('src');
                resultPreview.style.display = 'none';
            }

            if (resultPlaceholder) {
                resultPlaceholder.textContent = '';
                resultPlaceholder.classList.remove('status-error', 'status-success');
                resultPlaceholder.style.display = 'none';
            }

            resetFeedbackForm();
            setStatus('', '');
            setHistoryPreviewMode(false);
            updateCompareMode(false);
            setComparisonPosition(50);
            setLoading(false);
            applyDummyModelSelectionUI();
            syncComparisonBeforeImage();
        }

        function openTryOnModal(el) {
            selectProduct(el);
            resetTryOnModalState();
            const modal = document.getElementById('tryOnModal');
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            refreshHistory();
        }

        function closeTryOnModal() {
            resetTryOnModalState();
            const modal = document.getElementById('tryOnModal');
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.getElementById('tryOnModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeTryOnModal();
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeHistoryPreviewModal();
                closeTryOnModal();
            }
        });

        document.getElementById('customerPhoto').addEventListener('change', async function() {
            const file = this.files && this.files[0] ? this.files[0] : null;
            const preview = document.getElementById('customerPreview');
            const placeholder = document.getElementById('customerPlaceholder');
            const removeBtn = document.getElementById('removePhotoBtn');

            setStatus('', '');
            if (!file) {
                resetModelAdjustState();
                preview.removeAttribute('src');
                preview.style.display = 'none';
                placeholder.style.display = 'block';
                removeBtn.style.display = 'none';
                syncComparisonBeforeImage();
                return;
            }

            await loadCustomerPhotoForAdjust(file);
        });

        document.getElementById('removePhotoBtn').addEventListener('click', function() {
            const input = document.getElementById('customerPhoto');
            const preview = document.getElementById('customerPreview');
            const placeholder = document.getElementById('customerPlaceholder');
            const removeBtn = document.getElementById('removePhotoBtn');

            input.value = '';
            resetModelAdjustState();
            preview.removeAttribute('src');
            preview.style.display = 'none';
            placeholder.style.display = 'block';
            removeBtn.style.display = 'none';
            setStatus('', '');
            syncComparisonBeforeImage();
        });

        function setStatus(message, type) {
            const note = document.getElementById('statusNote');
            if (!note) {
                return;
            }
            note.textContent = message || '';
            note.classList.remove('status-error', 'status-success');
            if (type === 'error') note.classList.add('status-error');
            if (type === 'success') note.classList.add('status-success');
        }

        function buildFeedbackSubmitUrl(sessionId) {
            return `${TRYON_PUBLIC_BASE_URL}/sessions/${sessionId}/feedback`;
        }

        function setFeedbackStatus(message, type = '') {
            const feedbackStatus = document.getElementById('feedbackStatus');
            if (!feedbackStatus) {
                return;
            }

            feedbackStatus.textContent = message || '';
            feedbackStatus.classList.remove('status-error', 'status-success');
            if (type === 'error') feedbackStatus.classList.add('status-error');
            if (type === 'success') feedbackStatus.classList.add('status-success');
        }

        function renderFeedbackStars() {
            const stars = document.querySelectorAll('#feedbackStars .feedback-star');
            stars.forEach((star) => {
                const starRating = Number(star.getAttribute('data-rating') || 0);
                star.classList.toggle('is-active', starRating <= selectedFeedbackRating && selectedFeedbackRating > 0);
            });
        }

        function resetFeedbackForm() {
            activeFeedbackSessionId = null;
            selectedFeedbackRating = 0;
            isSubmittingFeedback = false;

            const feedbackWrap = document.getElementById('feedbackWrap');
            const feedbackComment = document.getElementById('feedbackComment');
            const feedbackSubmitBtn = document.getElementById('feedbackSubmitBtn');

            if (feedbackWrap) {
                feedbackWrap.style.display = 'none';
            }

            if (feedbackComment) {
                feedbackComment.value = '';
            }

            if (feedbackSubmitBtn) {
                feedbackSubmitBtn.disabled = false;
                feedbackSubmitBtn.textContent = I18N.feedbackSubmit;
            }

            setFeedbackStatus('', '');
            renderFeedbackStars();
        }

        function showFeedbackForm(sessionPayload) {
            const feedbackWrap = document.getElementById('feedbackWrap');
            const feedbackComment = document.getElementById('feedbackComment');
            const feedbackSubmitBtn = document.getElementById('feedbackSubmitBtn');
            const sessionId = Number((sessionPayload && sessionPayload.id) ? sessionPayload.id : 0);

            if (!feedbackWrap || !feedbackComment || !feedbackSubmitBtn || !sessionId) {
                resetFeedbackForm();
                return;
            }

            activeFeedbackSessionId = sessionId;
            selectedFeedbackRating = Number(sessionPayload.feedback_rating || 0);
            feedbackComment.value = sessionPayload.feedback_comment || '';
            feedbackWrap.style.display = 'block';
            feedbackSubmitBtn.disabled = false;
            feedbackSubmitBtn.textContent = selectedFeedbackRating > 0 ? I18N.feedbackUpdate : I18N.feedbackSubmit;

            if (sessionPayload.feedback_submitted_at) {
                setFeedbackStatus(I18N.feedbackAlreadySent, 'success');
            } else {
                setFeedbackStatus('', '');
            }

            renderFeedbackStars();
            if (typeof feedbackWrap.scrollIntoView === 'function') {
                feedbackWrap.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        async function submitTryOnFeedback() {
            if (isSubmittingFeedback) {
                return;
            }

            if (!activeFeedbackSessionId) {
                setFeedbackStatus(I18N.feedbackSubmitFailed, 'error');
                return;
            }

            if (selectedFeedbackRating < 1 || selectedFeedbackRating > 5) {
                setFeedbackStatus(I18N.feedbackRatingRequired, 'error');
                return;
            }

            const feedbackComment = document.getElementById('feedbackComment');
            const feedbackSubmitBtn = document.getElementById('feedbackSubmitBtn');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            isSubmittingFeedback = true;
            if (feedbackSubmitBtn) {
                feedbackSubmitBtn.disabled = true;
            }
            setFeedbackStatus('', '');

            try {
                const response = await fetch(buildFeedbackSubmitUrl(activeFeedbackSessionId), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Tryon-Device-Id': resolveTryOnDeviceId(),
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        rating: selectedFeedbackRating,
                        comment: feedbackComment ? feedbackComment.value : '',
                    }),
                });

                const payload = await response.json();
                if (!response.ok) {
                    throw new Error(payload.message || I18N.feedbackSubmitFailed);
                }

                setFeedbackStatus(I18N.feedbackSaved, 'success');
                if (feedbackSubmitBtn) {
                    feedbackSubmitBtn.textContent = I18N.feedbackUpdate;
                }
            } catch (error) {
                setFeedbackStatus(error.message || I18N.feedbackSubmitFailed, 'error');
            } finally {
                isSubmittingFeedback = false;
                if (feedbackSubmitBtn) {
                    feedbackSubmitBtn.disabled = false;
                }
            }
        }

        function setLoading(loading) {
            const btn = document.getElementById('generateBtn');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            const mustDisableByQuota = remainingDailyQuota !== null && remainingDailyQuota <= 0;

            btn.disabled = loading || mustDisableByQuota;
            btn.textContent = loading ? 'Processing...' : 'Try-On';

            if (resultPlaceholder) {
                resultPlaceholder.innerHTML = loading
                    ? '<div class="loading-dots" aria-label="Loading"><span></span><span></span><span></span></div>'
                    : '';
                resultPlaceholder.style.display = loading ? 'flex' : 'none';
            }
        }

        function showResultPlaceholderMessage(message, type = '') {
            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            if (!resultPlaceholder || !resultPreview) {
                return;
            }

            resultPreview.removeAttribute('src');
            resultPreview.style.display = 'none';
            updateCompareMode(false);
            resultPlaceholder.style.display = 'flex';
            resultPlaceholder.textContent = message || '';
            resultPlaceholder.classList.remove('status-error', 'status-success');
            if (type === 'error') resultPlaceholder.classList.add('status-error');
            if (type === 'success') resultPlaceholder.classList.add('status-success');
        }

        function formatHistoryDateLabel(isoDate) {
            if (!isoDate) {
                return '-';
            }

            const dt = new Date(isoDate);
            if (Number.isNaN(dt.getTime())) {
                return '-';
            }

            return new Intl.DateTimeFormat('id-ID', {
                day: '2-digit',
                month: 'short',
                hour: '2-digit',
                minute: '2-digit',
            }).format(dt);
        }

        function showHistoryResult(sessionItem) {
            const url = (sessionItem && sessionItem.result_url) ? sessionItem.result_url : '';
            if (!url) {
                return;
            }

            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            resultPreview.src = url;
            resultPreview.style.display = 'block';
            resultPlaceholder.style.display = 'none';
            setHistoryPreviewMode(true);
            setComparisonPosition(50);
            setStatus(I18N.historyResultShown, 'success');
            showFeedbackForm(sessionItem);
        }

        function renderHistory(items) {
            const listEl = document.getElementById('historyList');
            const emptyEl = document.getElementById('historyEmpty');
            if (!listEl || !emptyEl) {
                return;
            }

            listEl.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                emptyEl.style.display = 'block';
                return;
            }

            emptyEl.style.display = 'none';
            items.forEach((item) => {
                if (!item || !item.result_url) {
                    return;
                }

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'history-item';
                btn.setAttribute('aria-label', I18N.historyItemAria);

                const img = document.createElement('img');
                img.src = item.result_url;
                img.alt = I18N.historyImageAlt;

                const time = document.createElement('span');
                time.className = 'history-item-time';
                time.textContent = formatHistoryDateLabel(item.created_at);

                btn.appendChild(img);
                btn.appendChild(time);
                btn.addEventListener('click', () => showHistoryResult(item));
                listEl.appendChild(btn);
            });
        }

        function renderFloatingHistory(items) {
            const listEl = document.getElementById('floatingHistoryList');
            const emptyEl = document.getElementById('floatingHistoryEmpty');
            if (!listEl || !emptyEl) {
                return;
            }

            listEl.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                emptyEl.style.display = 'block';
                return;
            }

            emptyEl.style.display = 'block';
            emptyEl.style.display = 'none';

            items.forEach((item) => {
                if (!item || !item.result_url) {
                    return;
                }

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'history-item';
                btn.setAttribute('aria-label', I18N.historyItemAria);

                const img = document.createElement('img');
                img.src = item.result_url;
                img.alt = I18N.historyImageAlt;

                const time = document.createElement('span');
                time.className = 'history-item-time';
                time.textContent = formatHistoryDateLabel(item.created_at);

                btn.appendChild(img);
                btn.appendChild(time);
                btn.addEventListener('click', () => {
                    const historyLink = item.result_url || '';
                    const currentCard = document.querySelector('#productGrid .card.selected') || document.querySelector('#productGrid .card');
                    const productLink = currentCard ? (currentCard.getAttribute('data-product-link-url') || '') : '';
                    if (historyLink) {
                        openHistoryPreviewModal(historyLink, productLink);
                    }
                    closeFloatingHistoryPanel();
                });
                listEl.appendChild(btn);
            });
        }

        function openFloatingHistoryPanel() {
            const panel = document.getElementById('floatingHistoryPanel');
            if (!panel) {
                return;
            }
            panel.classList.add('active');
            panel.setAttribute('aria-hidden', 'false');
            refreshHistory();
        }

        function closeFloatingHistoryPanel() {
            const panel = document.getElementById('floatingHistoryPanel');
            if (!panel) {
                return;
            }
            panel.classList.remove('active');
            panel.setAttribute('aria-hidden', 'true');
        }

        function openHistoryPreviewModal(url, productLinkUrl = '') {
            if (!url) {
                return;
            }

            const overlay = document.getElementById('historyPreviewOverlay');
            const image = document.getElementById('historyPreviewImage');
            const buyBtn = document.getElementById('historyPreviewBuy');
            if (!overlay || !image) {
                return;
            }

            image.src = url;
            const link = (productLinkUrl || selectedProductLinkUrl || '').trim();
            if (buyBtn) {
                if (link) {
                    buyBtn.href = link;
                    buyBtn.style.display = 'inline-flex';
                } else {
                    buyBtn.removeAttribute('href');
                    buyBtn.style.display = 'none';
                }
            }
            overlay.classList.add('active');
            overlay.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeHistoryPreviewModal() {
            const overlay = document.getElementById('historyPreviewOverlay');
            const image = document.getElementById('historyPreviewImage');
            const buyBtn = document.getElementById('historyPreviewBuy');
            if (!overlay || !image) {
                return;
            }

            overlay.classList.remove('active');
            overlay.setAttribute('aria-hidden', 'true');
            image.removeAttribute('src');
            if (buyBtn) {
                buyBtn.removeAttribute('href');
                buyBtn.style.display = 'none';
            }
            document.body.style.overflow = '';
        }

        async function refreshHistory() {
            try {
                const response = await fetch(TRYON_HISTORY_URL, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tryon-Device-Id': resolveTryOnDeviceId(),
                    },
                });

                const payload = await response.json();
                if (!response.ok) {
                    return;
                }

                renderHistory(payload.items || []);
                renderFloatingHistory(payload.items || []);
            } catch (error) {
                // History failure should not block the page.
            }
        }

        function updateQuotaUI(quota) {
            const el = document.getElementById('remainingQuotaText');
            if (!el || !quota) {
                return;
            }

            const limit = Number(quota.daily_limit ?? 3);
            const remaining = Number(quota.remaining ?? 0);
            remainingDailyQuota = remaining;
            el.textContent = `${remaining} / ${limit}`;

            if (remaining <= 0) {
                setStatus(I18N.dailyLimitReached, 'error');
            }

            const btn = document.getElementById('generateBtn');
            if (btn && !btn.disabled && remaining <= 0) {
                btn.disabled = true;
            }
        }

        function consumeDummyQuotaUI() {
            if (remainingDailyQuota === null) {
                return;
            }

            remainingDailyQuota = Math.max(remainingDailyQuota - 1, 0);
            const quotaEl = document.getElementById('remainingQuotaText');
            if (quotaEl) {
                const parts = quotaEl.textContent.split('/');
                const limit = Number((parts[1] || '').trim());
                if (!Number.isNaN(limit) && limit > 0) {
                    quotaEl.textContent = `${remainingDailyQuota} / ${limit}`;
                }
            }
        }

        function applyDummyModelSelectionUI() {
            const customerPhotoInput = document.getElementById('customerPhoto');
            const customerPreview = document.getElementById('customerPreview');
            const customerPlaceholder = document.getElementById('customerPlaceholder');
            const removePhotoBtn = document.getElementById('removePhotoBtn');
            const toggleWrap = document.getElementById('dummyModelToggleWrap');
            const toggle = document.getElementById('useDummyModelToggle');
            const hasDummyModelUrl = Boolean(TRYON_DUMMY.model_image_url);

            if (!toggleWrap || !toggle) {
                syncModelAdjustSurfaceState();
                return;
            }

            if (TRYON_DUMMY.enabled) {
                toggleWrap.style.display = 'none';
                useDummyModelForRealGenerate = hasDummyModelUrl;
                toggle.checked = hasDummyModelUrl;
                customerPhotoInput.value = '';
                customerPhotoInput.disabled = true;
                removePhotoBtn.style.display = 'none';
                resetModelAdjustState();

                if (hasDummyModelUrl) {
                    customerPreview.src = TRYON_DUMMY.model_image_url;
                    customerPreview.style.display = 'block';
                    customerPlaceholder.style.display = 'none';
                } else {
                    customerPreview.removeAttribute('src');
                    customerPreview.style.display = 'none';
                    customerPlaceholder.style.display = 'block';
                }
                syncComparisonBeforeImage();
                syncModelAdjustSurfaceState();
                return;
            }

            if (!hasDummyModelUrl) {
                toggleWrap.style.display = 'none';
                useDummyModelForRealGenerate = false;
                toggle.checked = false;
                customerPhotoInput.disabled = false;
                syncModelAdjustSurfaceState();
                return;
            }

            toggleWrap.style.display = 'inline-flex';
            useDummyModelForRealGenerate = toggle.checked;

            if (useDummyModelForRealGenerate) {
                customerPhotoInput.value = '';
                customerPhotoInput.disabled = true;
                removePhotoBtn.style.display = 'none';
                resetModelAdjustState();
                customerPreview.src = TRYON_DUMMY.model_image_url;
                customerPreview.style.display = 'block';
                customerPlaceholder.style.display = 'none';
                syncComparisonBeforeImage();
                syncModelAdjustSurfaceState();
                return;
            }

            customerPhotoInput.disabled = false;
            if (!customerPhotoInput.files || !customerPhotoInput.files[0]) {
                customerPreview.removeAttribute('src');
                customerPreview.style.display = 'none';
                customerPlaceholder.style.display = 'block';
                removePhotoBtn.style.display = 'none';
                resetModelAdjustState();
            } else if (modelAdjustState.image) {
                toggleModelAdjustPanel(true);
                applyModelAdjustStateToControls();
            }
            syncComparisonBeforeImage();
            syncModelAdjustSurfaceState();
        }

        async function refreshQuota() {
            try {
                const response = await fetch(@json(route('public.tryon.quota.show', ['seller_slug' => $seller->slug])), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Tryon-Device-Id': resolveTryOnDeviceId(),
                    },
                });

                const payload = await response.json();
                if (!response.ok) {
                    return;
                }

                updateQuotaUI(payload);
            } catch (error) {
                // Quota failure should not block the page.
            }
        }

        async function submitTryOn() {
            const customerPreview = document.getElementById('customerPreview');
            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            const customerPhotoInput = document.getElementById('customerPhoto');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            if (!selectedProductId) {
                setStatus(I18N.selectProductFirst, 'error');
                return;
            }

            resetFeedbackForm();

            const forceSellerDummy = Boolean(TRYON_DUMMY.enabled);
            if (forceSellerDummy && !TRYON_DUMMY.model_image_url) {
                setStatus(I18N.dummyModelUrlMissing, 'error');
                showResultPlaceholderMessage(I18N.dummyModelUrlMissing, 'error');
                return;
            }

            if (forceSellerDummy && !TRYON_DUMMY.result_url) {
                setStatus(I18N.dummyResultUrlMissing, 'error');
                showResultPlaceholderMessage(I18N.dummyResultUrlMissing, 'error');
                return;
            }

            const useDummyModelImage = forceSellerDummy
                ? Boolean(TRYON_DUMMY.model_image_url)
                : (Boolean(TRYON_DUMMY.model_image_url) && useDummyModelForRealGenerate);
            const file = customerPhotoInput.files && customerPhotoInput.files[0] ? customerPhotoInput.files[0] : null;
            if (!useDummyModelImage && (!file || !customerPreview.getAttribute('src'))) {
                setStatus(I18N.uploadModelFirst, 'error');
                if (resultPlaceholder) {
                    resultPlaceholder.style.display = 'none';
                    resultPlaceholder.textContent = '';
                    resultPlaceholder.classList.remove('status-error', 'status-success');
                }
                return;
            }

            if (remainingDailyQuota !== null && remainingDailyQuota <= 0) {
                setStatus(I18N.dailyLimitReached, 'error');
                showResultPlaceholderMessage(I18N.dailyLimitReached, 'error');
                setLoading(false);
                return;
            }

            setLoading(true);
            setStatus('', '');
            updateCompareMode(false);
            resultPreview.removeAttribute('src');
            resultPreview.style.display = 'none';
            resultPlaceholder.style.display = 'flex';

            try {
                const formData = new FormData();
                formData.append('product_id', String(selectedProductId));
                formData.append('use_dummy_model', useDummyModelImage ? '1' : '0');
                if (!useDummyModelImage) {
                    const adjustedFile = await buildAdjustedModelUploadFile();
                    const uploadFile = adjustedFile || file;
                    if (uploadFile) {
                        formData.append('customer_photo', uploadFile);
                    }
                }

                const createResponse = await fetch(@json(route('public.tryon.sessions.store', ['seller_slug' => $seller->slug])), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Tryon-Device-Id': resolveTryOnDeviceId(),
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const createPayload = await createResponse.json();
                if (!createResponse.ok) {
                    if (createPayload.quota) {
                        updateQuotaUI(createPayload.quota);
                    }
                    throw new Error(createPayload.message || I18N.failedCreateSession);
                }

                if (createPayload.quota) {
                    updateQuotaUI(createPayload.quota);
                }

                await pollTryOnStatus(createPayload.id);
            } catch (error) {
                setStatus(error.message || 'Terjadi kesalahan saat generate try-on.', 'error');
                showResultPlaceholderMessage(error.message || 'Terjadi kesalahan saat generate try-on.', 'error');
                setLoading(false);
            }
        }

        async function pollTryOnStatus(sessionId) {
            const resultPreview = document.getElementById('resultPreview');
            const resultPlaceholder = document.getElementById('resultPlaceholder');
            const statusUrlBase = @json(url('/'));

            if (pollTimer) {
                clearInterval(pollTimer);
                pollTimer = null;
            }

            let attempts = 0;
            const maxAttempts = 90;

            pollTimer = setInterval(async () => {
                attempts += 1;
                try {
                    const response = await fetch(`${statusUrlBase}/${@json($seller->slug)}/try-on/sessions/${sessionId}`, {
                        headers: {
                            'Accept': 'application/json'
                        },
                    });

                    const payload = await response.json();
                    if (!response.ok) {
                        throw new Error(payload.message || I18N.failedCheckStatus);
                    }

                    if (payload.status === 'completed') {
                        clearInterval(pollTimer);
                        pollTimer = null;

                        if (payload.result_url) {
                            resultPreview.src = payload.result_url;
                            resultPreview.style.display = 'block';
                            resultPlaceholder.style.display = 'none';
                            setHistoryPreviewMode(false);
                            updateCompareMode(true);
                            setComparisonPosition(50);
                        }

                        showFeedbackForm(payload);
                        setStatus(I18N.generateDone, 'success');
                        setLoading(false);
                        refreshHistory();
                        return;
                    }

                    if (payload.status === 'failed') {
                        clearInterval(pollTimer);
                        pollTimer = null;
                        const errorMessage = payload.error_message || 'Try-on gagal diproses.';
                        setStatus(errorMessage, 'error');
                        showResultPlaceholderMessage(errorMessage, 'error');
                        setLoading(false);
                        return;
                    }

                    if (attempts >= maxAttempts) {
                        clearInterval(pollTimer);
                        pollTimer = null;
                        const timeoutMessage = I18N.processingRetryLater;
                        setStatus(timeoutMessage, 'error');
                        showResultPlaceholderMessage(timeoutMessage, 'error');
                        setLoading(false);
                    }
                } catch (error) {
                    clearInterval(pollTimer);
                    pollTimer = null;
                    const pollingErrorMessage = error.message || I18N.failedPolling;
                    setStatus(pollingErrorMessage, 'error');
                    showResultPlaceholderMessage(pollingErrorMessage, 'error');
                    setLoading(false);
                }
            }, 2000);
        }

        (function initPage() {
            const current = document.querySelector('#productGrid .card.selected');
            if (current) {
                selectProduct(current);
            }

            const autoOpenTrigger = document.getElementById('autoOpenTryOnTrigger');
            if (autoOpenTrigger) {
                openTryOnModal(autoOpenTrigger);
            }

            const floatingBtn = document.getElementById('floatingHistoryBtn');
            const floatingClose = document.getElementById('floatingHistoryClose');
            const floatingPanel = document.getElementById('floatingHistoryPanel');
            if (floatingBtn) {
                floatingBtn.addEventListener('click', openFloatingHistoryPanel);
            }
            if (floatingClose) {
                floatingClose.addEventListener('click', closeFloatingHistoryPanel);
            }
            if (floatingPanel) {
                document.addEventListener('click', function(event) {
                    if (!floatingPanel.classList.contains('active')) {
                        return;
                    }
                    if (floatingPanel.contains(event.target) || (floatingBtn && floatingBtn.contains(event.target))) {
                        return;
                    }
                    closeFloatingHistoryPanel();
                });
            }
            const historyPreviewOverlay = document.getElementById('historyPreviewOverlay');
            const historyPreviewClose = document.getElementById('historyPreviewClose');
            if (historyPreviewClose) {
                historyPreviewClose.addEventListener('click', closeHistoryPreviewModal);
            }
            if (historyPreviewOverlay) {
                historyPreviewOverlay.addEventListener('click', function(event) {
                    if (event.target === historyPreviewOverlay) {
                        closeHistoryPreviewModal();
                    }
                });
            }

            const feedbackStars = document.querySelectorAll('#feedbackStars .feedback-star');
            feedbackStars.forEach((starBtn) => {
                starBtn.addEventListener('click', function() {
                    selectedFeedbackRating = Number(this.getAttribute('data-rating') || 0);
                    renderFeedbackStars();
                    setFeedbackStatus('', '');
                });
            });

            const feedbackSubmitBtn = document.getElementById('feedbackSubmitBtn');
            if (feedbackSubmitBtn) {
                feedbackSubmitBtn.addEventListener('click', submitTryOnFeedback);
            }

            const toggle = document.getElementById('useDummyModelToggle');
            if (toggle) {
                // Default OFF when seller dummy mode is disabled, even if dummy URL exists.
                toggle.checked = false;
                toggle.addEventListener('change', function() {
                    useDummyModelForRealGenerate = this.checked;
                    applyDummyModelSelectionUI();
                });
            }
            const compareSlider = document.getElementById('compareSlider');
            if (compareSlider) {
                compareSlider.addEventListener('input', function() {
                    setComparisonPosition(this.value);
                });
            }
            initModelAdjustGestures();
            window.addEventListener('resize', function() {
                if (!isModelAdjustInteractive()) {
                    return;
                }

                queueModelAdjustPreviewRender();
            });
            const modelAdjustZoom = document.getElementById('modelAdjustZoom');
            const modelAdjustOffsetX = document.getElementById('modelAdjustOffsetX');
            const modelAdjustOffsetY = document.getElementById('modelAdjustOffsetY');
            const modelAdjustResetBtn = document.getElementById('modelAdjustResetBtn');

            [modelAdjustZoom, modelAdjustOffsetX, modelAdjustOffsetY].forEach((input) => {
                if (!input) {
                    return;
                }

                input.addEventListener('input', updateModelAdjustStateFromControls);
            });

            if (modelAdjustResetBtn) {
                modelAdjustResetBtn.addEventListener('click', function() {
                    if (!modelAdjustState.image) {
                        return;
                    }

                    modelAdjustState.zoom = MODEL_ADJUST_ZOOM_MIN;
                    modelAdjustState.offsetX = 0;
                    modelAdjustState.offsetY = 0;
                    applyModelAdjustStateToControls();
                    queueModelAdjustPreviewRender();
                });
            }
            const resultPreview = document.getElementById('resultPreview');
            if (resultPreview) {
                resultPreview.style.display = 'none';
            }
            resetFeedbackForm();
            updateCompareMode(false);
            setComparisonPosition(50);
            resetModelAdjustState();
            applyDummyModelSelectionUI();
            refreshQuota();
            refreshHistory();
        })();
    </script>
</body>

</html>
