# Tailwind CSS v4 Upgrade Guide

**Date:** January 1, 2026
**Current Version:** Tailwind CSS v3.4.19
**Target Version:** Tailwind CSS v4 (via @tailwindcss/vite)

---

## 🚨 npm Installation Issue Encountered

During the upgrade process, we encountered a persistent npm installation issue where devDependencies are not being installed properly. This appears to be an npm configuration or caching issue on the system.

**Symptoms:**
- `npm install` only installs 15 packages instead of expected 200+
- `node_modules` contains only production dependencies
- devDependencies listed in package.json are not being installed
- npm reports "up to date" even when packages are missing

**Attempted Solutions:**
- Cleared npm cache (`npm cache clean --force`)
- Removed node_modules and package-lock.json multiple times
- Tried `npm ci` for clean install from lock file
- Tried `npm install --force` and `--legacy-peer-deps`
- Verified npm configuration
- No .npmrc file present
- No workspace configuration

---

## 📋 Manual Upgrade Steps

Follow these steps to manually upgrade to Tailwind CSS v4:

###Step 1: Fix npm Installation

**Option A: Try pnpm (Recommended)**
```bash
# Install pnpm globally
npm install -g pnpm

# Remove existing installations
rm -rf node_modules package-lock.json

# Install using pnpm
pnpm install
```

**Option B: Try yarn**
```bash
# Install yarn globally
npm install -g yarn

# Remove existing installations
rm -rf node_modules package-lock.json

# Install using yarn
yarn install
```

**Option C: Reinstall npm**
```bash
# Reinstall npm itself
npm install -g npm@latest

# Clear all caches
npm cache clean --force

# Remove and reinstall
rm -rf node_modules package-lock.json
npm install
```

### Step 2: Update package.json

Once npm is working, update `package.json`:

```json
{
    "devDependencies": {
        "@tailwindcss/forms": "^0.4.0-alpha.2",
        "@tailwindcss/vite": "^4.1.18",
        "alpinejs": "^3.15.3",
        "axios": "^1.13.2",
        "concurrently": "^9.2.1",
        "laravel-vite-plugin": "^2.0.1",
        "postcss": "^8.5.6",
        "rollup-plugin-visualizer": "^6.0.5",
        "terser": "^5.44.1",
        "vite": "^7.3.0",
        "vite-plugin-compression": "^0.5.1"
    }
}
```

**Note:** Remove `tailwindcss` and `autoprefixer` - they're included in `@tailwindcss/vite` v4.

### Step 3: Update vite.config.js

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { visualizer } from 'rollup-plugin-visualizer';
import viteCompression from 'vite-plugin-compression';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(), // ← Add Tailwind v4 plugin
        // ... rest of plugins
    ],
    // ... rest of config
});
```

### Step 4: Update resources/css/app.css

Replace the old v3 directives with v4 syntax:

**Before (v3):**
```css
@tailwind base;
@tailwind components;
@tailwind utilities;
```

**After (v4):**
```css
@import "tailwindcss";

/* Tailwind v4 Theme Configuration */
@theme {
    /* Custom Colors */
    --color-primary: #f97316;
    --color-secondary: #ec4899;

    /* Font Family */
    --font-sans: Inter, ui-sans-serif, system-ui, sans-serif;
}

/* Form Plugin Styles */
@plugin "@tailwindcss/forms";

@layer base {
    html {
        scroll-behavior: smooth;
    }

    /* Your custom styles here */
}
```

### Step 5: Remove Old Config Files

```bash
rm tailwind.config.js
rm postcss.config.js
```

Tailwind v4 uses CSS-based configuration instead of JavaScript config files.

### Step 6: Test the Build

```bash
# Development build
npm run dev

