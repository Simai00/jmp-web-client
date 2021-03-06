module.exports = {
    root: true,
    env: {
        node: true
    },
    'extends': [
        'plugin:vue/essential',
        '@vue/standard'
    ],
    rules: {
        'no-console': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
        'semi': [2, 'always'],
        'indent': [0, 4],
        'space-before-function-paren': [0],
        'object-curly-spacing': [0]
    },
    parserOptions: {
        parser: 'babel-eslint'
    }
};
