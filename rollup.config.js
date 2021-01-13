import {terser} from 'rollup-plugin-terser';
import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import babel from '@rollup/plugin-babel';
import postcss from 'rollup-plugin-postcss';
import postcssPresetEnv from 'postcss-preset-env/index.js';

process.chdir(__dirname);

const defaultBrowsers = ['defaults', 'not ie > 0'];

const defaultCfg = {
  input: './index.js',
  output: {
    file: './resources/form.min.js',
    name: 'Pagelets',
    format: 'iife',
  },
  plugins: [
    //css
    postcss(
      {
        extract: true,
        minimize: true,
        plugins: [
          postcssPresetEnv({browsers: defaultBrowsers}),
        ],
      }),
    resolve({browser: true, preferBuiltins: false}),
    commonjs(),
    terser(),

    babel(
      {
        babelHelpers: 'bundled',
        babelrc: false,
        exclude: [/\/core-js\//],
        presets: [
          [
            '@babel/preset-env',
            {
              corejs: 3,
              modules: false,
              useBuiltIns: 'usage',
              targets: defaultBrowsers,
            },
          ],
        ],
      }),
  ]
};

export default [defaultCfg];
