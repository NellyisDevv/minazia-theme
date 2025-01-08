const ignorePatterns = [
  '**/*.tsignore.ts',

  // This assumes that the types is automatically generated by tsc; No need to lint the output.
  '**/build-types/**/*.d.ts',
];

module.exports = { ignorePatterns };
