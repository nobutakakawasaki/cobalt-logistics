<?php
/**
 * Template Name: 社員ログイン
 *
 * Internal staff-login page (STAFF_AUTH_BRIEF.md). Intentionally not linked
 * from the main navigation or footer. Submits to admin-post.php
 * (cobalt_staff_login), which authenticates via wp_signon() — see
 * cobalt_logistics_handle_staff_login() and the additive
 * cobalt_logistics_authenticate_staff_id() `authenticate` filter in
 * functions.php — and redirects to /wp-admin/ on success.
 *
 * Failure message is intentionally generic (does not say whether the 社員ID
 * itself was wrong vs. the password) to avoid account-enumeration.
 *
 * @package Cobalt_Logistics
 */

get_header();

$cobalt_staff_login_status = isset( $_GET['staff_login'] ) ? sanitize_key( wp_unslash( $_GET['staff_login'] ) ) : '';
?>

<main id="main">

	<section class="page-hero">
		<div class="container">
			<p class="eyebrow">STAFF</p>
			<h1 class="page-hero__title">社員ログイン</h1>
			<p class="page-hero__lead">社内向けページです。社員IDとパスワードでログインしてください。</p>
		</div>
	</section>

	<section class="section">
		<div class="container">

			<?php if ( 'error' === $cobalt_staff_login_status ) : ?>
				<p class="form-message form-message--error" role="alert">社員IDまたはパスワードが正しくありません。</p>
			<?php endif; ?>

			<form class="contact-form" id="staff-login-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate>
				<input type="hidden" name="action" value="cobalt_staff_login">
				<?php wp_nonce_field( 'cobalt_staff_login', 'cobalt_staff_login_nonce' ); ?>

				<div class="contact-form__field">
					<label for="staff-login-id">社員ID <span class="required">必須</span></label>
					<input type="text" id="staff-login-id" name="staff_login_id" autocomplete="username" required>
				</div>
				<div class="contact-form__field">
					<label for="staff-login-password">パスワード <span class="required">必須</span></label>
					<input type="password" id="staff-login-password" name="staff_login_password" autocomplete="current-password" required>
				</div>

				<button type="submit" class="btn btn--solid" style="width:100%;">ログイン</button>
			</form>

			<p style="text-align:center; margin-top: 24px;"><a href="<?php echo esc_url( cobalt_logistics_page_url( 'staff-register' ) ); ?>">アカウントをお持ちでない方はこちら（登録）</a></p>
		</div>
	</section>

</main>

<?php
get_footer();
