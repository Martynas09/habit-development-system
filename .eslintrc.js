module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'plugin:vue/vue3-essential',
    'airbnb-base',
  ],
  overrides: [
    {
      files: ['*.vue'],
      rules: {
        'import/no-extraneous-dependencies': 'off',
        'import/no-unresolved': 'off',
        'max-len': 'off',
        'vue/no-v-text-v-html-on-component': 'off',
        'linebreak-style': 'off',
        'vue/multi-word-component-names': 'off',
        'no-undef': 'off',
      },
    },
  ],
  parserOptions: {
    ecmaVersion: 'latest',
    sourceType: 'module',
  },
  plugins: [
    'vue',
  ],
  rules: {
  },
};
