// Use the official PostCSS adapter for Tailwind v4+ which moved the plugin
// into the @tailwindcss/postcss package. Export an array so vue-cli's
// postcss-loader picks up the plugins in order.
// Support both the new @tailwindcss/postcss adapter (Tailwind v4+)
// and the older direct tailwindcss plugin. This makes the PostCSS
// config robust across environments where one or the other may be
// installed during dependency resolution.
let tailwindPlugin;
try {
  // Preferred for Tailwind v4+ where the PostCSS plugin was split out
  tailwindPlugin = require('@tailwindcss/postcss');
} catch (err) {
  // Fallback to the classic tailwindcss plugin if the adapter isn't present
  // (older setups or when the package wasn't installed).
  // eslint-disable-next-line global-require
  tailwindPlugin = require('tailwindcss');
}

module.exports = {
  plugins: [
    // Some plugins (including tailwind adapters) export a function to be
    // invoked. Others work as the plugin itself; calling here is safe for
    // both since tailwindcss returns a function.
    tailwindPlugin(),
    require('autoprefixer'),
  ],
};
