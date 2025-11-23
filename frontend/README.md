# Frontend — Amin Talent Solutions

This frontend is a Vue 3 + Vite (or Vue CLI) app built with Tailwind CSS. The goal of the frontend is to provide a professional, polished dashboard and public site for connecting talents and companies.

Quick start

1. From repository root open a terminal and change to the frontend folder:

```powershell
cd "frontend"
npm install
npm run dev
```

2. The dev server usually serves the app at http://localhost:3000 (or the port shown by the dev script).

Branding / Logo

- Place your logo image at `frontend/src/assets/logo.png` and the header will attempt to load it automatically at runtime; if not found the initials fallback (AT) will be used instead.

Design system

- Tokens and global helpers are in `src/assets/styles/design-system.css` and included by `src/assets/styles/main.css`.
- Tailwind configuration is in `tailwind.config.js` (custom colors, shadows, animations).

Components added so far

- `src/components/ui/BaseButton.vue` — standardized buttons with variants and loading.
- `src/components/ui/BaseInput.vue` — reusable input/select/textarea with v-model.
- `src/components/ui/SkeletonCard.vue` — small skeleton used for loading states.
- `src/components/common/Header.vue` / `Footer.vue` — global header/footer.
- `src/components/common/ThemeToggle.vue` — light/dark toggle persisted in localStorage.

Next steps

- Replace raw markup in dashboards with Base components and skeletons.
- Add more skeletons, charts and accessible ARIA attributes across the UI.
- Run visual testing and responsiveness checks.

If you want me to continue, I'll: create skeleton states in each dashboard, replace common controls with Base components, and add a small set of unit tests for the UI primitives.
# frontend

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