# Production build
npm run build
```

---

## 🔄 Migration Guide: v3 to v4

### Configuration Changes

**Tailwind v3:**
- Used `tailwind.config.js` (JavaScript)
- Required `postcss.config.js`
- Used `@tailwind` directives

**Tailwind v4:**
- Uses `@theme` in CSS
- No JavaScript config needed
- Uses `@import "tailwindcss"`
- Configuration via CSS custom properties

### Theme Customization

**Before (tailwind.config.js):**
```javascript
module.exports = {
    theme: {
        extend: {
            colors: {
                primary: '#f97316',
                secondary: '#ec4899',
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
    },
}
```

**After (resources/css/app.css):**
```css
@theme {
    --color-primary: #f97316;
    --color-secondary: #ec4899;
    --font-sans: Inter, ui-sans-serif, system-ui, sans-serif;
}
```

### Plugin Configuration

**Before:**
```javascript
// tailwind.config.js
import forms from '@tailwindcss/forms';

module.exports = {
    plugins: [forms],
}
```

**After:**
```css
/* resources/css/app.css */
@plugin "@tailwindcss/forms";
```

### Dark Mode

Dark mode still works the same way:

```html
<html class="dark">
```

And in your Alpine.js:

```javascript
x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }"
:class="{ 'dark': darkMode }"
```

---

## ✨ Tailwind v4 Benefits

### Performance Improvements
- **10x faster** compilation
- **Smaller bundle sizes**
- **Better tree-shaking**
- **Faster HMR** (Hot Module Replacement)

### Developer Experience
- **CSS-based configuration** (easier to understand)
- **No PostCSS required**
- **Native CSS nesting**
- **Better TypeScript support**

### New Features
- **CSS-first** configuration
- **Improved @apply**
- **Better arbitrary values**
- **Enhanced variant system**
- **Native cascade layers**

---

## 📊 Expected Build Output

After successful upgrade:

```
vite v7.3.0 building for production...
✓ 61 modules transformed.
✓ built in ~4s

CSS:  ~70-80 KB → 8-10 KB (compressed)
JS:   ~160 KB → 60-70 KB (compressed)

Total compression: ~75%
```

---

## 🔍 Troubleshooting

### Issue 1: npm devDependencies Not Installing

**Symptoms:**
- Only 15 packages installed instead of 200+
- Missing vite, tailwindcss, alpine.js, etc.

**Solutions:**
1. Try pnpm or yarn instead of npm
2. Reinstall npm: `npm install -g npm@latest`
3. Check for conflicting global packages
4. Verify no workspace configuration
5. Try in a different directory

### Issue 2: "Cannot find module '@tailwindcss/vite'"

**Solution:**
```bash
npm install @tailwindcss/vite@latest --save-dev
```

### Issue 3: CSS not compiling

**Check:**
1. `@import "tailwindcss"` is first line in app.css
2. vite.config.js has `tailwindcss()` plugin
3. No old tailwind.config.js file

### Issue 4: Custom colors not working

**Solution:**
Use CSS custom properties with `--color-` prefix:

```css
@theme {
    --color-brand: #3b82f6;
}
```

Then use as:
```html
<div class="bg-brand text-white">
```

### Issue 5: Forms plugin styles missing

**Check:**
```css
@plugin "@tailwindcss/forms";
```

Is present in app.css and package installed:
```bash
npm install @tailwindcss/forms@next --save-dev
```

---

## 📝 Complete File Changes

### 1. package.json
```json
{
    "devDependencies": {
        "@tailwindcss/forms": "^0.4.0-alpha.2",
        "@tailwindcss/vite": "^4.1.18",
        // Remove: "tailwindcss", "autoprefixer"
    }
}
```

### 2. vite.config.js
```javascript
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({ /* ... */ }),
        tailwindcss(), // Add this
    ],
});
```

### 3. resources/css/app.css
```css
@import "tailwindcss";

@theme {
    --color-primary: #f97316;
    --color-secondary: #ec4899;
    --font-sans: Inter, ui-sans-serif, system-ui, sans-serif;
}

@plugin "@tailwindcss/forms";

@layer base {
    /* Your custom styles */
}
```

### 4. Remove Files
- ❌ tailwind.config.js
- ❌ postcss.config.js

---

## 🧪 Testing Checklist

After upgrade, test:

- [ ] Development server starts: `npm run dev`
- [ ] Production build works: `npm run build`
- [ ] Tailwind utilities work (bg-primary, text-secondary)
- [ ] Dark mode toggle works
- [ ] Custom fonts load (Inter)
- [ ] Form styles apply (from @tailwindcss/forms)
- [ ] Responsive breakpoints work
- [ ] Custom scrollbar styles work
- [ ] Alpine.js interactivity works
- [ ] Build size is similar or smaller

---

## 📚 Resources

### Official Documentation
- **Tailwind CSS v4:** https://tailwindcss.com/docs/v4-beta
- **@tailwindcss/vite Plugin:** https://tailwindcss.com/docs/installation/vite
- **Migration Guide:** https://tailwindcss.com/docs/upgrade-guide

### Package Versions
```
@tailwindcss/vite: 4.1.18 (includes Tailwind CSS v4)
@tailwindcss/forms: 0.4.0-alpha.2 (v4 compatible)
vite: 7.3.0
```

---

## 🔙 Rollback Plan

If upgrade fails, rollback:

```bash
# Restore from git
git restore package.json vite.config.js resources/css/app.css
git restore tailwind.config.js postcss.config.js

# Reinstall old dependencies
rm -rf node_modules package-lock.json
npm install

# Rebuild
npm run build
```

---

## 💡 Current Status

**Blocked By:** npm installation issue - devDependencies not installing

**Next Steps:**
1. Fix npm installation (try pnpm/yarn)
2. Once packages install, apply the configuration changes above
3. Test build
4. Commit changes

**Files Ready for Upgrade:**
- ✅ vite.config.js (updated)
- ✅ resources/css/app.css (updated)
- ✅ tailwind.config.js (can be deleted)
- ✅ postcss.config.js (can be deleted)

**Waiting For:**
- Package installation to work properly

---

**Note:** All configuration changes have been prepared. Once the npm installation issue is resolved, the upgrade can be completed by running `npm install` followed by `npm run build`.

---

*Last Updated: January 1, 2026*
*Status: ⏸️ Blocked by npm installation issue*
*Tailwind v4 Version: 4.1.18 (via @tailwindcss/vite)*
