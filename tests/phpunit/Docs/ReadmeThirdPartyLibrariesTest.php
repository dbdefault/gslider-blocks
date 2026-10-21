<?php
/**
 * Unit tests for readme.txt Third-Party Libraries declarations.
 *
 * Verifies that:
 * 1. readme.txt exists and contains a Third-Party Libraries section.
 * 2. The bundled fslightbox entry declares the exact MIT build version (3.7.4).
 * 3. The fslightbox license is stated as MIT License with a clear license URL.
 * 4. Ambiguous proprietary-style wording ("Free / Commercial") is not used for fslightbox.
 *
 * @package GSlider\Tests\Docs
 */

require_once dirname( __DIR__ ) . '/bootstrap.php';

/**
 * Test class for WordPress.org third-party library disclosure in readme.txt.
 */
class ReadmeThirdPartyLibrariesTest {

	/**
	 * Helper assertion for strict boolean conditions.
	 *
	 * @param bool   $condition Evaluated boolean condition.
	 * @param string $message   Assertion message.
	 * @return void
	 */
	public function assert_true( bool $condition, string $message ): void {
		if ( true !== $condition ) {
			fwrite( STDERR, "FAIL: {$message}\n" );
			exit( 1 );
		}
		fwrite( STDOUT, "OK: {$message}\n" );
	}

	/**
	 * Run all test methods in class.
	 *
	 * @return void
	 */
	public function run_all_tests(): void {
		echo "=== GSlider readme.txt Third-Party Libraries Tests ===\n\n";

		$this->test_readme_exists();
		$this->test_fslightbox_declares_exact_mit_version_and_license();
		$this->test_fslightbox_does_not_use_ambiguous_free_commercial_license();

		echo "\n=== All readme third-party library tests passed ===\n";
	}

	/**
	 * Load readme.txt contents from the plugin root.
	 *
	 * @return string
	 */
	private function load_readme(): string {
		$path = ABSPATH . 'readme.txt';
		$this->assert_true( is_readable( $path ), 'readme.txt is readable at plugin root' );
		$contents = file_get_contents( $path );
		$this->assert_true( false !== $contents && '' !== $contents, 'readme.txt contents are non-empty' );
		return $contents;
	}

	/**
	 * Extract the fslightbox bullet block from the Third-Party Libraries section.
	 *
	 * @param string $readme Full readme.txt contents.
	 * @return string
	 */
	private function extract_fslightbox_block( string $readme ): string {
		$this->assert_true(
			false !== strpos( $readme, '== Third-Party Libraries ==' ),
			'readme.txt contains Third-Party Libraries section'
		);

		if ( ! preg_match(
			'/\*\s+\*\*fslightbox\*\*.*?(?=\n\*\s+\*\*|\n== |\z)/s',
			$readme,
			$matches
		) ) {
			fwrite( STDERR, "FAIL: Could not locate fslightbox entry in Third-Party Libraries\n" );
			exit( 1 );
		}

		return $matches[0];
	}

	/**
	 * Test 1: readme.txt is present and loadable.
	 *
	 * @return void
	 */
	public function test_readme_exists(): void {
		$this->load_readme();
	}

	/**
	 * Test 2: fslightbox lists exact free MIT build version and MIT license URL.
	 *
	 * @return void
	 */
	public function test_fslightbox_declares_exact_mit_version_and_license(): void {
		$block = $this->extract_fslightbox_block( $this->load_readme() );

		$this->assert_true(
			false !== strpos( $block, 'v3.7.4' ),
			'fslightbox entry declares exact version v3.7.4'
		);
		$this->assert_true(
			false !== strpos( $block, 'MIT License' ),
			'fslightbox entry declares MIT License'
		);
		$this->assert_true(
			false !== strpos( $block, 'https://github.com/banthagroup/fslightbox/blob/master/LICENSE' ),
			'fslightbox entry links to the MIT LICENSE file'
		);
		$this->assert_true(
			false !== strpos( $block, 'https://www.npmjs.com/package/fslightbox' ),
			'fslightbox entry links to the npm package page'
		);
	}

	/**
	 * Test 3: fslightbox must not use ambiguous Free / Commercial license wording.
	 *
	 * @return void
	 */
	public function test_fslightbox_does_not_use_ambiguous_free_commercial_license(): void {
		$block = $this->extract_fslightbox_block( $this->load_readme() );

		$this->assert_true(
			false === strpos( $block, 'Free / Commercial' ),
			'fslightbox entry does not use ambiguous Free / Commercial license wording'
		);
	}
}

// Standalone execution entry point.
( new ReadmeThirdPartyLibrariesTest() )->run_all_tests();
