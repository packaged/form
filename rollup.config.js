import resolve from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';

process.chdir(__dirname);

const defaultCfg = {
  input: './demo/demo.js',
  output: {
    file: './demo/assets/demo.min.js',
    name: 'Pagelets',
    format: 'iife',
  },
  plugins: [
    resolve({browser: true, preferBuiltins: false}),
    commonjs(),
  ]
};

export default [defaultCfg];
