/**
 * Konfigurasi ESLint (flat config).
 * Catatan: ESLint hanya memeriksa file JavaScript.
 * File PHP diperiksa terpisah lewat `npm run lint:php` (php -l).
 */

const js = require("@eslint/js");
const globals = require("globals");

module.exports = [
  {
    ignores: ["node_modules/**", "assets/css/**", "assets/js/vendor/**"],
  },
  js.configs.recommended,
  {
    files: ["**/*.js"],
    languageOptions: {
      ecmaVersion: "latest",
      sourceType: "script",
      globals: {
        ...globals.browser,
      },
    },
    rules: {
      "no-unused-vars": "error",
      "no-undef": "error",
      "no-console": "off",
    },
  },
  {
    files: ["eslint.config.js"],
    languageOptions: {
      sourceType: "commonjs",
      globals: {
        ...globals.node,
      },
    },
  },
];
